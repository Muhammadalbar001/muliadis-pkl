<?php

namespace App\Livewire\Supervisor;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\DeletionRequest;
use App\Models\Master\Produk;
use App\Models\Master\Sales;
use App\Models\Master\Supplier;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    use WithPagination;

    public $activeTab = 'master'; // Tab default

    // Variabel Otorisasi
    public $requestIdToProcess = null;
    public $actionType = ''; 
    public $showConfirmModal = false;

    // Variabel Filter Evaluasi Kinerja
    public $evalBulan;
    public $evalTahun;

    public function mount()
    {
        $this->evalBulan = date('m');
        $this->evalTahun = date('Y');
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // ==========================================
    // LOGIKA 1: DATA HEALTH CHECK (Kesehatan Data)
    // ==========================================
    #[Computed]
    public function dataHealth()
    {
        // Mengecek produk yang datanya tidak lengkap (tanpa supplier)
        $produkInvalid = Produk::whereNull('supplier')->orWhere('supplier', '')->count();
        
        // Mengecek Salesman yang aktif TAPI belum diberi Target Penjualan bulan ini
        $salesActiveIds = Sales::where('status', 'Active')->pluck('id');
        $salesWithTargetIds = SalesTarget::where('year', date('Y'))->where('month', date('n'))->pluck('sales_id');
        $salesTanpaTarget = $salesActiveIds->diff($salesWithTargetIds)->count();

        return [
            'produk_invalid' => $produkInvalid,
            'sales_tanpa_target' => $salesTanpaTarget
        ];
    }

    // ==========================================
    // LOGIKA 2: EVALUASI KINERJA & ACTION PLAN
    // ==========================================
    #[Computed]
    public function evaluasiData()
    {
        $sales = Sales::where('status', 'Active')->get();
        $targets = SalesTarget::where('year', $this->evalTahun)->where('month', (int)$this->evalBulan)->get()->keyBy('sales_id');

        $start = Carbon::createFromDate($this->evalTahun, $this->evalBulan, 1)->startOfMonth()->format('Y-m-d');
        $end = Carbon::createFromDate($this->evalTahun, $this->evalBulan, 1)->endOfMonth()->format('Y-m-d');

        // 1. Hitung Target Pacing (Progress Bar)
        $realisasi = Penjualan::whereBetween('tgl_penjualan', [$start, $end])
            ->selectRaw('sales_name, SUM(total_grand) as omzet')
            ->groupBy('sales_name')
            ->pluck('omzet', 'sales_name');

        $pacing = [];
        foreach($sales as $s) {
            $target = $targets->get($s->id)->target_ims ?? 0;
            $omzet = $realisasi->get($s->sales_name) ?? 0;
            $persen = $target > 0 ? ($omzet / $target) * 100 : 0;
            
            $pacing[] = [
                'nama' => $s->sales_name,
                'target' => $target,
                'omzet' => $omzet,
                'persen' => $persen
            ];
        }
        usort($pacing, fn($a, $b) => $b['persen'] <=> $a['persen']);

        // 2. Action Plan: Coaching Sales (Ambil 3 terbawah yang targetnya belum tercapai)
        $coaching = array_filter($pacing, fn($p) => $p['target'] > 0 && $p['persen'] < 50);
        $coaching = array_slice(array_reverse($coaching), 0, 3);

        // 3. Action Plan: Follow Up Pelanggan Churn (Tidak order > 30 hari)
        $churn = Penjualan::selectRaw('nama_pelanggan, MAX(tgl_penjualan) as last_order, sales_name')
            ->groupBy('nama_pelanggan', 'sales_name')
            ->having('last_order', '<', Carbon::now()->subDays(30)->format('Y-m-d'))
            ->orderBy('last_order', 'asc')
            ->limit(5)
            ->get();

        return compact('pacing', 'coaching', 'churn');
    }

    // ==========================================
    // LOGIKA 3: OTORISASI HAPUS DATA
    // ==========================================
    public function confirmAction($id, $type)
    {
        $this->requestIdToProcess = $id;
        $this->actionType = $type;
        $this->showConfirmModal = true;
    }

    public function executeAction()
    {
        $request = DeletionRequest::find($this->requestIdToProcess);
        
        if ($request && $request->status === 'Pending') {
            if ($this->actionType === 'approve') {
                $request->update(['status' => 'Disetujui']);
                if ($request->modul == 'Penjualan') DB::table('penjualans')->whereBetween('tgl_penjualan', [$request->tanggal_awal, $request->tanggal_akhir])->delete();
                elseif ($request->modul == 'Retur') DB::table('returs')->whereBetween('tgl_retur', [$request->tanggal_awal, $request->tanggal_akhir])->delete();
                elseif ($request->modul == 'Piutang') DB::table('account_receivables')->whereBetween('tgl_penjualan', [$request->tanggal_awal, $request->tanggal_akhir])->delete();
                elseif ($request->modul == 'Pelunasan') DB::table('collections')->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir])->delete();

                $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Pengajuan disetujui, Data berhasil dihapus dari sistem.']);
            } elseif ($this->actionType === 'reject') {
                $request->update(['status' => 'Ditolak']);
                $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Pengajuan penghapusan ditolak.']);
            }
        }
        $this->showConfirmModal = false;
    }

    public function closeModal() { $this->showConfirmModal = false; }

    public function render()
    {
        $totalProduk = Produk::count();
        $totalSupplier = Supplier::count();
        $totalSalesman = Sales::count();
        $totalUser = User::count();

        $antreanHapus = DeletionRequest::with('user')->orderBy('created_at', 'desc')->paginate(10);
        $health = $this->dataHealth;
        $eval = $this->evaluasiData;

        return view('livewire.supervisor.dashboard', compact(
            'totalProduk', 'totalSupplier', 'totalSalesman', 'totalUser', 'antreanHapus', 'health', 'eval'
        ))->layout('layouts.app', ['header' => 'Supervisor Dashboard']);
    }
}