<?php

namespace App\Livewire\Laporan\Kinerja;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use App\Models\Keuangan\AccountReceivable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Ranking extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCabang = '';

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->reset(['filterCabang', 'search']);
        $this->resetPage();
    }

    public function getDataLaporan()
    {
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

        $arStats = AccountReceivable::selectRaw("sales_name, 
            SUM(nilai) as total_ar, 
            SUM(CASE WHEN umur_piutang > 30 THEN nilai ELSE 0 END) as ar_macet")
            ->where('status', '!=', 'Lunas')
            ->groupBy('sales_name')->get()->keyBy('sales_name');

        $laporan = [];
        $summary = ['total_ar' => 0, 'total_macet' => 0];

        foreach ($allSales as $sales) {
            $ar = $arStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($sales->sales_name));

            $arTotal = $ar ? (float)$ar->total_ar : 0;
            $arMacet = $ar ? (float)$ar->ar_macet : 0;

            $summary['total_ar'] += $arTotal;
            $summary['total_macet'] += $arMacet;

            $laporan[] = [
                'kode' => $sales->sales_code ?? '-',
                'nama' => $sales->sales_name,
                'cabang' => $sales->city,
                'ar_total' => $arTotal,
                'ar_macet' => $arMacet,
                'ar_persen_macet' => $arTotal > 0 ? ($arMacet / $arTotal) * 100 : 0,
            ];
        }

        usort($laporan, fn($a, $b) => $b['ar_total'] <=> $a['ar_total']);
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

        return view('livewire.laporan.kinerja.ranking', [
            'laporan' => $paginated,
            'summary' => $hasil['summary'],
            'optCabang' => $optCabang
        ])->layout('layouts.app');
    }

    public function exportPdf()
    {
        $hasil = $this->getDataLaporan();
        
        // Gunakan bulan dari filter jika ada, atau bulan saat ini
        $bulanPilih = $this->bulan ?? date('Y-m'); 
        $dateObj = \Carbon\Carbon::parse($bulanPilih . '-01');

        $dataView = [
            'periode' => $dateObj->translatedFormat('F Y'),
            'cetak_oleh' => auth()->user()->name ?? 'Administrator',
            'tgl_cetak' => now()->format('d/m/Y H:i'),
            'data' => $hasil['data']
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.laporan.exports.kinerja-ar-pdf', $dataView)->setPaper('a4', 'landscape');
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Monitoring_Kredit_' . $bulanPilih . '.pdf');
    }
}