<?php

namespace App\Livewire\Laporan\Kinerja;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Kualitas extends Component
{
    use WithPagination;

    public $bulan;
    public $search = '';

    public function mount() { $this->bulan = date('Y-m'); }
    public function updatedSearch() { $this->resetPage(); }
    public function updatedBulan() { $this->resetPage(); }

    public function getDataLaporan()
    {
        $bulanPilih = $this->bulan ?: date('Y-m');
        $start = Carbon::parse($bulanPilih . '-01')->startOfMonth()->format('Y-m-d');
        $end = Carbon::parse($bulanPilih . '-01')->endOfMonth()->format('Y-m-d');

        $salesQuery = Sales::whereIn('status', ['Active', 'aktif', 'Aktif']);
        if ($this->search) $salesQuery->where('sales_name', 'like', '%' . $this->search . '%');
        $allSales = $salesQuery->orderBy('sales_name')->get();

        // Omzet
        $penjualan = DB::table('penjualans')
            ->select('sales_name', DB::raw("SUM(total_grand) as gross_sales"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name')->get()->keyBy('sales_name');

        // Retur
        $retur = DB::table('returs')
            ->select('sales_name', DB::raw("SUM(total_grand) as total_retur, COUNT(*) as qty_retur"))
            ->whereBetween('tgl_retur', [$start, $end])
            ->groupBy('sales_name')->get()->keyBy('sales_name');

        $laporan = [];
        foreach ($allSales as $sales) {
            $p = $penjualan->get($sales->sales_name);
            $r = $retur->get($sales->sales_name);

            $gross = $p ? (float)$p->gross_sales : 0;
            $retVal = $r ? (float)$r->total_retur : 0;

            $laporan[] = [
                'kode' => $sales->sales_code,
                'nama' => $sales->sales_name,
                'gross' => $gross,
                'retur' => $retVal,
                'qty_retur' => $r ? $r->qty_retur : 0,
                'net' => $gross - $retVal,
                'rasio' => $gross > 0 ? ($retVal / $gross) * 100 : 0
            ];
        }

        usort($laporan, fn($a, $b) => $b['retur'] <=> $a['retur']);
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.laporan.exports.kinerja-kualitas-pdf', $dataView)->setPaper('a4', 'landscape');
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Kualitas_Penjualan_' . $this->bulan . '.pdf');
    }

    public function render()
    {
        $items = $this->getDataLaporan();
        $perPage = 15;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator($items->slice(($currentPage - 1) * $perPage, $perPage)->all(), count($items), $perPage, $currentPage, ['path' => request()->url()]);

        return view('livewire.laporan.kinerja.kualitas', ['laporan' => $paginated])->layout('layouts.app');
    }
}