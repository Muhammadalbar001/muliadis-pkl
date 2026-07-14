<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi\Penjualan;
use Illuminate\Support\Facades\Auth;

class PenjualanIndex extends Component
{
    use WithPagination;

    // Properti Pencarian & Filter
    public $search = '';
    public $filter_cabang = '';
    public $start_date = '';
    public $end_date = '';
    
    // Properti Modal CRUD
    public $showModal = false;
    public $isEdit = false;
    public $penjualan_id;

    // Field Input Manual (Tipe A)
    public $no_penjualan; 
    public $tgl_penjualan;
    public $cabang;
    public $sales_name;
    public $nama_pelanggan;
    public $total_grand;

    // Reset paginasi saat filter diubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterCabang() { $this->resetPage(); }
    public function updatingStartDate() { $this->resetPage(); }
    public function updatingEndDate() { $this->resetPage(); }

    // Fungsi Mengosongkan Semua Filter
    public function resetFilters()
    {
        $this->reset(['search', 'filter_cabang', 'start_date', 'end_date']);
        $this->resetPage();
    }

    protected function rules()
    {
        return [
            'no_penjualan' => 'required|string|max:255',
            'tgl_penjualan' => 'required|date',
            'cabang' => 'required|string',
            'sales_name' => 'required|string|max:255',
            'nama_pelanggan' => 'required|string|max:255',
            'total_grand' => 'required|numeric|min:0',
        ];
    }

    public function create()
    {
        $this->reset(['no_penjualan', 'tgl_penjualan', 'cabang', 'sales_name', 'nama_pelanggan', 'total_grand', 'penjualan_id']);
        $this->tgl_penjualan = date('Y-m-d');
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        if(Auth::user()->role === 'operator') {
            session()->flash('error', 'Akses ditolak! Operator tidak diizinkan mengedit data secara langsung.');
            return;
        }

        $data = Penjualan::findOrFail($id);
        $this->penjualan_id = $data->id;
        $this->no_penjualan = $data->trans_no; 
        $this->tgl_penjualan = $data->tgl_penjualan;
        $this->cabang = $data->cabang;
        $this->sales_name = $data->sales_name;
        $this->nama_pelanggan = $data->nama_pelanggan;
        $this->total_grand = $data->total_grand;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        if ($this->isEdit) {
            if(Auth::user()->role === 'operator') return; 

            $data = Penjualan::findOrFail($this->penjualan_id);
            $data->update([
                'trans_no' => $this->no_penjualan,
                'tgl_penjualan' => $this->tgl_penjualan,
                'cabang' => $this->cabang,
                'sales_name' => $this->sales_name,
                'nama_pelanggan' => $this->nama_pelanggan,
                'total_grand' => $this->total_grand,
            ]);
            session()->flash('message', 'Data Penjualan berhasil diperbarui!');
        } else {
            Penjualan::create([
                'trans_no' => $this->no_penjualan, 
                'tgl_penjualan' => $this->tgl_penjualan,
                'cabang' => $this->cabang,
                'sales_name' => $this->sales_name,
                'nama_pelanggan' => $this->nama_pelanggan,
                'total_grand' => $this->total_grand,
            ]);
            session()->flash('message', 'Data Penjualan baru berhasil ditambahkan secara manual!');
        }

        $this->showModal = false;
    }

    public function delete($id)
    {
        if(Auth::user()->role === 'operator') {
            session()->flash('error', 'Akses ditolak! Operator tidak diizinkan menghapus data secara langsung.');
            return;
        }

        Penjualan::findOrFail($id)->delete();
        session()->flash('message', 'Satu baris data penjualan berhasil dihapus secara permanen!');
    }

    public function render()
    {
        $query = Penjualan::query();

        // 1. Filter Pencarian Teks
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('trans_no', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_name', 'like', '%'.$this->search.'%');
            });
        }

        // 2. Filter Cabang
        if (!empty($this->filter_cabang)) {
            $query->where('cabang', $this->filter_cabang);
        }

        // 3. Filter Rentang Tanggal
        if (!empty($this->start_date) && !empty($this->end_date)) {
            $query->whereBetween('tgl_penjualan', [$this->start_date, $this->end_date]);
        } elseif (!empty($this->start_date)) {
            $query->whereDate('tgl_penjualan', '>=', $this->start_date);
        } elseif (!empty($this->end_date)) {
            $query->whereDate('tgl_penjualan', '<=', $this->end_date);
        }

        $penjualan = $query->orderBy('tgl_penjualan', 'desc')->paginate(15);

        return view('livewire.transaksi.penjualan-index', compact('penjualan'))
            ->layout('layouts.app', ['header' => 'Operasional - Data Penjualan']);
    }
}