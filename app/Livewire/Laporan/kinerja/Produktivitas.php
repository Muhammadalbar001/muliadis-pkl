<?php

namespace App\Livewire\Laporan\Kinerja;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use App\Models\Transaksi\Penjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Produktivitas extends Component
{
    use WithPagination;

    public $bulan;
    public $search = '';
    public $filterCabang = '';
    public $minNominal = 50000;

    public function mount()
    {
        $this->bulan = date('Y-m');
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedBulan() { $this->resetPage(); }
    public function updatedMinNominal() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->reset(['filterCabang', 'search']);
        $this->bulan = date('Y-m');
        $this->minNominal = 50000;
        $this->resetPage();
    }

    public function getDataLaporan()
    {
        $bulanPilih = $this->bulan ?: date('Y-m');
        $dateObj = Carbon::parse($bulanPilih . '-01');
        $start = $dateObj->copy()->startOfMonth()->format('Y-m-d');
        $end = $dateObj->copy()->endOfMonth()->format('Y-m-d');
        $currentMin = (float) str_replace(['.', ','], '', $this->minNominal ?: 0);

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

        $subQuery = DB::table('penjualans')
            ->select('sales_name', 'trans_no', 'kode_pelanggan', DB::raw("SUM(total_grand) as total_per_nota"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name', 'trans_no', 'kode_pelanggan');

        $salesStats = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->mergeBindings($subQuery)
            ->selectRaw("
                sales_name, 
                COUNT(DISTINCT kode_pelanggan) as total_oa, 
                COUNT(DISTINCT CASE WHEN total_per_nota >= {$currentMin} THEN trans_no END) as total_ec
            ")
            ->groupBy('sales_name')
            ->get()->keyBy('sales_name');

        $laporan = [];
        $summary = ['total_oa' => 0, 'total_ec' => 0];

        foreach ($allSales as $sales) {
            $name = $sales->sales_name;
            $stat = $salesStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));

            $oa = $stat ? (int)$stat->total_oa : 0;
            $ec = $stat ? (int)$stat->total_ec : 0;

            $summary['total_oa'] += $oa;
            $summary['total_ec'] += $ec;

            $laporan[] = [
                'kode' => $sales->sales_code ?? '-',
                'nama' => $name,
                'cabang' => $sales->city,
                'real_oa' => $oa,
                'ec' => $ec,
            ];
        }

        // Urutkan berdasarkan Effective Call (EC) tertinggi
        usort($laporan, fn($a, $b) => $b['ec'] <=> $a['ec']);
        
        return ['data' => collect($laporan), 'summary' => $summary];
    }

    public function render()
    {
        $hasil = $this->getDataLaporan();
        
        $perPage = 15;
        $items = $hasil['data'];
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($items), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
            'query' => request()->query()
        ]);

        $optCabang = Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'));

        return view('livewire.laporan.kinerja.produktivitas', [
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
            'data' => $hasil['data'],
            'minNominal' => $this->minNominal
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.laporan.exports.kinerja-produktivitas-pdf', $dataView)->setPaper('a4', 'landscape');
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Produktivitas_Sales_' . $this->bulan . '.pdf');
    }
}