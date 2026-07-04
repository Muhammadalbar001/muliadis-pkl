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
    public $search = ''; 
    
    public $isModalOpen = false;
    public $selectedDetail = null;

    // Properti baru untuk menyimpan hasil metrik AI
    public $ahpWeights = [];
    public $ahpCR = 0;
    public $nilaiRMSE = 0;
    
    public function mount()
    {
        $this->bulan = Carbon::now()->month;
        $this->tahun = Carbon::now()->year;
    }

    /**
     * 1. FUNGSI ANALYTICAL HIERARCHY PROCESS (AHP)
     * Menghitung bobot kriteria secara dinamis menggunakan Matriks Perbandingan Berpasangan
     */
    private function hitungAHP()
    {
        // Kriteria: C1(Omzet), C2(Nota/Transaksi), C3(Retur), C4(Piutang)
        // Skala Saaty (Contoh Preferensi Pakar/Pimpinan):
        // C1 sedikit lebih penting dari C3 dan C4 (Nilai 2)
        // C1 cukup lebih penting dari C2 (Nilai 3)
        $matriks = [
            [1,   3,   2,   2  ], // C1: Omzet
            [1/3, 1,   1/2, 1/2], // C2: Nota/Transaksi
            [1/2, 2,   1,   1  ], // C3: Retur
            [1/2, 2,   1,   1  ]  // C4: Piutang
        ];

        $jumlahKolom = [0, 0, 0, 0];
        
        // Hitung jumlah setiap kolom
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 4; $j++) {
                $jumlahKolom[$j] += $matriks[$i][$j];
            }
        }

        // Normalisasi Matriks & Cari Eigenvector (Bobot Prioritas)
        $bobot = [0, 0, 0, 0];
        for ($i = 0; $i < 4; $i++) {
            $jumlahBaris = 0;
            for ($j = 0; $j < 4; $j++) {
                $matriksNormalisasi = $matriks[$i][$j] / $jumlahKolom[$j];
                $jumlahBaris += $matriksNormalisasi;
            }
            $bobot[$i] = $jumlahBaris / 4; // Rata-rata baris = Eigenvector
        }

        // Hitung Consistency Ratio (CR)
        $lambdaMax = 0;
        for ($i = 0; $i < 4; $i++) {
            $lambdaMax += $bobot[$i] * $jumlahKolom[$i];
        }
        
        $ci = ($lambdaMax - 4) / (4 - 1);
        $ri = 0.90; // Nilai Random Index untuk n=4
        $cr = $ci / $ri;

        return [
            'bobot' => $bobot,
            'cr' => $cr
        ];
    }

    /**
     * 2. FUNGSI SIMPLE ADDITIVE WEIGHTING (SAW) & RMSE
     */
    public function hitungSAW()
    {
        // Eksekusi algoritma AHP terlebih dahulu untuk mendapatkan bobot
        $hasilAHP = $this->hitungAHP();
        $this->ahpWeights = $hasilAHP['bobot'];
        $this->ahpCR = $hasilAHP['cr'];

        $salesList = Sales::when($this->search, function($query) {
            $query->where('sales_name', 'like', '%' . $this->search . '%')
                  ->orWhere('sales_code', 'like', '%' . $this->search . '%');
        })->get();

        $dataKinerja = [];

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

        $maxK1 = max(array_column($dataKinerja, 'k1_omzet'));
        $maxK2 = max(array_column($dataKinerja, 'k2_transaksi'));

        $retur_filtered = array_filter(array_column($dataKinerja, 'k3_retur'));
        $minK3 = count($retur_filtered) > 0 ? min($retur_filtered) : 1; 

        $piutang_filtered = array_filter(array_column($dataKinerja, 'k4_piutang'));
        $minK4 = count($piutang_filtered) > 0 ? min($piutang_filtered) : 1;

        // Ambil bobot dinamis dari AHP
        $w1 = $this->ahpWeights[0]; 
        $w2 = $this->ahpWeights[1]; 
        $w3 = $this->ahpWeights[2]; 
        $w4 = $this->ahpWeights[3];

        $hasilAkhir = [];
        $totalErrorKuadrat = 0;

        foreach ($dataKinerja as $row) {
            // Normalisasi SAW
            $n1 = $maxK1 > 0 ? ($row['k1_omzet'] / $maxK1) : 0;
            $n2 = $maxK2 > 0 ? ($row['k2_transaksi'] / $maxK2) : 0;
            $n3 = $row['k3_retur'] > 0 ? ($minK3 / $row['k3_retur']) : 1;
            $n4 = $row['k4_piutang'] > 0 ? ($minK4 / $row['k4_piutang']) : 1;

            // Perhitungan Akhir SAW
            $nilaiSAW = ($n1 * $w1) + ($n2 * $w2) + ($n3 * $w3) + ($n4 * $w4);

            // ==========================================
            // KALKULASI RMSE (Sesuai Permintaan Panelis)
            // ==========================================
            // Asumsi sistem lama: Kinerja hanya dilihat dari Omzet saja (Bobot Omzet = 100%)
            $skorManualLama = $n1 * 1.0; 
            
            // Hitung Kuadrat Selisih (Error^2)
            $errorKuadrat = pow($nilaiSAW - $skorManualLama, 2);
            $totalErrorKuadrat += $errorKuadrat;

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
                'skor_manual' => round($skorManualLama, 3) // Untuk referensi tabel jika diperlukan
            ];
        }

        // Finalisasi nilai RMSE
        $jumlahData = count($hasilAkhir);
        $this->nilaiRMSE = $jumlahData > 0 ? sqrt($totalErrorKuadrat / $jumlahData) : 0;

        // Urutkan berdasarkan Skor Tertinggi (Ranking 1 di atas)
        usort($hasilAkhir, fn($a, $b) => $b['skor_akhir'] <=> $a['skor_akhir']);
        
        return $hasilAkhir;
    }

    public function openDetail($nama)
    {
        $hasil = $this->hitungSAW();
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
        $bulanNama = Carbon::create()->month($this->bulan)->translatedFormat('F');

        $data = [
            'hasil' => $hasil,
            'bulanNama' => $bulanNama,
            'tahun' => $this->tahun,
            'tanggal_cetak' => Carbon::now()->translatedFormat('d F Y H:i'),
            'ahpCR' => $this->ahpCR,
            'nilaiRMSE' => $this->nilaiRMSE
        ];

        $pdf = Pdf::loadView('livewire.pimpinan.exports.spk-sales-pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Laporan_Analitik_SPK_Sales_' . $bulanNama . '_' . $this->tahun . '.pdf');
    }
}