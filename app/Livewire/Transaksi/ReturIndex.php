<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Transaksi\Retur;
use App\Models\DeletionRequest;
use App\Services\Import\ReturImportService;
use Illuminate\Support\Facades\Cache;

class ReturIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $startDate;
    public $endDate;
    public $filterCabang = []; 

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
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang']); 
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
            'tipe_modul'      => 'retur',
            'tanggal_mulai'   => $this->deleteStartDate,
            'tanggal_selesai' => $this->deleteEndDate,
            'alasan'          => $this->deleteReason,
            'status'          => 'pending',
            'requested_by'    => auth()->id(),
        ]);

        $this->closeDeletePeriodModal();
        
        $this->dispatch('show-toast', [
            'type' => 'success', 
            'message' => 'Pengajuan hapus periode Retur berhasil dikirim ke Supervisor!'
        ]);
    }

    public function render()
    {
        $query = Retur::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%');
            });
        }
        
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_retur', [$this->startDate, $this->endDate]);
        }
        
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        $summary = [
            'total_nilai'  => (clone $query)->sum('total_grand'),
            'total_faktur' => (clone $query)->distinct('no_retur')->count('no_retur'),
            'total_items'  => (clone $query)->count(),
        ];

        $returs = $query->orderBy('tgl_retur', 'desc')->paginate(50);
        
        $optCabang = Cache::remember('opt_cabang_retur', 3600, fn() => 
            Retur::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang')
        );

        return view('livewire.transaksi.retur-index', compact('returs', 'optCabang', 'summary'))
            ->layout('layouts.app', ['header' => 'Retur Penjualan']);
    }

    // Import Handlers
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import() {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:153600']);
        
        $path = $this->file->getRealPath(); 
        try {
            $stats = (new ReturImportService)->handle($path, $this->resetData);
            Cache::forget('opt_cabang_retur');
            $this->closeImportModal();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sukses import " . number_format($stats['processed']) . " data."]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => $e->getMessage()]);
        }
    }
}