<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Supplier;
use App\Models\Master\Produk;
use Illuminate\Support\Facades\Cache;
use Throwable;

class SupplierIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCabang = []; 
    // --- TAMBAHAN FILTER BARU ---
    public $filterKategori = [];
    public $filterStatus = ''; // '' = Semua, '1' = Aktif, '0' = Non-Aktif
    // ----------------------------

    public $isOpen = false;
    
    // Form Input menggunakan Bahasa Indonesia
    public $supplierId, $cabang, $nama_supplier, $kategori, $nama_kontak, $telepon, $email, $alamat;
    public $is_active = true;

    // Reset halaman saat filter berubah
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterKategori() { $this->resetPage(); }
    public function updatedFilterStatus() { $this->resetPage(); }

    public function render()
    {
        $query = Supplier::query();

        // 1. Search (Nama Supplier & Narahubung)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_supplier', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_kontak', 'like', '%'.$this->search.'%');
            });
        }

        // 2. Filter Multi-Cabang
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        // 3. Filter Multi-Kategori (Divisi)
        if (!empty($this->filterKategori)) {
            $query->whereIn('kategori', $this->filterKategori);
        }

        // 4. Filter Status
        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus);
        }

        $suppliers = $query->orderBy('is_active', 'desc')
                           ->orderBy('nama_supplier', 'asc')
                           ->paginate(25);

        // Ambil pilihan filter dari cache agar performa cepat
        $optCabang = Cache::remember('opt_cabang_supp', 3600, function () {
            return Supplier::select('cabang')->distinct()
                ->whereNotNull('cabang')->where('cabang', '!=', '')
                ->orderBy('cabang')->pluck('cabang');
        });

        $optKategori = Cache::remember('opt_kategori_supp', 3600, function () {
            return Supplier::select('kategori')->distinct()
                ->whereNotNull('kategori')->where('kategori', '!=', '')
                ->orderBy('kategori')->pluck('kategori');
        });

        return view('livewire.master.supplier-index', compact('suppliers', 'optCabang', 'optKategori'))
            ->layout('layouts.app', ['header' => 'Master Supplier']);
    }

    // --- SYNC DARI PRODUK (DENGAN KATEGORI/DIVISI) ---
    public function syncFromProducts()
    {
        try {
            $dataProduk = Produk::select('cabang', 'supplier', 'divisi')
                ->whereNotNull('supplier')->where('supplier', '!=', '')
                ->whereNotNull('cabang')->where('cabang', '!=', '')
                ->distinct()->get();

            if ($dataProduk->isEmpty()) {
                $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Tidak ada data supplier di tabel Produk.']);
                return;
            }

            $count = 0;
            foreach ($dataProduk as $item) {
                $supplier = Supplier::updateOrCreate(
                    [
                        'cabang' => trim($item->cabang), 
                        'nama_supplier' => trim($item->supplier)
                    ],
                    [
                        'kategori' => trim($item->divisi) ?: 'UMUM',
                        'is_active' => true
                    ]
                );
                
                if ($supplier->wasRecentlyCreated) $count++;
            }

            // Hapus cache agar filter kategori/cabang terbaru muncul
            Cache::forget('opt_cabang_supp');
            Cache::forget('opt_kategori_supp');
            
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sync Selesai! $count Supplier baru ditambahkan."]);

        } catch (Throwable $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Sync: ' . $e->getMessage()]);
        }
    }

    // --- SISANYA TETAP SAMA (CRUD & MODAL) ---

    public function create() { $this->resetInputFields(); $this->openModal(); }
    public function openModal() { $this->isOpen = true; }
    public function closeModal() { $this->isOpen = false; $this->resetInputFields(); }

    private function resetInputFields() {
        $this->reset(['supplierId', 'cabang', 'nama_supplier', 'kategori', 'nama_kontak', 'telepon', 'email', 'alamat']);
        $this->is_active = true;
    }

    public function store() {
        $this->validate([
            'cabang' => 'required', 
            'nama_supplier' => 'required|min:3',
            'email' => 'nullable|email',
            'telepon' => 'nullable|numeric'
        ]);

        $exists = Supplier::where('cabang', $this->cabang)
            ->where('nama_supplier', $this->nama_supplier)
            ->where('id', '!=', $this->supplierId)
            ->exists();

        if ($exists) {
            $this->addError('nama_supplier', 'Supplier ini sudah ada di cabang tersebut.');
            return;
        }

        Supplier::updateOrCreate(['id' => $this->supplierId], [
            'cabang' => $this->cabang,
            'nama_supplier' => $this->nama_supplier,
            'kategori' => $this->kategori,
            'nama_kontak' => $this->nama_kontak,
            'telepon' => $this->telepon,
            'email' => $this->email,
            'alamat' => $this->alamat,
            'is_active' => $this->is_active,
        ]);

        $this->closeModal();
        Cache::forget('opt_cabang_supp');
        Cache::forget('opt_kategori_supp');
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Supplier berhasil disimpan.']);
    }

    public function edit($id) {
        $supplier = Supplier::findOrFail($id);
        $this->supplierId = $id;
        $this->cabang = $supplier->cabang;
        $this->nama_supplier = $supplier->nama_supplier;
        $this->kategori = $supplier->kategori;
        $this->nama_kontak = $supplier->nama_kontak;
        $this->telepon = $supplier->telepon;
        $this->email = $supplier->email;
        $this->alamat = $supplier->alamat;
        $this->is_active = $supplier->is_active;
        $this->openModal();
    }

    public function toggleStatus($id) {
        $supplier = Supplier::findOrFail($id);
        $supplier->update(['is_active' => !$supplier->is_active]);
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Status Supplier berhasil diubah.']);
    }

    public function delete($id) {
        Supplier::destroy($id);
        Cache::forget('opt_cabang_supp');
        Cache::forget('opt_kategori_supp');
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Supplier berhasil dihapus.']);
    }
}