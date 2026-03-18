<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Transaksi\Penjualan;
use App\Services\Import\PenjualanImportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PenjualanIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $startDate;
    public $endDate;
    
    // --- PASTIKAN INI ARRAY ---
    public $filterCabang = []; 
    // -------------------------

    // Modal Import
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    // Modal Hapus Tanggal
    public $isDeleteDateOpen = false;
    public $deleteDateInput;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    
    public function resetFilter()
    {
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Penjualan::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('trans_no', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_name', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate]);
        }

        // --- FILTER MULTI SELECT ---
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }
        // ---------------------------

        $summary = [
            'total_omzet'  => (clone $query)->sum('total_grand'),
            'total_faktur' => (clone $query)->distinct('trans_no')->count('trans_no'),
            'total_items'  => (clone $query)->count(),
        ];

        $penjualans = $query->orderBy('tgl_penjualan', 'desc')->paginate(50);

        $optCabang = Cache::remember('opt_cabang_jual', 3600, fn() => 
            Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang')
        );

        return view('livewire.transaksi.penjualan-index', compact('penjualans', 'optCabang', 'summary'))
            ->layout('layouts.app', ['header' => 'Transaksi Penjualan']);
    }

    // --- IMPORT ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import() 
    {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:153600']);
        
        $lock = Cache::lock('importing_penjualan', 600);
        if (!$lock->get()) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Import sedang berjalan. Tunggu!']);
            return;
        }

        ini_set('memory_limit', '-1');
        set_time_limit(0);

        try {
            $path = $this->file->store('temp-import', 'local');
            $fullPath = Storage::disk('local')->path($path);

            $stats = (new PenjualanImportService)->handle($fullPath, $this->resetData); 
            
            if(Storage::disk('local')->exists($path)) { Storage::disk('local')->delete($path); }
            Cache::forget('opt_cabang_jual');
            $this->closeImportModal();
            
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sukses! " . number_format($stats['processed']) . " Data masuk."]);

        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        } finally {
            $lock->release();
        }
    }

    // --- PROPERTY PENGAJUAN HAPUS ---
    public $isDeletePeriodModalOpen = false;
    public $deleteStartDate;
    public $deleteEndDate;
    public $deleteReason;

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

        \App\Models\DeletionRequest::create([
            'tipe_modul'      => 'penjualan', // UBAH INI SESUAI HALAMAN: 'retur', 'ar', atau 'collection'
            'tanggal_mulai'   => $this->deleteStartDate,
            'tanggal_selesai' => $this->deleteEndDate,
            'alasan'          => $this->deleteReason,
            'status'          => 'pending',
            'requested_by'    => auth()->id(),
        ]);

        $this->closeDeletePeriodModal();
        
        $this->dispatch('show-toast', [
            'type' => 'success', 
            'message' => 'Pengajuan hapus periode berhasil dikirim ke Supervisor!'
        ]);
    }
}