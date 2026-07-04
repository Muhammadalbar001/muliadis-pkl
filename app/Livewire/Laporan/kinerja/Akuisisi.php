<?php

namespace App\Livewire\Laporan\Kinerja;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Akuisisi extends Component
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

        // 1. Ambil semua transaksi pelanggan di bulan berjalan
        $currentMonthCust = DB::table('penjualans')
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->select('sales_name', 'kode_pelanggan', DB::raw('SUM(total_grand) as omzet'))
            ->groupBy('sales_name', 'kode_pelanggan')
            ->get();

        // 2. Ambil daftar pelanggan yang PERNAH berbelanja sebelum bulan ini (Data Historis)
        $pastCustArray = DB::table('penjualans')
            ->where('tgl_penjualan', '<', $start)
            ->distinct()
            ->pluck('kode_pelanggan')
            ->toArray();
            
        // Balikkan array untuk pencarian O(1) yang super cepat
        $pastCustMap = array_flip($pastCustArray);

        // 3. Proses Algoritma Klasifikasi (Baru vs Lama)
        $salesStats = [];
        foreach($currentMonthCust as $c) {
            $s = strtoupper($c->sales_name);
            if(!isset($salesStats[$s])) {
                $salesStats[$s] = ['lama' => 0, 'baru' => 0, 'omzet_baru' => 0];
            }
            
            if(isset($pastCustMap[$c->kode_pelanggan])) {
                // Toko Lama (Sudah ada di riwayat)
                $salesStats[$s]['lama']++;
            } else {
                // Toko Baru (Tidak ditemukan di riwayat)
                $salesStats[$s]['baru']++;
                $salesStats[$s]['omzet_baru'] += $c->omzet;
            }
        }

        $laporan = [];
        foreach ($allSales as $sales) {
            $sName = strtoupper($sales->sales_name);
            $stat = $salesStats[$sName] ?? ['lama' => 0, 'baru' => 0, 'omzet_baru' => 0];
            
            $totalToko = $stat['lama'] + $stat['baru'];
            $rasio = $totalToko > 0 ? ($stat['baru'] / $totalToko) * 100 : 0;

            $laporan[] = [
                'kode' => $sales->sales_code,
                'nama' => $sales->sales_name,
                'lama' => $stat['lama'],
                'baru' => $stat['baru'],
                'total_toko' => $totalToko,
                'omzet_baru' => $stat['omzet_baru'],
                'rasio' => $rasio
            ];
        }

        // Urutkan berdasarkan toko baru terbanyak
        usort($laporan, fn($a, $b) => $b['baru'] <=> $a['baru']);
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.laporan.exports.kinerja-akuisisi-pdf', $dataView)->setPaper('a4', 'landscape');
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Akuisisi_Toko_Baru_' . $this->bulan . '.pdf');
    }

    public function render()
    {
        $items = $this->getDataLaporan();
        $perPage = 15;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator($items->slice(($currentPage - 1) * $perPage, $perPage)->all(), count($items), $perPage, $currentPage, ['path' => request()->url()]);

        return view('livewire.laporan.kinerja.akuisisi', ['laporan' => $paginated])->layout('layouts.app');
    }
}