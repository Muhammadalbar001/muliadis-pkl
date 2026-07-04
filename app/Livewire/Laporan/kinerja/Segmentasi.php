<?php

namespace App\Livewire\Laporan\Kinerja;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Segmentasi extends Component
{
    use WithPagination;

    public $bulan;
    public $search = '';
    public $filterCabang = '';

    public function mount() { $this->bulan = date('Y-m'); }
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedBulan() { $this->resetPage(); }

    public function resetFilter() {
        $this->reset(['filterCabang', 'search']);
        $this->bulan = date('Y-m');
        $this->resetPage();
    }

    public function getDataLaporan()
    {
        $bulanPilih = $this->bulan ?: date('Y-m');
        $dateObj = Carbon::parse($bulanPilih . '-01');
        $start = $dateObj->copy()->startOfMonth()->format('Y-m-d');
        $end = $dateObj->copy()->endOfMonth()->format('Y-m-d');

        $salesQuery = Sales::whereIn('status', ['Active', 'aktif', 'Aktif']);
        if ($this->search) {
            $salesQuery->where('sales_name', 'like', '%' . $this->search . '%');
        }
        if ($this->filterCabang) {
            $salesQuery->where('city', $this->filterCabang);
        }
        $allSales = $salesQuery->orderBy('sales_name')->get();

        // Subquery: Menghitung status VIP/Pasif secara dinamis per pelanggan (On The Fly FCM Logic)
        // Jika pelanggan beli > 3 kali dan omzet > 1jt (VIP)
        // Jika pelanggan beli 1 kali (Pasif)
        // Sisanya Menengah
        $sub = DB::table('penjualans')
            ->select('sales_name', 'kode_pelanggan', DB::raw("COUNT(DISTINCT trans_no) as freq, SUM(total_grand) as m_val"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name', 'kode_pelanggan');

        $klaster = DB::table(DB::raw("({$sub->toSql()}) as sub"))
            ->mergeBindings($sub)
            ->selectRaw("
                sales_name,
                SUM(CASE WHEN freq >= 3 AND m_val >= 1000000 THEN 1 ELSE 0 END) as vip,
                SUM(CASE WHEN freq = 1 THEN 1 ELSE 0 END) as pasif,
                SUM(CASE WHEN freq < 3 AND freq > 1 THEN 1 ELSE 0 END) as menengah
            ")
            ->groupBy('sales_name')
            ->get()->keyBy('sales_name');

        $laporan = [];
        foreach ($allSales as $sales) {
            $k = $klaster->get($sales->sales_name);
            $laporan[] = [
                'kode' => $sales->sales_code,
                'nama' => $sales->sales_name,
                'vip' => $k ? $k->vip : 0,
                'menengah' => $k ? $k->menengah : 0,
                'pasif' => $k ? $k->pasif : 0,
                'total' => $k ? ($k->vip + $k->menengah + $k->pasif) : 0,
            ];
        }

        usort($laporan, fn($a, $b) => $b['vip'] <=> $a['vip']);
        return collect($laporan);
    }

    public function exportPdf()
    {
        $laporan = $this->getDataLaporan();
        $dateObj = \Carbon\Carbon::parse($this->bulan . '-01');

        $dataView = [
            'periode' => $dateObj->translatedFormat('F Y'),
            'cetak_oleh' => auth()->user()->name ?? 'Pimpinan Eksekutif',
            'tgl_cetak' => now()->translatedFormat('d F Y H:i'),
            'data' => $laporan
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.laporan.exports.kinerja-segmentasi-pdf', $dataView)->setPaper('a4', 'landscape');
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Kinerja_Segmentasi_' . $this->bulan . '.pdf');
    }

    public function render()
    {
        $items = $this->getDataLaporan();
        $perPage = 15;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($items), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
            'query' => request()->query()
        ]);

        return view('livewire.laporan.kinerja.segmentasi', [
            'laporan' => $paginated,
            'optCabang' => Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'))
        ])->layout('layouts.app');
    }

    
}