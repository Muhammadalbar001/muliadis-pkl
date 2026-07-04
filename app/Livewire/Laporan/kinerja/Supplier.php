<?php

namespace App\Livewire\Laporan\Kinerja;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use App\Models\Transaksi\Penjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Supplier extends Component
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
        $start = $dateObj->copy()->startOfMonth()->format('Y-m-d');
        $end = $dateObj->copy()->endOfMonth()->format('Y-m-d');

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

        $topSuppliers = Penjualan::select('supplier', DB::raw("SUM(total_grand) as val"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->whereNotNull('supplier')
            ->groupBy('supplier')
            ->orderByDesc('val')
            ->limit(10) // Dibatasi 10 agar tabel tidak tumpah
            ->pluck('supplier');

        $rawPivot = Penjualan::selectRaw("sales_name, supplier, SUM(total_grand) as total")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->whereIn('supplier', $topSuppliers)
            ->groupBy('sales_name', 'supplier')
            ->get();

        $matrixSupplier = [];
        foreach ($rawPivot as $p) { $matrixSupplier[$p->sales_name][$p->supplier] = $p->total; }

        $laporan = [];
        foreach ($allSales as $sales) {
            $name = $sales->sales_name;
            $countSupplied = isset($matrixSupplier[$name]) ? count($matrixSupplier[$name]) : 0;
            $sumSupplied = isset($matrixSupplier[$name]) ? array_sum($matrixSupplier[$name]) : 0;

            $laporan[] = [
                'kode' => $sales->sales_code ?? '-',
                'nama' => $name,
                'jml_supplier' => $countSupplied,
                'total_supplier_val' => $sumSupplied,
            ];
        }

        // Urutkan berdasarkan nominal total dari brand-brand utama
        usort($laporan, fn($a, $b) => $b['total_supplier_val'] <=> $a['total_supplier_val']);
        
        return ['data' => collect($laporan), 'topSuppliers' => $topSuppliers, 'matrix' => $matrixSupplier];
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

        return view('livewire.laporan.kinerja.supplier', [
            'laporan' => $paginated,
            'topSuppliers' => $hasil['topSuppliers'],
            'matrixSupplier' => $hasil['matrix'],
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
            'topSuppliers' => $hasil['topSuppliers'],
            'matrixSupplier' => $hasil['matrix']
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.laporan.exports.kinerja-supplier-pdf', $dataView)->setPaper('a4', 'landscape');
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Penjualan_Supplier_' . $this->bulan . '.pdf');
    }
}