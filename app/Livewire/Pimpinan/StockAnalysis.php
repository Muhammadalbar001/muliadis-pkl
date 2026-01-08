<?php

namespace App\Livewire\Pimpinan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Untuk mencatat error
use Barryvdh\DomPDF\Facade\Pdf;

class StockAnalysis extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCabang = '';   
    public $selectedSuppliers = []; 
    public $perPage = 50;

    public function mount()
    {
        $firstBranch = Produk::whereNotNull('cabang')->where('cabang', '!=', '')->orderBy('cabang')->first();
        if ($firstBranch) {
            $this->selectedCabang = $firstBranch->cabang;
        }
    }

    public function setCabang($namaCabang)
    {
        $this->selectedCabang = $namaCabang;
        $this->selectedSuppliers = [];
        $this->resetPage();
    }

    public function updatedSelectedSuppliers()
    {
        $this->resetPage();
    }

    // --- FUNGSI EXPORT PDF (DENGAN PENANGANAN ERROR) ---
    public function exportPdf()
    {
        try {
            // 1. Query Data
            $query = Produk::query()->where('cabang', $this->selectedCabang);

            if (!empty($this->selectedSuppliers)) {
                $query->whereIn('supplier', $this->selectedSuppliers);
                $supplierLabel = count($this->selectedSuppliers) . ' Supplier Terpilih';
            } else {
                $supplierLabel = 'SEMUA PEMASOK';
            }

            if ($this->search) {
                $query->where(function($q) {
                    $q->where('name_item', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%');
                });
            }

            $query->orderBy('supplier', 'asc')->orderBy('name_item', 'asc');
            
            $data = $query->get();

            if ($data->isEmpty()) {
                $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Data Kosong, tidak bisa dicetak.']);
                return;
            }

            // 2. Hitung Summary (Manual Loop agar aman dari error SQL)
            $totalQty = 0;
            $totalAset = 0;

            foreach($data as $item) {
                // Pastikan angka benar-benar angka (float)
                $stok = is_numeric($item->stok) ? (float)$item->stok : 0;
                $avg = is_numeric($item->avg) ? (float)$item->avg : 0;

                $totalQty += $stok;
                $totalAset += ($stok * $avg);
            }

            $summary = [
                'cabang'     => $this->selectedCabang,
                'supplier'   => $supplierLabel,
                'total_item' => $data->count(),
                'total_qty'  => $totalQty,
                'total_aset' => $totalAset
            ];

            // 3. Generate PDF
            // Pastikan view ini benar-benar ada di folder resources/views/livewire/pimpinan/exports/
            $pdf = Pdf::loadView('livewire.pimpinan.exports.stock-valuation-pdf', [
                'data' => $data,
                'summary' => $summary,
                'tanggal_cetak' => now()->format('d F Y H:i'),
                'user' => auth()->user()->name ?? 'System'
            ])->setPaper('a4', 'landscape');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'Valuasi_Stok_' . str_replace(' ', '_', $this->selectedCabang) . '.pdf');

        } catch (\Exception $e) {
            // JIKA MASIH ERROR, PESAN AKAN MUNCUL DI LAYAR
            Log::error('Gagal Cetak PDF: ' . $e->getMessage());
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Cetak: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $branches = Produk::select('cabang')->whereNotNull('cabang')->where('cabang', '!=', '')->distinct()->orderBy('cabang')->pluck('cabang');
        
        $suppliers = Produk::select('supplier')->whereNotNull('supplier')->where('supplier', '!=', '')->where('cabang', $this->selectedCabang)->distinct()->orderBy('supplier')->pluck('supplier');

        $products = collect(); 
        if (!empty($this->selectedSuppliers)) {
            $query = Produk::query()
                ->where('cabang', $this->selectedCabang)
                ->whereIn('supplier', $this->selectedSuppliers);

            if ($this->search) {
                $query->where(function($q) {
                    $q->where('name_item', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%');
                });
            }

            $query->orderBy('supplier', 'asc')->orderBy('name_item', 'asc');

            $products = $query->paginate($this->perPage);

            $products->getCollection()->transform(function ($item) {
                return [
                    'id'            => $item->id,
                    'nama_item'     => $item->name_item,
                    'good_konversi' => $item->good_konversi,
                    'ktn'           => $item->ktn,
                    'sell_per_week' => $item->sell_per_week,
                    'empty_field'   => $item->empty_field, 
                    'stok'          => $item->stok,
                    'buy'           => $item->buy,
                    'buy_disc'      => $item->buy_disc,
                    'avg'           => $item->avg,
                    'fix'           => $item->fix,
                    'ppn'           => $item->ppn,
                    'supplier'      => $item->supplier,
                    'cabang'        => $item->cabang,
                ];
            });
        }

        // Hitung Global Asset (Manual Loop agar aman)
        $currentBranchAsset = 0;
        if($this->selectedCabang) {
             // Menggunakan query biasa agar tidak error SQL pada kolom string
             $allBranchProducts = Produk::where('cabang', $this->selectedCabang)->get(['stok', 'avg']);
             foreach($allBranchProducts as $p) {
                 $currentBranchAsset += ((float)$p->stok * (float)$p->avg);
             }
        }

        return view('livewire.pimpinan.stock-analysis', [
            'branches'      => $branches,
            'suppliersList' => $suppliers,
            'products'      => $products,
            'globalAsset'   => $currentBranchAsset
        ])->layout('layouts.app', ['header' => 'Stock Analysis']);
    }
}