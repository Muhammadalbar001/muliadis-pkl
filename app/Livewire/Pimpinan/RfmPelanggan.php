<?php

namespace App\Livewire\Pimpinan;

use Livewire\Component;
use App\Models\Transaksi\Penjualan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RfmPelanggan extends Component
{
    public $bulan;
    public $tahun;

    public function mount()
    {
        $this->bulan = Carbon::now()->month;
        $this->tahun = Carbon::now()->year;
    }

    public function hitungRFM()
    {
        // 1. Tentukan tanggal evaluasi (Akhir dari bulan yang dipilih)
        $tanggalEvaluasi = Carbon::create($this->tahun, $this->bulan)->endOfMonth();

        // 2. Ambil data penjualan dari AWAL TAHUN sampai BULAN YANG DIPILIH
        // (RFM butuh rentang waktu yang agak panjang agar Frequency-nya akurat)
        $dataPenjualan = Penjualan::selectRaw('
                nama_pelanggan, 
                MAX(tgl_penjualan) as last_order, 
                COUNT(*) as total_orders, 
                SUM(CAST(total_grand AS UNSIGNED)) as total_spent
            ')
            ->whereYear('tgl_penjualan', $this->tahun)
            ->whereMonth('tgl_penjualan', '<=', $this->bulan)
            ->groupBy('nama_pelanggan')
            ->get();

        if ($dataPenjualan->isEmpty()) return [];

        $rfmData = [];

        // 3. Hitung Nilai Mentah (Raw Values)
        foreach ($dataPenjualan as $p) {
            $lastOrderDate = Carbon::parse($p->last_order);
            // Recency: Selisih hari dari transaksi terakhir ke tanggal evaluasi
            $recencyDays = $lastOrderDate->diffInDays($tanggalEvaluasi);
            
            $rfmData[] = [
                'nama' => $p->nama_pelanggan,
                'r_raw' => $recencyDays,
                'f_raw' => $p->total_orders,
                'm_raw' => $p->total_spent,
                'r_score' => 0, 'f_score' => 0, 'm_score' => 0,
            ];
        }

        $totalData = count($rfmData);
        $chunkSize = ceil($totalData / 5); // Dibagi jadi 5 kelompok (Quantile 1-5)

        // 4. SCORING RECENCY (Semakin KECIL hari, semakin BESAR skornya)
        usort($rfmData, fn($a, $b) => $a['r_raw'] <=> $b['r_raw']);
        foreach ($rfmData as $index => &$item) {
            $score = 5 - floor($index / $chunkSize);
            $item['r_score'] = max(1, min(5, $score));
        }

        // 5. SCORING FREQUENCY (Semakin BESAR total order, semakin BESAR skornya)
        usort($rfmData, fn($a, $b) => $b['f_raw'] <=> $a['f_raw']);
        foreach ($rfmData as $index => &$item) {
            $score = 5 - floor($index / $chunkSize);
            $item['f_score'] = max(1, min(5, $score));
        }

        // 6. SCORING MONETARY (Semakin BESAR total belanja, semakin BESAR skornya)
        usort($rfmData, fn($a, $b) => $b['m_raw'] <=> $a['m_raw']);
        foreach ($rfmData as $index => &$item) {
            $score = 5 - floor($index / $chunkSize);
            $item['m_score'] = max(1, min(5, $score));
        }

        // 7. PENENTUAN SEGMENTASI (Berdasarkan Kombinasi R dan F)
        foreach ($rfmData as &$item) {
            $r = $item['r_score'];
            $f = $item['f_score'];
            $m = $item['m_score'];
            $item['rfm_concat'] = $r . $f . $m;

            if ($r >= 4 && $f >= 4) {
                $item['segment'] = 'Champions'; // Pelanggan terbaik
                $item['color'] = 'emerald';
            } elseif ($r >= 3 && $f >= 3) {
                $item['segment'] = 'Loyal Customers'; // Sering beli
                $item['color'] = 'blue';
            } elseif ($r >= 4 && $f <= 2) {
                $item['segment'] = 'New / Promising'; // Baru beli, tapi baru sedikit
                $item['color'] = 'cyan';
            } elseif ($r <= 2 && $f >= 3) {
                $item['segment'] = 'At Risk'; // Dulu sering beli, sekarang menghilang
                $item['color'] = 'orange';
            } elseif ($r <= 2 && $f <= 2) {
                $item['segment'] = 'Lost Customers'; // Sudah lama hilang dan jaranga beli
                $item['color'] = 'rose';
            } else {
                $item['segment'] = 'Regular Customers'; // Rata-rata
                $item['color'] = 'slate';
            }
        }

        // Urutkan berdasarkan Segment terbaik
        usort($rfmData, function($a, $b) {
            $segmentOrder = ['Champions' => 1, 'Loyal Customers' => 2, 'New / Promising' => 3, 'Regular Customers' => 4, 'At Risk' => 5, 'Lost Customers' => 6];
            return $segmentOrder[$a['segment']] <=> $segmentOrder[$b['segment']];
        });

        return $rfmData;
    }

    public function render()
    {
        $hasilRFM = $this->hitungRFM();

        // Hitung Summary untuk Statistik di Atas
        $summary = collect($hasilRFM)->countBy('segment')->toArray();

        return view('livewire.pimpinan.rfm-pelanggan', [
            'hasilRFM' => $hasilRFM,
            'summary' => $summary
        ])->layout('layouts.app');
    }
}