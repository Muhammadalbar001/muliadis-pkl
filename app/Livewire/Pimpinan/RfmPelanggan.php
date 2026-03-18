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

    public $isModalOpen = false;
    public $selectedDetail = null;

    public function mount()
    {
        $this->bulan = Carbon::now()->month;
        $this->tahun = Carbon::now()->year;
    }

    public function hitungRFM()
    {
        $tanggalEvaluasi = Carbon::create($this->tahun, $this->bulan)->endOfMonth();

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

        foreach ($dataPenjualan as $p) {
            $lastOrderDate = Carbon::parse($p->last_order);
            $recencyDays = (int) $lastOrderDate->diffInDays($tanggalEvaluasi);
            
            $rfmData[] = [
                'nama' => $p->nama_pelanggan,
                'r_raw' => $recencyDays,
                'f_raw' => $p->total_orders,
                'm_raw' => $p->total_spent,
                'm_fmt' => number_format($p->total_spent, 0, ',', '.'),
                'r_score' => 0, 'f_score' => 0, 'm_score' => 0,
            ];
        }

        $totalData = count($rfmData);
        $chunkSize = ceil($totalData / 5); 

        // R (Keterbaruan)
        usort($rfmData, fn($a, $b) => $a['r_raw'] <=> $b['r_raw']);
        foreach ($rfmData as $index => &$item) {
            $score = 5 - floor($index / $chunkSize);
            $item['r_score'] = max(1, min(5, (int)$score));
            $item['r_rank_info'] = "Kelompok " . (5 - $item['r_score'] + 1) . " (Terbaru)";
        }

        // F (Frekuensi)
        usort($rfmData, fn($a, $b) => $b['f_raw'] <=> $a['f_raw']);
        foreach ($rfmData as $index => &$item) {
            $score = 5 - floor($index / $chunkSize);
            $item['f_score'] = max(1, min(5, (int)$score));
        }

        // M (Moneter)
        usort($rfmData, fn($a, $b) => $b['m_raw'] <=> $a['m_raw']);
        foreach ($rfmData as $index => &$item) {
            $score = 5 - floor($index / $chunkSize);
            $item['m_score'] = max(1, min(5, (int)$score));
        }

        // Segmentasi
        foreach ($rfmData as &$item) {
            $r = $item['r_score'];
            $f = $item['f_score'];
            $item['rfm_concat'] = $r . $f . $item['m_score'];

            if ($r >= 4 && $f >= 4) {
                $item['segment'] = 'Pelanggan Utama'; $item['color'] = 'emerald';
            } elseif ($r >= 3 && $f >= 3) {
                $item['segment'] = 'Pelanggan Setia'; $item['color'] = 'blue';
            } elseif ($r >= 4 && $f <= 2) {
                $item['segment'] = 'Pelanggan Potensial'; $item['color'] = 'cyan';
            } elseif ($r <= 2 && $f >= 3) {
                $item['segment'] = 'Berisiko Pindah'; $item['color'] = 'orange';
            } elseif ($r <= 2 && $f <= 2) {
                $item['segment'] = 'Pelanggan Pasif'; $item['color'] = 'rose';
            } else {
                $item['segment'] = 'Pelanggan Reguler'; $item['color'] = 'slate';
            }
        }

        usort($rfmData, function($a, $b) {
            $order = ['Pelanggan Utama' => 1, 'Pelanggan Setia' => 2, 'Pelanggan Potensial' => 3, 'Pelanggan Reguler' => 4, 'Berisiko Pindah' => 5, 'Pelanggan Pasif' => 6];
            return $order[$a['segment']] <=> $order[$b['segment']];
        });

        return $rfmData;
    }

    public function openDetail($nama)
    {
        $hasil = $this->hitungRFM();
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
        $hasilRFM = $this->hitungRFM();
        $summary = collect($hasilRFM)->countBy('segment')->toArray();

        return view('livewire.pimpinan.rfm-pelanggan', [
            'hasilRFM' => $hasilRFM,
            'summary' => $summary
        ])->layout('layouts.app');
    }
}