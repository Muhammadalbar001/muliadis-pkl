<?php

namespace App\Livewire\Pimpinan;

use Livewire\Component;
use App\Models\Master\Sales;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use Carbon\Carbon;

class SpkSales extends Component
{
    public $bulan;
    public $tahun;
    
    public function mount()
    {
        $this->bulan = Carbon::now()->month;
        $this->tahun = Carbon::now()->year;
    }

    public function hitungSAW()
    {
        // 1. Ambil semua Sales
        $salesList = Sales::all();
        $dataKinerja = [];

        // 2. Kumpulkan Nilai Mentah (Matriks Keputusan)
        foreach ($salesList as $sales) {
            // C1: Total Omzet (Benefit)
            $omzet = Penjualan::where('sales_name', $sales->sales_name)
                        ->whereMonth('tgl_penjualan', $this->bulan)
                        ->whereYear('tgl_penjualan', $this->tahun)
                        ->sum(\DB::raw('CAST(total_grand AS UNSIGNED)'));

            // C2: Total Transaksi / Nota (Benefit)
            $transaksi = Penjualan::where('sales_name', $sales->sales_name)
                        ->whereMonth('tgl_penjualan', $this->bulan)
                        ->whereYear('tgl_penjualan', $this->tahun)
                        ->count();

            // C3: Total Retur (Cost - Semakin kecil semakin baik)
            $retur = Retur::where('sales_name', $sales->sales_name)
                        ->whereMonth('tgl_retur', $this->bulan)
                        ->whereYear('tgl_retur', $this->tahun)
                        ->sum(\DB::raw('CAST(total_grand AS UNSIGNED)'));

            // C4: Piutang Belum Lunas (Cost - Semakin kecil semakin baik)
            // PERBAIKAN DI SINI: Kolomnya adalah 'sales_name', bukan 'sales'
            $piutang = AccountReceivable::where('sales_name', $sales->sales_name)
                        ->where('status', '!=', 'Lunas')
                        ->sum(\DB::raw('CAST(nilai AS UNSIGNED)'));

            // Hanya masukkan sales yang punya aktivitas di bulan ini (omzet/retur/piutang)
            if ($omzet > 0 || $retur > 0 || $piutang > 0) {
                $dataKinerja[] = [
                    'nama' => $sales->sales_name,
                    'cabang' => $sales->cabang,
                    'c1_omzet' => $omzet,
                    'c2_transaksi' => $transaksi,
                    'c3_retur' => $retur,
                    'c4_piutang' => $piutang,
                ];
            }
        }

        // Jika tidak ada data di bulan tersebut, kembalikan array kosong
        if (empty($dataKinerja)) return [];

        // 3. Cari Nilai Max (untuk Benefit) dan Min (untuk Cost)
        $maxC1 = max(array_column($dataKinerja, 'c1_omzet'));
        $maxC2 = max(array_column($dataKinerja, 'c2_transaksi'));
        
        // Atasi nilai 0 pada array_column untuk Cost agar tidak division by zero.
        // Jika nilai terendah adalah 0, kita beri nilai 1 agar rumus pembagian SAW tidak Error (Undefined Division).
        $minC3 = min(array_filter(array_column($dataKinerja, 'c3_retur'))) ?: 1; 
        $minC4 = min(array_filter(array_column($dataKinerja, 'c4_piutang'))) ?: 1;

        // Bobot Kriteria (Total harus 1 atau 100%)
        $w1 = 0.40; // Omzet (40%)
        $w2 = 0.20; // Frekuensi Nota (20%)
        $w3 = 0.20; // Retur (20%)
        $w4 = 0.20; // Piutang Macet (20%)

        $hasilAkhir = [];

        // 4. Normalisasi Matriks & Hitung Nilai Akhir (Preferensi)
        foreach ($dataKinerja as $row) {
            // Normalisasi Benefit (Nilai / Max)
            $n1 = $maxC1 > 0 ? ($row['c1_omzet'] / $maxC1) : 0;
            $n2 = $maxC2 > 0 ? ($row['c2_transaksi'] / $maxC2) : 0;
            
            // Normalisasi Cost (Min / Nilai)
            // Jika Cost (Retur/Piutang) = 0, artinya kinerjanya SEMPURNA, kita beri nilai Max yaitu 1.
            $n3 = $row['c3_retur'] > 0 ? ($minC3 / $row['c3_retur']) : 1;
            $n4 = $row['c4_piutang'] > 0 ? ($minC4 / $row['c4_piutang']) : 1;

            // Hitung Vektor V (Nilai Akhir Total SAW)
            $nilaiSAW = ($n1 * $w1) + ($n2 * $w2) + ($n3 * $w3) + ($n4 * $w4);

            $hasilAkhir[] = [
                'nama' => $row['nama'],
                'cabang' => $row['cabang'],
                'omzet' => $row['c1_omzet'],
                'retur' => $row['c3_retur'],
                'n1' => round($n1, 2),
                'n2' => round($n2, 2),
                'n3' => round($n3, 2),
                'n4' => round($n4, 2),
                'skor_akhir' => round($nilaiSAW, 3),
            ];
        }

        // 5. Urutkan Ranking (dari skor tertinggi ke terendah)
        usort($hasilAkhir, function($a, $b) {
            return $b['skor_akhir'] <=> $a['skor_akhir'];
        });

        return $hasilAkhir;
    }

    public function render()
    {
        $hasilSPK = $this->hitungSAW();

        return view('livewire.pimpinan.spk-sales', [
            'hasilSPK' => $hasilSPK
        ])->layout('layouts.app');
    }
}