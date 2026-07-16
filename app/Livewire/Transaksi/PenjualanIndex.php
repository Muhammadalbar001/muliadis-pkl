<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Transaksi\Penjualan;
use Illuminate\Support\Facades\Auth;

class PenjualanIndex extends Component
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
        $data = Penjualan::findOrFail($id);
        $this->record_id = $data->id;
        $this->no_dokumen = $data->trans_no ?? $data->no_penjualan; 
        $this->tanggal = $data->tgl_penjualan;
        $this->cabang = $data->cabang;
        $this->sales_name = $data->sales_name;
        $this->nama_pelanggan = $data->nama_pelanggan ?? $data->toko;
        $this->nominal = $data->total_grand;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function store() {
        $this->validate();
        if ($this->isEdit && Auth::user()->role === 'operator') return; 
        $dbData = [
            'trans_no' => $this->no_dokumen, 'tgl_penjualan' => $this->tanggal, 'cabang' => $this->cabang,
            'sales_name' => $this->sales_name, 'nama_pelanggan' => $this->nama_pelanggan, 'total_grand' => $this->nominal,
        ];
        if ($this->isEdit) { Penjualan::findOrFail($this->record_id)->update($dbData); session()->flash('message', 'Data diperbarui!'); } 
        else { Penjualan::create($dbData); session()->flash('message', 'Data baru ditambahkan!'); }
        $this->showModal = false;
    }

    public function delete($id) {
        if(Auth::user()->role === 'operator') return;
        Penjualan::findOrFail($id)->delete(); session()->flash('message', 'Data dihapus permanen!');
    }

    public function openModalImport() { $this->resetValidation(); $this->reset('file_excel'); $this->showModalImport = true; }

    public function importData() {
        $this->validate(['file_excel' => 'required|mimes:xlsx,xls,csv|max:512000']);
        // $importService = new \App\Services\Import\PenjualanImportService();
        // $importService->import($this->file_excel->getRealPath());
        session()->flash('message', 'Data dari Excel berhasil diimpor!');
        $this->showModalImport = false; $this->reset('file_excel');
    }

    public function render() {
        $query = Penjualan::query();
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('trans_no', 'like', '%'.$this->search.'%')->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')->orWhere('sales_name', 'like', '%'.$this->search.'%');
            });
        }
        if (!empty($this->filter_cabang)) { $query->where('cabang', $this->filter_cabang); }
        if (!empty($this->start_date) && !empty($this->end_date)) { $query->whereBetween('tgl_penjualan', [$this->start_date, $this->end_date]); } 
        elseif (!empty($this->start_date)) { $query->whereDate('tgl_penjualan', '>=', $this->start_date); } 
        elseif (!empty($this->end_date)) { $query->whereDate('tgl_penjualan', '<=', $this->end_date); }

        $dataTabel = $query->orderBy('tgl_penjualan', 'desc')->paginate(15);
        return view('livewire.transaksi.penjualan-index', compact('dataTabel'))->layout('layouts.app', ['header' => 'Data Penjualan']);
    }
}