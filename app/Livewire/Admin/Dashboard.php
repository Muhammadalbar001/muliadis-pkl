<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Keuangan\Collection;
use App\Models\DeletionRequest; // Model Pengajuan
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    // === FILTER PERIODE ===
    public $bulan;
    public $tahun;

    // === PROPERTY MODAL PENGAJUAN HAPUS ===
    public $showModal = false;
    public $tipe_modul = '';
    public $tanggal_mulai = '';
    public $tanggal_selesai = '';
    public $alasan = '';

    // Aturan Validasi
    protected $rules = [
        'tipe_modul' => 'required|in:penjualan,retur,ar,collection',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'alasan' => 'required|string|min:10',
    ];

    protected $messages = [
        'tipe_modul.required' => 'Modul data wajib dipilih.',
        'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih lama dari tanggal mulai.',
        'alasan.min' => 'Berikan alasan yang jelas (minimal 10 karakter).',
    ];

    public function mount()
    {
        // Set default filter ke bulan & tahun saat ini
        $this->bulan = date('m');
        $this->tahun = date('Y');
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['tipe_modul', 'tanggal_mulai', 'tanggal_selesai', 'alasan']);
        $this->showModal = true;
    }

    public function submitDeletionRequest()
    {
        $this->validate();

        DeletionRequest::create([
            'tipe_modul' => $this->tipe_modul,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'alasan' => $this->alasan,
            'status' => 'pending',
            'requested_by' => auth()->id(), // ID Admin
        ]);

        $this->showModal = false; 
        session()->flash('message', 'Pengajuan hapus data berhasil dikirim ke Supervisor!');
    }

    public function render()
    {
        // Mendapatkan format nama bulan untuk UI
        $selectedDate = Carbon::createFromDate($this->tahun, $this->bulan, 1);
        $namaBulan = $selectedDate->translatedFormat('F Y');

        // 1. MENGAMBIL JUMLAH DATA MASUK BERDASARKAN BULAN & TAHUN PILIHAN
        $statPenjualan = Penjualan::whereMonth('tgl_penjualan', $this->bulan)->whereYear('tgl_penjualan', $this->tahun)->count();
        $statRetur     = Retur::whereMonth('tgl_retur', $this->bulan)->whereYear('tgl_retur', $this->tahun)->count();
        $statAr        = AccountReceivable::whereMonth('tgl_penjualan', $this->bulan)->whereYear('tgl_penjualan', $this->tahun)->count(); 
        $statColl      = Collection::whereMonth('tanggal', $this->bulan)->whereYear('tanggal', $this->tahun)->count();

        // 2. AKTIVITAS INPUT TERAKHIR (Pada bulan pilihan)
        $recentPenjualan = Penjualan::whereMonth('tgl_penjualan', $this->bulan)->whereYear('tgl_penjualan', $this->tahun)->orderBy('created_at', 'desc')->take(3)->get();
        $recentRetur     = Retur::whereMonth('tgl_retur', $this->bulan)->whereYear('tgl_retur', $this->tahun)->orderBy('created_at', 'desc')->take(2)->get();

        // 3. RIWAYAT PENGAJUAN HAPUS MILIK ADMIN INI
        $riwayatPengajuan = DeletionRequest::where('requested_by', auth()->id())
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        // ========================================================
        // 4. SMART ALERTS (PENGINGAT & NOTIFIKASI)
        // ========================================================
        $alerts = collect();

        // Alert: Jika belum ada import sama sekali di bulan yang dipilih
        if ($statPenjualan == 0) {
            $alerts->push([
                'type' => 'warning', 'icon' => 'fas fa-file-excel',
                'title' => 'Pengingat: Sinkronisasi Penjualan',
                'message' => "Anda belum melakukan <em>import</em> data <strong>Penjualan</strong> untuk periode <strong>{$namaBulan}</strong>. Segera unggah file operasional agar Pimpinan dapat memantau data terbaru.",
                'link' => route('transaksi.penjualan')
            ]);
        }

        if ($statRetur == 0) {
            $alerts->push([
                'type' => 'warning', 'icon' => 'fas fa-file-excel',
                'title' => 'Pengingat: Sinkronisasi Retur',
                'message' => "Belum ada data <strong>Retur Barang</strong> di periode <strong>{$namaBulan}</strong>. Pastikan tidak ada dokumen retur gudang yang terlewat.",
                'link' => route('transaksi.retur')
            ]);
        }

        // Alert: Notifikasi Hasil Keputusan Supervisor (Hanya muncul 2 hari terakhir)
        $recentUpdates = DeletionRequest::where('requested_by', auth()->id())
                            ->whereIn('status', ['Disetujui', 'Ditolak', 'approved', 'rejected'])
                            ->where('updated_at', '>=', Carbon::now()->subDays(2))
                            ->orderBy('updated_at', 'desc')
                            ->take(2) 
                            ->get();

        foreach ($recentUpdates as $update) {
            $isApproved = in_array($update->status, ['Disetujui', 'approved']);
            $statusText = $isApproved ? 'DISETUJUI' : 'DITOLAK';
            $type = $isApproved ? 'success' : 'danger';
            $icon = $isApproved ? 'fas fa-check-circle' : 'fas fa-times-circle';
            
            $alerts->push([
                'type' => $type,
                'icon' => $icon,
                'title' => "Respon Supervisor: {$statusText}",
                'message' => "Pengajuan penghapusan data <strong>" . strtoupper($update->tipe_modul) . "</strong> untuk rentang periode " . Carbon::parse($update->tanggal_mulai)->format('d/m/Y') . " s.d " . Carbon::parse($update->tanggal_selesai)->format('d/m/Y') . " telah <strong>{$statusText}</strong>.",
            ]);
        }

        return view('livewire.admin.dashboard', compact(
            'statPenjualan', 'statRetur', 'statAr', 'statColl', 
            'recentPenjualan', 'recentRetur', 'riwayatPengajuan', 
            'alerts', 'namaBulan'
        ))->layout('layouts.app', ['header' => 'Pusat Operasional Data']);
    }
}