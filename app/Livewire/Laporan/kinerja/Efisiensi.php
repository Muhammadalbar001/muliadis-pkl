<?php

namespace App\Livewire\Laporan\Kinerja;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Efisiensi extends Component
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

        // Mengambil Total Tagihan yang terbentuk bulan ini (AR)
        $ar = DB::table('account_receivables')
            ->select('sales_name', DB::raw("SUM(nilai) as tagihan"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name')->get()->keyBy('sales_name');

        // Mengambil Total Pelunasan (Collection) yang masuk bulan ini
        $collection = DB::table('collections')
            ->select('sales_name', DB::raw("SUM(receive_amount) as pelunasan"))
            ->whereBetween('tanggal', [$start, $end])
            ->groupBy('sales_name')->get()->keyBy('sales_name');

        $laporan = [];
        foreach ($allSales as $sales) {
            $a = $ar->get($sales->sales_name);
            $c = $collection->get($sales->sales_name);

            $tagihan = $a ? (float)$a->tagihan : 0;
            $pelunasan = $c ? (float)$c->pelunasan : 0;
            
            // Mencegah error pembagian dengan nol
            $rasio = $tagihan > 0 ? ($pelunasan / $tagihan) * 100 : ($pelunasan > 0 ? 100 : 0);

            $laporan[] = [
                'kode' => $sales->sales_code,
                'nama' => $sales->sales_name,
                'tagihan' => $tagihan,
                'pelunasan' => $pelunasan,
                'rasio' => $rasio
            ];
        }

        // Urutkan berdasarkan Rasio Efisiensi terbaik
        usort($laporan, fn($a, $b) => $b['rasio'] <=> $a['rasio']);
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.laporan.exports.kinerja-efisiensi-pdf', $dataView)->setPaper('a4', 'landscape');
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Efisiensi_Penagihan_' . $this->bulan . '.pdf');
    }

    public function render()
    {
        $items = $this->getDataLaporan();
        $perPage = 15;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator($items->slice(($currentPage - 1) * $perPage, $perPage)->all(), count($items), $perPage, $currentPage, ['path' => request()->url()]);

        return view('livewire.laporan.kinerja.efisiensi', ['laporan' => $paginated])->layout('layouts.app');
    }
}