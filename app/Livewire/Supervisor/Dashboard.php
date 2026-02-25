<?php

namespace App\Livewire\Supervisor;

use Livewire\Component;
use App\Models\Master\Produk;
use App\Models\Master\Supplier;
use App\Models\Master\Sales;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // 1. METRIK MASTER DATA
        $totalProduk = Produk::count();
        $produkKosong = Produk::where('stok', '<=', 0)->count(); 
        $totalSupplier = Supplier::count();
        $totalSalesman = Sales::count();

        // 2. STATUS SINKRONISASI (Waktu terakhir Admin melakukan Import Penjualan)
        $lastSync = Penjualan::latest('created_at')->first()?->created_at;

        // 3. ANOMALI DATA (Mendeteksi item terjual yang SKU-nya belum terdaftar di Master Produk)
        // Jika hasil > 0, artinya Admin memasukkan transaksi untuk produk "Gaib" (tidak terdaftar)
        $anomaliProdukCount = DB::table('penjualans')
            ->whereNotNull('sku')
            ->whereNotIn('sku', function($query) {
                $query->select('sku')->from('produks')->whereNotNull('sku');
            })
            ->distinct()
            ->count('sku');

        // 4. PENGAWASAN OPERASIONAL: Top Retur Bulan Ini
        $topRetur = Retur::selectRaw('nama_item, SUM(CAST(qty AS UNSIGNED)) as total_qty, SUM(CAST(total_grand AS UNSIGNED)) as total_nilai')
            ->whereMonth('tgl_retur', $bulanIni)
            ->whereYear('tgl_retur', $tahunIni)
            ->groupBy('nama_item')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // 5. ACTION NEEDED: Piutang Kritis (> 30 Hari)
        $piutangKritis = AccountReceivable::where('umur_piutang', '>', 30)
            ->where('status', '!=', 'Lunas')
            ->orderByRaw('CAST(nilai AS UNSIGNED) DESC')
            ->take(5)
            ->get();

        // 6. MINI RAPOR: Bottom 3 Salesman (Salesman dengan Omzet Terendah Bulan Ini untuk di Coaching)
        $bottomSales = Penjualan::selectRaw('sales_name, SUM(CAST(total_grand AS UNSIGNED)) as total_omzet')
            ->whereMonth('tgl_penjualan', $bulanIni)
            ->whereYear('tgl_penjualan', $tahunIni)
            ->groupBy('sales_name')
            ->orderBy('total_omzet', 'asc') // ASC = Dari yang paling rendah
            ->take(3)
            ->get();

        return view('livewire.supervisor.dashboard', [
            'totalProduk' => $totalProduk,
            'produkKosong' => $produkKosong,
            'totalSupplier' => $totalSupplier,
            'totalSalesman' => $totalSalesman,
            'lastSync' => $lastSync,
            'anomaliProdukCount' => $anomaliProdukCount,
            'topRetur' => $topRetur,
            'piutangKritis' => $piutangKritis,
            'bottomSales' => $bottomSales,
        ])->layout('layouts.app');
    }
}