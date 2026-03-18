<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Keuangan\Collection;
use App\Models\DeletionRequest;
use App\Services\Import\CollectionImportService;

class CollectionIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterCabang = [];
    public $filterPenagih = '';
    
    // Properti Import
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    // --- PROPERTI PENGAJUAN HAPUS ---
    public $isDeletePeriodModalOpen = false;
    public $deleteStartDate;
    public $deleteEndDate;
    public $deleteReason;

    protected $queryString = ['search', 'filterPenagih'];

    public function updatingSearch() { $this->resetPage(); }
    public function resetFilter() { $this->reset(['search', 'filterCabang', 'filterPenagih']); }

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
            'tipe_modul'      => 'collection',
            'tanggal_mulai'   => $this->deleteStartDate,
            'tanggal_selesai' => $this->deleteEndDate,
            'alasan'          => $this->deleteReason,
            'status'          => 'pending',
            'requested_by'    => auth()->id(),
        ]);

        $this->closeDeletePeriodModal();
        
        $this->dispatch('show-toast', [
            'type' => 'success', 
            'message' => 'Pengajuan hapus periode Pelunasan berhasil dikirim ke Supervisor!'
        ]);
    }

    // Import Handlers
    public function openImportModal() { $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }

    public function import(CollectionImportService $importService)
    {
        $this->validate(['file' => 'required|mimes:xlsx,xls,csv|max:51200']);
        
        try {
            if ($this->resetData) {
                Collection::truncate();
            }

            $importService->handle($this->file->getRealPath());
            
            $this->isImportOpen = false;
            $this->file = null;
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Collection berhasil diimport']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $query = Collection::query();

        // Fitur Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('receive_no', 'like', '%'.$this->search.'%')
                  ->orWhere('outlet_name', 'like', '%'.$this->search.'%')
                  ->orWhere('invoice_no', 'like', '%'.$this->search.'%');
            });
        }

        // Fitur Filter
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        if ($this->filterPenagih) {
            $query->where('penagih', $this->filterPenagih);
        }

        $collections = $query->latest('tanggal')->paginate(15);

        // Summary Data
        $summary = [
            'total_cair' => (clone $query)->sum('receive_amount'),
            'total_bukti' => (clone $query)->distinct('receive_no')->count(),
            'total_faktur' => (clone $query)->distinct('invoice_no')->count(),
        ];

        // Opsi Dropdown
        $optCabang = Collection::distinct()->pluck('cabang')->filter()->toArray();
        $optPenagih = Collection::distinct()->whereNotNull('penagih')->pluck('penagih')->toArray();

        return view('livewire.transaksi.collection-index', [
            'collections' => $collections,
            'summary' => $summary,
            'optCabang' => $optCabang,
            'optPenagih' => $optPenagih
        ])->layout('layouts.app');
    }
}