<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Keuangan\Collection;
use Illuminate\Support\Facades\Auth;

class CollectionIndex extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '', $filter_cabang = '', $start_date = '', $end_date = '';
    public $showModal = false, $isEdit = false, $record_id;
    public $showModalImport = false, $file_excel;

    public $no_dokumen, $tanggal, $cabang, $sales_name, $nama_pelanggan, $nominal;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterCabang() { $this->resetPage(); }
    public function updatingStartDate() { $this->resetPage(); }
    public function updatingEndDate() { $this->resetPage(); }

    public function resetFilters() { $this->reset(['search', 'filter_cabang', 'start_date', 'end_date']); $this->resetPage(); }

    protected $rules = [
        'no_dokumen' => 'required|string|max:255',
        'tanggal' => 'required|date',
        'cabang' => 'required|string',
        'sales_name' => 'required|string|max:255',
        'nama_pelanggan' => 'required|string|max:255',
        'nominal' => 'required|numeric|min:0',
    ];

    public function create() {
        $this->reset(['no_dokumen', 'tanggal', 'cabang', 'sales_name', 'nama_pelanggan', 'nominal', 'record_id']);
        $this->tanggal = date('Y-m-d');
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id) {
        if(Auth::user()->role === 'operator') { session()->flash('error', 'Akses ditolak!'); return; }
        
        $data = Collection::findOrFail($id);
        $this->record_id = $data->id;
        
        // MAPPING KOLOM DATABASE (Sesuai Migration Collection)
        $this->no_dokumen = $data->receive_no; 
        $this->tanggal = $data->tanggal;
        $this->cabang = $data->cabang;
        $this->sales_name = $data->sales_name;
        $this->nama_pelanggan = $data->outlet_name; 
        $this->nominal = $data->receive_amount;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function store() {
        $this->validate();
        if ($this->isEdit && Auth::user()->role === 'operator') return; 

        // MAPPING KOLOM DATABASE (Sesuai Migration Collection)
        $dbData = [
            'receive_no' => $this->no_dokumen,
            'tanggal' => $this->tanggal,
            'cabang' => $this->cabang,
            'sales_name' => $this->sales_name,
            'outlet_name' => $this->nama_pelanggan,
            'receive_amount' => $this->nominal,
        ];

        if ($this->isEdit) { 
            Collection::findOrFail($this->record_id)->update($dbData); 
            session()->flash('message', 'Data diperbarui!'); 
        } else { 
            Collection::create($dbData); 
            session()->flash('message', 'Data baru ditambahkan!'); 
        }
        $this->showModal = false;
    }

    public function delete($id) {
        if(Auth::user()->role === 'operator') return;
        Collection::findOrFail($id)->delete(); session()->flash('message', 'Data dihapus!');
    }

    public function openModalImport() { $this->resetValidation(); $this->reset('file_excel'); $this->showModalImport = true; }

    public function importData() {
        $this->validate(['file_excel' => 'required|mimes:xlsx,xls,csv|max:512000']);
        // $importService = new \App\Services\Import\CollectionImportService();
        // $importService->import($this->file_excel->getRealPath());
        session()->flash('message', 'Data Pelunasan dari Excel berhasil diimpor!');
        $this->showModalImport = false; $this->reset('file_excel');
    }

    public function render() {
        $query = Collection::query();
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('receive_no', 'like', '%'.$this->search.'%')
                  ->orWhere('outlet_name', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_name', 'like', '%'.$this->search.'%');
            });
        }
        if (!empty($this->filter_cabang)) { $query->where('cabang', $this->filter_cabang); }
        if (!empty($this->start_date) && !empty($this->end_date)) { $query->whereBetween('tanggal', [$this->start_date, $this->end_date]); } 
        elseif (!empty($this->start_date)) { $query->whereDate('tanggal', '>=', $this->start_date); } 
        elseif (!empty($this->end_date)) { $query->whereDate('tanggal', '<=', $this->end_date); }

        $dataTabel = $query->orderBy('tanggal', 'desc')->paginate(15);
        return view('livewire.transaksi.collection-index', compact('dataTabel'))->layout('layouts.app', ['header' => 'Data Pelunasan']);
    }
}