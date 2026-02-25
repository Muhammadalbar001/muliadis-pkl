<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Keuangan\Collection;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $hariIni = Carbon::today();

        // 1. Mengambil jumlah data yang tanggal transaksinya hari ini
        $jualHariIni = Penjualan::whereDate('tgl_penjualan', $hariIni)->count();
        $returHariIni = Retur::whereDate('tgl_retur', $hariIni)->count();
        $piutangHariIni = AccountReceivable::whereDate('tgl_penjualan', $hariIni)->count(); 
        $pelunasanHariIni = Collection::whereDate('tanggal', $hariIni)->count();

        // 2. Mengambil 5 aktivitas terbaru berdasarkan waktu pembuatan
        $recentPenjualan = Penjualan::orderBy('created_at', 'desc')->take(3)->get();
        $recentRetur = Retur::orderBy('created_at', 'desc')->take(2)->get();

        // 3. FITUR BARU: Checklist Upload Cabang Hari Ini (Berdasarkan Data Penjualan)
        $cabangList = ['Banjarmasin', 'Palangkaraya', 'Barabai', 'Batulicin', 'Pharma'];
        $statusUploadCabang = [];

        foreach ($cabangList as $cabang) {
            // Cek apakah ada data penjualan untuk cabang ini pada hari ini
            $isUploaded = Penjualan::where('cabang', $cabang)
                                   ->whereDate('tgl_penjualan', $hariIni)
                                   ->exists();
            
            $statusUploadCabang[$cabang] = $isUploaded;
        }

        return view('livewire.admin.dashboard', [
            'jualHariIni' => $jualHariIni,
            'returHariIni' => $returHariIni,
            'piutangHariIni' => $piutangHariIni,
            'pelunasanHariIni' => $pelunasanHariIni,
            'recentPenjualan' => $recentPenjualan,
            'recentRetur' => $recentRetur,
            'hariIni' => $hariIni->translatedFormat('l, d F Y'),
            'statusUploadCabang' => $statusUploadCabang, // Kirim data status ke view
        ])->layout('layouts.app');
    }
}