<?php

namespace App\Livewire\Pimpinan;

use Livewire\Component;
use App\Models\Master\Sales;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 

class SpkSales extends Component
{
    public $bulan;
    public $tahun;
    
    // Variabel untuk kendali Modal Pop-up via Livewire
    public $isModalOpen = false;
    public $selectedDetail = null;
    
    public function mount()
    {
        $this->bulan = Carbon::now()->month;
        $this->tahun = Carbon::now()->year;
    }

    public function hitungSAW()
    {
        $salesList = Sales::all();
        $dataKinerja = [];

        // 1. Kumpulkan Nilai Mentah
        foreach ($salesList as $sales) {
            $omzet = Penjualan::where('sales_name', $sales->sales_name)
                        ->whereMonth('tgl_penjualan', $this->bulan)
                        ->whereYear('tgl_penjualan', $this->tahun)
                        ->sum(DB::raw('CAST(total_grand AS UNSIGNED)'));

            $transaksi = Penjualan::where('sales_name', $sales->sales_name)
                        ->whereMonth('tgl_penjualan', $this->bulan)
                        ->whereYear('tgl_penjualan', $this->tahun)
                        ->count();

            $retur = Retur::where('sales_name', $sales->sales_name)
                        ->whereMonth('tgl_retur', $this->bulan)
                        ->whereYear('tgl_retur', $this->tahun)
                        ->sum(DB::raw('CAST(total_grand AS UNSIGNED)'));

            $piutang = AccountReceivable::where('sales_name', $sales->sales_name)
                        ->where('status', '!=', 'Lunas')
                        ->sum(DB::raw('CAST(nilai AS UNSIGNED)'));

            if ($omzet > 0 || $retur > 0 || $piutang > 0) {
                $dataKinerja[] = [
                    'nama' => $sales->sales_name,
                    'cabang' => $sales->cabang,
                    'k1_omzet' => $omzet,
                    'k2_transaksi' => $transaksi,
                    'k3_retur' => $retur,
                    'k4_piutang' => $piutang,
                ];
            }
        }

        if (empty($dataKinerja)) return [];

        // 2. Cari Nilai Max (Keuntungan) dan Min (Biaya)
        $maxK1 = max(array_column($dataKinerja, 'k1_omzet'));
        $maxK2 = max(array_column($dataKinerja, 'k2_transaksi'));

        // PERBAIKAN BUG ValueError: Cek apakah array memiliki elemen sebelum dikenakan fungsi min()
        $retur_filtered = array_filter(array_column($dataKinerja, 'k3_retur'));
        $minK3 = count($retur_filtered) > 0 ? min($retur_filtered) : 1; 

        $piutang_filtered = array_filter(array_column($dataKinerja, 'k4_piutang'));
        $minK4 = count($piutang_filtered) > 0 ? min($piutang_filtered) : 1;

        // Bobot Kriteria
        $w1 = 0.40; $w2 = 0.20; $w3 = 0.20; $w4 = 0.20;

        $hasilAkhir = [];

        // 3. Normalisasi
        foreach ($dataKinerja as $row) {
            $n1 = $maxK1 > 0 ? ($row['k1_omzet'] / $maxK1) : 0;
            $n2 = $maxK2 > 0 ? ($row['k2_transaksi'] / $maxK2) : 0;
            $n3 = $row['k3_retur'] > 0 ? ($minK3 / $row['k3_retur']) : 1;
            $n4 = $row['k4_piutang'] > 0 ? ($minK4 / $row['k4_piutang']) : 1;

            $nilaiSAW = ($n1 * $w1) + ($n2 * $w2) + ($n3 * $w3) + ($n4 * $w4);

            $hasilAkhir[] = [
                'nama' => $row['nama'],
                'cabang' => $row['cabang'],
                'omzet' => $row['k1_omzet'],
                'retur' => $row['k3_retur'],
                'omzet_fmt' => number_format($row['k1_omzet'], 0, ',', '.'),
                'trans_fmt' => number_format($row['k2_transaksi'], 0, ',', '.'),
                'retur_fmt' => number_format($row['k3_retur'], 0, ',', '.'),
                'piutang_fmt' => number_format($row['k4_piutang'], 0, ',', '.'),
                'max_k1_fmt' => number_format($maxK1, 0, ',', '.'),
                'max_k2_fmt' => number_format($maxK2, 0, ',', '.'),
                'min_k3_fmt' => number_format($minK3, 0, ',', '.'),
                'min_k4_fmt' => number_format($minK4, 0, ',', '.'),
                'n1' => round($n1, 3), 'n2' => round($n2, 3),
                'n3' => round($n3, 3), 'n4' => round($n4, 3),
                'skor_akhir' => round($nilaiSAW, 3),
            ];
        }

        usort($hasilAkhir, fn($a, $b) => $b['skor_akhir'] <=> $a['skor_akhir']);
        return $hasilAkhir;
    }

    // FUNGSI UNTUK MEMBUKA MODAL
    public function openDetail($nama)
    {
        $hasil = $this->hitungSAW();
        // Cari data spesifik dari salesman yang diklik
        $this->selectedDetail = collect($hasil)->firstWhere('nama', $nama);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedDetail = null;
    }

    public function render()
    {
        return view('livewire.pimpinan.spk-sales', [
            'hasilSPK' => $this->hitungSAW()
        ])->layout('layouts.app');
    }
    public function exportPdf()
    {
        $hasil = $this->hitungSAW();
        
        // Terjemahkan angka bulan ke nama bulan (misal: 3 -> Maret)
        $bulanNama = Carbon::create()->month($this->bulan)->translatedFormat('F');

        $data = [
            'hasil' => $hasil,
            'bulanNama' => $bulanNama,
            'tahun' => $this->tahun,
            'tanggal_cetak' => Carbon::now()->translatedFormat('d F Y H:i'),
        ];

        // Load view PDF
        $pdf = Pdf::loadView('livewire.pimpinan.exports.spk-sales-pdf', $data);
        
        // Atur ukuran kertas menjadi A4 Landscape karena kolomnya banyak
        $pdf->setPaper('A4', 'landscape');

        // Download otomatis
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Laporan_Analitik_SPK_Sales_' . $bulanNama . '_' . $this->tahun . '.pdf');
    }
}