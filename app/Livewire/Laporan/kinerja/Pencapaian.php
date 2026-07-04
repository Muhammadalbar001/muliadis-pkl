<?php

namespace App\Livewire\Laporan\Kinerja;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Barryvdh\DomPDF\Facade\Pdf;

class Pencapaian extends Component
{
    use WithPagination;

    public $bulan;
    public $search = '';
    public $filterCabang = '';

    public function mount()
    {
        $this->bulan = date('Y-m');
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedBulan() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->reset(['filterCabang', 'search']);
        $this->bulan = date('Y-m');
        $this->resetPage();
    }

    public function getDataLaporan()
    {
        $bulanPilih = $this->bulan ?: date('Y-m');
        $dateObj = Carbon::parse($bulanPilih . '-01');
        
        $salesQuery = Sales::whereIn('status', ['Active', 'aktif', 'Aktif']);
        
        if ($this->search) {
            $salesQuery->where(function($q) {
                $q->where('sales_name', 'like', '%' . $this->search . '%')
                  ->orWhere('sales_code', 'like', '%' . $this->search . '%');
            });
        }
        if ($this->filterCabang) {
            $salesQuery->where('city', $this->filterCabang);
        }

        $allSales = $salesQuery->orderBy('sales_name')->get();

        $targets = SalesTarget::where('year', $dateObj->year)
            ->where('month', $dateObj->month)
            ->get()->keyBy('sales_id');

        $realisasi = Penjualan::whereMonth('tgl_penjualan', $dateObj->month)
            ->whereYear('tgl_penjualan', $dateObj->year)
            ->select('sales_name', DB::raw("SUM(total_grand) as total_ims"))
            ->groupBy('sales_name')
            ->get()->keyBy('sales_name');

        $laporan = [];
        $summary = ['target' => 0, 'real' => 0];

        foreach ($allSales as $sales) {
            $t = $targets->get($sales->id);
            $r = $realisasi->first(fn($i) => strtoupper($i->sales_name) === strtoupper($sales->sales_name));

            $targetIMS = $t ? (float)$t->target_ims : 0;
            $realIMS = $r ? (float)$r->total_ims : 0;

            $summary['target'] += $targetIMS;
            $summary['real'] += $realIMS;

            $laporan[] = [
                'kode' => $sales->sales_code ?? '-',
                'nama' => $sales->sales_name,
                'cabang' => $sales->city,
                'target_ims' => $targetIMS,
                'real_ims' => $realIMS,
                'persen_ims' => $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0,
                'gap' => $realIMS - $targetIMS,
            ];
        }

        usort($laporan, fn($a, $b) => $b['persen_ims'] <=> $a['persen_ims']);
        
        return ['data' => collect($laporan), 'summary' => $summary];
    }

    public function render()
    {
        $hasil = $this->getDataLaporan();
        
        // Manual Pagination untuk Collection
        $perPage = 15;
        $items = $hasil['data'];
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($items), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
            'query' => request()->query()
        ]);

        $optCabang = Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'));

        return view('livewire.laporan.kinerja.pencapaian', [
            'laporan' => $paginated,
            'summary' => $hasil['summary'],
            'optCabang' => $optCabang
        ])->layout('layouts.app');
    }

    public function exportPdf()
    {
        $hasil = $this->getDataLaporan();
        $dateObj = \Carbon\Carbon::parse($this->bulan . '-01');

        $dataView = [
            'periode' => $dateObj->translatedFormat('F Y'),
            'cetak_oleh' => auth()->user()->name ?? 'Administrator',
            'tgl_cetak' => now()->format('d/m/Y H:i'),
            'data' => $hasil['data']
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.laporan.exports.kinerja-penjualan-pdf', $dataView)->setPaper('a4', 'landscape');
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Kinerja_Penjualan_' . $this->bulan . '.pdf');
    }
}