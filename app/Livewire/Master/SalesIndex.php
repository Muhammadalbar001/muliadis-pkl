<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Services\Import\SalesImportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SalesIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
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
    public $multiplier = 1000000; // Default Jutaan

    public $file, $resetData = false;

    public function mount() {
        $this->targetYear = date('Y');
        $this->resetInput();
    }

    // --- FITUR 1: AUTO DISCOVERY (SYNC DATA CERDAS) ---
    public function autoDiscover()
    {
        try {
            DB::beginTransaction();
            $transactions = DB::table('penjualans')
                ->select('sales_name', 'kode_sales', 'cabang')
                ->whereNotNull('sales_name')
                ->distinct()
                ->get();

            $added = 0; $updated = 0;
            foreach ($transactions as $trx) {
                $sales = null;
                if (!empty($trx->kode_sales)) { $sales = Sales::where('sales_code', $trx->kode_sales)->first(); }
                if (!$sales) { $sales = Sales::where('sales_name', $trx->sales_name)->first(); }

                if (!$sales) {
                    Sales::create([
                        'sales_name' => $trx->sales_name,
                        'sales_code' => $trx->kode_sales, 
                        'city'       => $trx->cabang,
                        'status'     => 'Active'
                    ]);
                    $added++;
                } else {
                    $doSave = false;
                    if (empty($sales->sales_code) && !empty($trx->kode_sales)) { $sales->sales_code = $trx->kode_sales; $doSave = true; }
                    if (empty($sales->city) && !empty($trx->cabang)) { $sales->city = $trx->cabang; $doSave = true; }
                    if ($doSave) { $sales->save(); $updated++; }
                }
            }
            DB::commit();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sync Selesai: $added Baru, $updated Dilengkapi"]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Sinkronisasi']);
        }
    }

    // --- FITUR 2: MANAJEMEN TARGET ---
    public function applyBulkTarget()
    {
        if ($this->bulkTarget !== null && $this->bulkTarget !== '') {
            // Bersihkan input dari titik/koma
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

    public function updatedTargetYear() {
        if ($this->salesId) { $this->loadTargets(); }
    }

    public function saveTargets() {
        try {
            DB::beginTransaction();
            foreach ($this->monthlyTargets as $month => $amount) {
                $cleanAmount = is_numeric($amount) ? $amount : (float) str_replace(['.', ','], '', $amount);
                SalesTarget::updateOrCreate(
                    ['sales_id' => $this->salesId, 'year' => $this->targetYear, 'month' => $month],
                    ['target_ims' => $cleanAmount]
                );
            }
            DB::commit();
            $this->isTargetOpen = false;
            $this->bulkTarget = null;
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Target Berhasil Disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Simpan Target']);
        }
    }

    // --- FITUR 3: CRUD ---
    public function store() {
        $this->validate([
            'sales_name' => 'required|min:3',
            'sales_code' => 'nullable|unique:sales,sales_code,' . $this->salesId,
            'nik'        => 'nullable|numeric|digits:16',
            'phone'      => 'nullable|numeric',
        ]);

        try {
            DB::beginTransaction();
            Sales::updateOrCreate(['id' => $this->salesId], [
                'sales_name'    => $this->sales_name,
                'sales_code'    => $this->sales_code ?: null,
                'phone'         => $this->phone ?: null,
                'nik'           => $this->nik ?: null,
                'alamat'        => $this->alamat ?: null,
                'tempat_lahir'  => $this->tempat_lahir ?: null,
                'tanggal_lahir' => !empty($this->tanggal_lahir) ? $this->tanggal_lahir : null, 
                'city'          => $this->city,
                'status'        => $this->status,
            ]);
            DB::commit();
            $this->closeModal();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Berhasil Diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Simpan']);
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
        $this->tanggal_lahir = $s->tanggal_lahir;
        $this->city = $s->city; 
        $this->status = $s->status; 
        $this->isOpen = true;
    }

    public function delete($id) {
        try {
            Sales::findOrFail($id)->delete();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Dihapus']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Hapus']);
        }
    }

    public function closeModal() { $this->isOpen = false; $this->isTargetOpen = false; $this->resetInput(); }
    
    public function resetInput() { 
        $this->reset(['salesId', 'sales_name', 'sales_code', 'phone', 'nik', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'city', 'monthlyTargets', 'bulkTarget']); 
        $this->status = 'Active'; 
        $this->multiplier = 1000000;
    }

    public function render() {
        $query = Sales::query();
        if ($this->search) {
            $query->where(fn($q) => $q->where('sales_name', 'like', '%'.$this->search.'%')->orWhere('sales_code', 'like', '%'.$this->search.'%')->orWhere('nik', 'like', '%'.$this->search.'%')->orWhere('phone', 'like', '%'.$this->search.'%'));
        }
        $sales = $query->orderByRaw("CASE WHEN status = 'Active' THEN 1 ELSE 2 END")->orderBy('sales_name')->paginate(15);
        return view('livewire.master.sales-index', compact('sales'))->layout('layouts.app');
    }
}