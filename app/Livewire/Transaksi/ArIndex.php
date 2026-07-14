<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Keuangan\AccountReceivable;
use Illuminate\Support\Facades\Auth;

class ArIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filter_cabang = '';
    public $start_date = '';
    public $end_date = '';
    
    public $showModal = false;
    public $isEdit = false;
    public $ar_id;

    public $no_invoice; 
    public $tgl_penjualan; // Tanggal terjadinya piutang
    public $cabang;
    public $sales_name;
    public $nama_pelanggan;
    public $nilai;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterCabang() { $this->resetPage(); }
    public function updatingStartDate() { $this->resetPage(); }
    public function updatingEndDate() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['search', 'filter_cabang', 'start_date', 'end_date']);
        $this->resetPage();
    }

    protected function rules()
    {
        return [
            'no_invoice' => 'required|string|max:255',
            'tgl_penjualan' => 'required|date',
            'cabang' => 'required|string',
            'sales_name' => 'required|string|max:255',
            'nama_pelanggan' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0',
        ];
    }

    public function create()
    {
        $this->reset(['no_invoice', 'tgl_penjualan', 'cabang', 'sales_name', 'nama_pelanggan', 'nilai', 'ar_id']);
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

        $data = AccountReceivable::findOrFail($id);
        $this->ar_id = $data->id;
        $this->no_invoice = $data->no_invoice; 
        $this->tgl_penjualan = $data->tgl_penjualan;
        $this->cabang = $data->cabang;
        $this->sales_name = $data->sales_name;
        $this->nama_pelanggan = $data->nama_pelanggan;
        $this->nilai = $data->nilai;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        if ($this->isEdit) {
            if(Auth::user()->role === 'operator') return; 

            $data = AccountReceivable::findOrFail($this->ar_id);
            $data->update([
                'no_invoice' => $this->no_invoice,
                'tgl_penjualan' => $this->tgl_penjualan,
                'cabang' => $this->cabang,
                'sales_name' => $this->sales_name,
                'nama_pelanggan' => $this->nama_pelanggan,
                'nilai' => $this->nilai,
            ]);
            session()->flash('message', 'Data Piutang (AR) berhasil diperbarui!');
        } else {
            AccountReceivable::create([
                'no_invoice' => $this->no_invoice, 
                'tgl_penjualan' => $this->tgl_penjualan,
                'cabang' => $this->cabang,
                'sales_name' => $this->sales_name,
                'nama_pelanggan' => $this->nama_pelanggan,
                'nilai' => $this->nilai,
                'status' => 'Belum Lunas' // Status default piutang baru
            ]);
            session()->flash('message', 'Data Piutang (AR) baru berhasil ditambahkan secara manual!');
        }

        $this->showModal = false;
    }

    public function delete($id)
    {
        if(Auth::user()->role === 'operator') {
            session()->flash('error', 'Akses ditolak! Operator tidak diizinkan menghapus data secara langsung.');
            return;
        }

        AccountReceivable::findOrFail($id)->delete();
        session()->flash('message', 'Satu baris data piutang berhasil dihapus secara permanen!');
    }

    public function render()
    {
        $query = AccountReceivable::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('no_invoice', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_name', 'like', '%'.$this->search.'%');
            });
        }

        if (!empty($this->filter_cabang)) {
            $query->where('cabang', $this->filter_cabang);
        }

        if (!empty($this->start_date) && !empty($this->end_date)) {
            $query->whereBetween('tgl_penjualan', [$this->start_date, $this->end_date]);
        } elseif (!empty($this->start_date)) {
            $query->whereDate('tgl_penjualan', '>=', $this->start_date);
        } elseif (!empty($this->end_date)) {
            $query->whereDate('tgl_penjualan', '<=', $this->end_date);
        }

        $ar = $query->orderBy('tgl_penjualan', 'desc')->paginate(15);

        return view('livewire.transaksi.ar-index', compact('ar'))
            ->layout('layouts.app', ['header' => 'Operasional - Data Piutang']);
    }
}