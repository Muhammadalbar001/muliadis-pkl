<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; 

class SalesIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    // --- 1. FILTER CABANG ---
    public $filterCabang = []; 
    // ------------------------

    public $isOpen = false;
    public $isImportOpen = false;
    public $isTargetOpen = false;
    
    // Properties Sales
    public $salesId, $sales_name, $sales_code, $city, $status = 'Active';
    public $phone, $nik, $alamat, $tempat_lahir, $tanggal_lahir;

    // Properties Target
    public $targetYear;
    public $monthlyTargets = []; 
    public $selectedSalesNameForTarget;

    // Properties Fitur Cepat
    public $bulkTarget; 
    public $multiplier = 1000000;

    // Reset halaman saat filter berubah
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }

    public function mount() {
        $this->targetYear = date('Y');
        $this->resetInput();
    }

    public function render() {
        $query = Sales::query();

        // A. SEARCH
        if ($this->search) {
            $query->where(fn($q) => $q->where('sales_name', 'like', '%'.$this->search.'%')
                                      ->orWhere('sales_code', 'like', '%'.$this->search.'%')
                                      ->orWhere('nik', 'like', '%'.$this->search.'%')
                                      ->orWhere('phone', 'like', '%'.$this->search.'%'));
        }

        // B. FILTER CABANG (CITY)
        if (!empty($this->filterCabang)) {
            $query->whereIn('city', $this->filterCabang);
        }

        $sales = $query->orderByRaw("CASE WHEN status = 'Active' THEN 1 ELSE 2 END")
                       ->orderBy('sales_name')
                       ->paginate(15);

        // --- DATA UNTUK FILTER (Cache) ---
        $optCabang = Cache::remember('opt_cabang_sales', 3600, function () {
            return Sales::select('city')->distinct()
                ->whereNotNull('city')->where('city', '!=', '')
                ->orderBy('city')->pluck('city');
        });

        // --- DATA UNTUK FORM DROPDOWN (Default + DB) ---
        $defaultCabang = collect(['Banjarmasin', 'Palangkaraya', 'Barabai', 'Batulicin', 'Pharma']);
        $formCabang = $defaultCabang->merge($optCabang)->unique()->sort()->values();

        return view('livewire.master.sales-index', compact('sales', 'optCabang', 'formCabang'))
            ->layout('layouts.app');
    }

    public function autoDiscover()
    {
        try {
            $salesFromTrx = Penjualan::select('kode_sales', 'sales_name', 'cabang')
                ->whereNotNull('kode_sales')
                ->distinct()
                ->get();

            $count = 0;
            foreach ($salesFromTrx as $trx) {
                $exists = Sales::where('sales_code', $trx->kode_sales)->exists();
                
                if (!$exists) {
                    Sales::create([
                        'sales_code' => $trx->kode_sales,
                        'sales_name' => $trx->sales_name ?? 'Unknown',
                        'city'       => $trx->cabang ?? null, 
                        'status'     => 'Active'
                    ]);
                    $count++;
                }
            }

            // Clear cache agar filter cabang terupdate
            Cache::forget('opt_cabang_sales');

            $this->dispatch('show-toast', [
                'type' => 'success', 
                'message' => "Sinkronisasi Selesai. $count Sales baru ditambahkan."
            ]);

        } catch (\Exception $e) {
            Log::error("Auto Discover Error: " . $e->getMessage());
            $this->dispatch('show-toast', [
                'type' => 'error', 
                'message' => 'Gagal Sinkronisasi: ' . $e->getMessage()
            ]);
        }
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function store() {
        $this->validate([
            'sales_name' => 'required|min:3',
            'sales_code' => 'nullable|unique:sales,sales_code,' . $this->salesId,
            'nik'        => 'nullable|numeric|digits:16',
            'phone'      => 'nullable|numeric',
            'city'       => 'required', 
        ], [
            'sales_name.required' => 'Nama Salesman wajib diisi.',
            'sales_code.unique' => 'Kode Salesman sudah digunakan.',
            'nik.digits' => 'NIK harus 16 digit.',
            'city.required' => 'Silakan pilih cabang.',
        ]);

        try {
            DB::beginTransaction();
            
            $tanggalLahirValue = !empty($this->tanggal_lahir) ? $this->tanggal_lahir : null;

            Sales::updateOrCreate(['id' => $this->salesId], [
                'sales_name'    => $this->sales_name,
                'sales_code'    => $this->sales_code ?: null,
                'phone'         => $this->phone ?: null,
                'nik'           => $this->nik ?: null,
                'alamat'        => $this->alamat ?: null,
                'tempat_lahir'  => $this->tempat_lahir ?: null,
                'tanggal_lahir' => $tanggalLahirValue, 
                'city'          => $this->city,
                'status'        => $this->status,
            ]);

            DB::commit();
            
            // Clear cache cabang
            Cache::forget('opt_cabang_sales');
            
            $this->closeModal();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Salesman Berhasil Disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Simpan Sales: ' . $e->getMessage());
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Simpan: ' . $e->getMessage()]);
        }
    }

    // --- MANAJEMEN TARGET ---
    public function applyBulkTarget() {
        if ($this->bulkTarget !== null && $this->bulkTarget !== '') {
            $val = (float) str_replace(['.', ','], '', $this->bulkTarget);
            $finalValue = $val * (float)$this->multiplier;
            for ($i = 1; $i <= 12; $i++) {
                $this->monthlyTargets[$i] = $finalValue;
            }
        }
    }

    public function manageTargets($id) {
        $sales = Sales::findOrFail($id);
        $this->salesId = $id;
        $this->selectedSalesNameForTarget = $sales->sales_name;
        $this->loadTargets();
        $this->isTargetOpen = true; 
    }

    public function loadTargets() {
        $this->monthlyTargets = [];
        for ($i = 1; $i <= 12; $i++) { $this->monthlyTargets[$i] = 0; }
        $targets = SalesTarget::where('sales_id', $this->salesId)->where('year', $this->targetYear)->get();
        foreach ($targets as $t) {
            $this->monthlyTargets[(int)$t->month] = (float)$t->target_ims;
        }
    }

    public function saveTargets() {
        try {
            DB::beginTransaction();
            foreach ($this->monthlyTargets as $month => $amount) {
                $cleanAmount = is_numeric($amount) ? (float)$amount : (float) str_replace(['.', ','], '', $amount);
                SalesTarget::updateOrCreate(
                    ['sales_id' => $this->salesId, 'year' => $this->targetYear, 'month' => $month],
                    ['target_ims' => $cleanAmount]
                );
            }
            DB::commit();
            $this->isTargetOpen = false;
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Target Berhasil Disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Simpan Target']);
        }
    }

    public function edit($id) {
        $s = Sales::findOrFail($id);
        $this->salesId = $id; 
        $this->sales_name = $s->sales_name; 
        $this->sales_code = $s->sales_code;
        $this->phone = $s->phone;
        $this->nik = $s->nik;
        $this->alamat = $s->alamat;
        $this->tempat_lahir = $s->tempat_lahir;
        $this->tanggal_lahir = $s->tanggal_lahir ? ($s->tanggal_lahir instanceof \DateTime ? $s->tanggal_lahir->format('Y-m-d') : date('Y-m-d', strtotime($s->tanggal_lahir))) : null;
        $this->city = $s->city; 
        $this->status = $s->status; 
        $this->isOpen = true;
    }

    public function delete($id) {
        try {
            Sales::findOrFail($id)->delete();
            Cache::forget('opt_cabang_sales'); 
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Dihapus']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Hapus']);
        }
    }

    public function closeModal() { 
        $this->isOpen = false; 
        $this->isTargetOpen = false; 
        $this->resetInput(); 
    }
    
    public function resetInput() { 
        $this->reset(['salesId', 'sales_name', 'sales_code', 'phone', 'nik', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'city', 'monthlyTargets', 'bulkTarget']); 
        $this->status = 'Active'; 
        $this->multiplier = 1000000;
    }
}