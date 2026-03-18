<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Keuangan\AccountReceivable;
use App\Models\DeletionRequest;
use App\Services\Import\ArImportService;
use Illuminate\Support\Facades\Cache;

class ArIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterCabang = []; 
    public $filterUmur = '';

    // Properti Import
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    // --- PROPERTI PENGAJUAN HAPUS ---
    public $isDeletePeriodModalOpen = false;
    public $deleteStartDate;
    public $deleteEndDate;
    public $deleteReason;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    
    public function resetFilter() 
    { 
        $this->reset(['search', 'filterCabang', 'filterUmur']); 
        $this->resetPage(); 
    }

    // --- FUNGSI PENGAJUAN HAPUS ---
    public function openDeletePeriodModal()
    {
        $this->resetValidation();
        $this->reset(['deleteStartDate', 'deleteEndDate', 'deleteReason']);
        $this->isDeletePeriodModalOpen = true;
    }

    public function closeDeletePeriodModal()
    {
        $this->isDeletePeriodModalOpen = false;
    }

    public function submitDeletionRequest()
    {
        $this->validate([
            'deleteStartDate' => 'required|date',
            'deleteEndDate'   => 'required|date|after_or_equal:deleteStartDate',
            'deleteReason'    => 'required|string|min:10',
        ], [
            'deleteEndDate.after_or_equal' => 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.',
            'deleteReason.min' => 'Berikan alasan minimal 10 karakter.',
        ]);

        DeletionRequest::create([
            'tipe_modul'      => 'ar',
            'tanggal_mulai'   => $this->deleteStartDate,
            'tanggal_selesai' => $this->deleteEndDate,
            'alasan'          => $this->deleteReason,
            'status'          => 'pending',
            'requested_by'    => auth()->id(),
        ]);

        $this->closeDeletePeriodModal();
        
        $this->dispatch('show-toast', [
            'type' => 'success', 
            'message' => 'Pengajuan hapus periode Piutang berhasil dikirim ke Supervisor!'
        ]);
    }

    public function render()
    {
        $query = AccountReceivable::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_penjualan', 'like', '%'.$this->search.'%')
                  ->orWhere('pelanggan_name', 'like', '%'.$this->search.'%');
            });
        }
        
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }
        
        if ($this->filterUmur == 'lancar') $query->where('umur_piutang', '<=', 30);
        if ($this->filterUmur == 'macet') $query->where('umur_piutang', '>', 30);

        $summary = [
            'total_piutang' => (clone $query)->sum('nilai'),
            'total_macet'   => (clone $query)->where('umur_piutang', '>', 30)->sum('nilai'),
            'total_faktur'  => (clone $query)->count(),
        ];

        $ars = $query->orderBy('umur_piutang', 'desc')->paginate(50);
        
        $optCabang = Cache::remember('opt_cabang_ar', 3600, fn() => 
            AccountReceivable::select('cabang')->distinct()->pluck('cabang')
        );

        return view('livewire.transaksi.ar-index', compact('ars', 'optCabang', 'summary'))
            ->layout('layouts.app', ['header' => 'Piutang (AR)']);
    }

    // Import Handlers
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import() {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:153600']);
        
        $path = $this->file->getRealPath();
        try {
            $stats = (new ArImportService)->handle($path, $this->resetData);
            Cache::forget('opt_cabang_ar');
            $this->closeImportModal();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sukses import " . number_format($stats['processed']) . " data."]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => $e->getMessage()]);
        }
    }
}