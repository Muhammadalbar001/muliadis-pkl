<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Keuangan\Collection;
use App\Models\DeletionRequest; // Model baru yang kita buat tadi
use Carbon\Carbon;

class Dashboard extends Component
{
    // Property untuk Modal Pengajuan Hapus
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

    // Fungsi membuka modal dan mereset isi form
    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['tipe_modul', 'tanggal_mulai', 'tanggal_selesai', 'alasan']);
        $this->showModal = true;
    }

    // Fungsi menyimpan pengajuan ke database
    public function submitDeletionRequest()
    {
        $this->validate();

        DeletionRequest::create([
            'tipe_modul' => $this->tipe_modul,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'alasan' => $this->alasan,
            'status' => 'pending',
            'requested_by' => auth()->id(), // ID Admin yang sedang login
        ]);

        $this->showModal = false; // Tutup modal
        
        // Memunculkan pesan sukses sementara di atas layar
        session()->flash('message', 'Pengajuan hapus data berhasil dikirim ke Supervisor!');
    }

    public function render()
    {
        $hariIni = Carbon::today();

        // 1. Mengambil jumlah data masuk hari ini
        $jualHariIni = Penjualan::whereDate('tgl_penjualan', $hariIni)->count();
        $returHariIni = Retur::whereDate('tgl_retur', $hariIni)->count();
        $piutangHariIni = AccountReceivable::whereDate('tgl_penjualan', $hariIni)->count(); 
        $pelunasanHariIni = Collection::whereDate('tanggal', $hariIni)->count();

        // 2. Aktivitas input terakhir
        $recentPenjualan = Penjualan::orderBy('created_at', 'desc')->take(3)->get();
        $recentRetur = Retur::orderBy('created_at', 'desc')->take(2)->get();

        // 3. Status checklist import per cabang
        $cabangList = ['Banjarmasin', 'Palangkaraya', 'Barabai', 'Batulicin', 'Pharma'];
        $statusUploadCabang = [];
        foreach ($cabangList as $cabang) {
            $isUploaded = Penjualan::where('cabang', $cabang)
                                   ->whereDate('tgl_penjualan', $hariIni)
                                   ->exists();
            $statusUploadCabang[$cabang] = $isUploaded;
        }

        // 4. Data Riwayat Pengajuan Hapus milik Admin ini (Max 4 terbaru)
        $riwayatPengajuan = DeletionRequest::where('requested_by', auth()->id())
                            ->orderBy('created_at', 'desc')
                            ->take(4)
                            ->get();

        return view('livewire.admin.dashboard', [
            'jualHariIni' => $jualHariIni,
            'returHariIni' => $returHariIni,
            'piutangHariIni' => $piutangHariIni,
            'pelunasanHariIni' => $pelunasanHariIni,
            'recentPenjualan' => $recentPenjualan,
            'recentRetur' => $recentRetur,
            'statusUploadCabang' => $statusUploadCabang,
            'riwayatPengajuan' => $riwayatPengajuan,
            'hariIni' => $hariIni->translatedFormat('l, d F Y')
        ])->layout('layouts.app');
    }
}