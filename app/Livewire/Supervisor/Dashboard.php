<?php

namespace App\Livewire\Supervisor;

use Livewire\Component;
use App\Models\Master\Produk;
use App\Models\Master\Supplier;
use App\Models\Master\Sales;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Keuangan\Collection;
use App\Models\DeletionRequest; // Model Pengajuan
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    // === FUNGSI PERSETUJUAN HAPUS (APPROVE) ===
    public function approveDeletion($id)
    {
        $request = DeletionRequest::findOrFail($id);

        if ($request->tipe_modul == 'penjualan') {
            Penjualan::whereBetween('tgl_penjualan', [$request->tanggal_mulai, $request->tanggal_selesai])->delete();
        } elseif ($request->tipe_modul == 'retur') {
            Retur::whereBetween('tgl_retur', [$request->tanggal_mulai, $request->tanggal_selesai])->delete();
        } elseif ($request->tipe_modul == 'ar') {
            AccountReceivable::whereBetween('tgl_penjualan', [$request->tanggal_mulai, $request->tanggal_selesai])->delete();
        } elseif ($request->tipe_modul == 'collection') {
            Collection::whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai])->delete();
        }

        $request->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        session()->flash('message', 'Pengajuan disetujui! Data ' . strtoupper($request->tipe_modul) . ' berhasil dihapus permanen.');
    }

    // === FUNGSI TOLAK HAPUS (REJECT) ===
    public function rejectDeletion($id)
    {
        $request = DeletionRequest::findOrFail($id);
        
        $request->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        session()->flash('error', 'Pengajuan hapus data ditolak.');
    }

    public function render()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // 1. METRIK MASTER DATA
        $totalProduk = Produk::count();
        $produkKosong = Produk::where('stok', '<=', 0)->count(); 
        $totalSupplier = Supplier::count();
        $totalSalesman = Sales::count();

        // 2. STATUS SINKRONISASI Terakhir
        $lastSync = Penjualan::latest('created_at')->first()?->created_at;

        // 3. ANOMALI DATA
        $anomaliProdukCount = DB::table('penjualans')
            ->whereNotNull('sku')
            ->whereNotIn('sku', function($query) {
                $query->select('sku')->from('produks')->whereNotNull('sku');
            })
            ->distinct()
            ->count('sku');

        // 4. DAFTAR PENGAJUAN HAPUS (PENDING)
        $pendingRequests = DeletionRequest::with('requester')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        // 5. Peringatan Operasional (Retur, Piutang Kritis, Sales Rendah)
        $topRetur = Retur::selectRaw('nama_item, SUM(CAST(qty AS UNSIGNED)) as total_qty, SUM(CAST(total_grand AS UNSIGNED)) as total_nilai')
            ->whereMonth('tgl_retur', $bulanIni)
            ->whereYear('tgl_retur', $tahunIni)
            ->groupBy('nama_item')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $piutangKritis = AccountReceivable::where('umur_piutang', '>', 30)
            ->where('status', '!=', 'Lunas')
            ->orderByRaw('CAST(nilai AS UNSIGNED) DESC')
            ->take(5)
            ->get();

        $bottomSales = Penjualan::selectRaw('sales_name, SUM(CAST(total_grand AS UNSIGNED)) as total_omzet')
            ->whereMonth('tgl_penjualan', $bulanIni)
            ->whereYear('tgl_penjualan', $tahunIni)
            ->groupBy('sales_name')
            ->orderBy('total_omzet', 'asc')
            ->take(3)
            ->get();

        // ========================================================
        // 6. SMART ALERTS (SISTEM PERINGATAN DINI UNTUK SUPERVISOR)
        // ========================================================
        $alerts = collect();

        // Alert 1: Otorisasi Tertunda (Prioritas Tertinggi)
        if ($pendingRequests->count() > 0) {
            $alerts->push([
                'type' => 'danger',
                'icon' => 'fas fa-clipboard-check',
                'title' => 'Otorisasi Tindakan Diperlukan',
                'message' => "Terdapat <strong>{$pendingRequests->count()} pengajuan hapus data</strong> operasional dari Admin yang menumpuk. Harap segera eksekusi pengajuan tersebut pada Tab Otorisasi untuk menjaga kebersihan database.",
                'action_tab' => 'persetujuan' // Untuk trigger tombol ke tab persetujuan
            ]);
        }

        // Alert 2: Anomali Data (SKU Siluman)
        if ($anomaliProdukCount > 0) {
            $alerts->push([
                'type' => 'warning',
                'icon' => 'fas fa-exclamation-triangle',
                'title' => 'Peringatan Integritas Master Data',
                'message' => "Sistem mendeteksi <strong>{$anomaliProdukCount} SKU produk</strong> pada riwayat transaksi penjualan yang tidak terdaftar di database Master Produk. Segera sinkronisasikan Master Produk agar analisa sistem tidak keliru.",
                'link' => route('master.produk')
            ]);
        }

        // Alert 3: Peringatan Stok Kosong
        if ($produkKosong > 0) {
            $alerts->push([
                'type' => 'info',
                'icon' => 'fas fa-box-open',
                'title' => 'Peringatan Ketersediaan Stok',
                'message' => "Terdapat <strong>{$produkKosong} jenis produk</strong> yang saat ini berstatus kehabisan stok fisik (Stok = 0). Silakan lakukan pengecekan master data dan koordinasikan dengan Supplier terkait.",
                'link' => route('master.produk')
            ]);
        }

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
            'pendingRequests' => $pendingRequests,
            'alerts' => $alerts, // Kirim Notifikasi Cerdas ke View
        ])->layout('layouts.app');
    }
}