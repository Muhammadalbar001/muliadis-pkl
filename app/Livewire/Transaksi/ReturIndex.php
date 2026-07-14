<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi\Retur;
use Illuminate\Support\Facades\Auth;

class ReturIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filter_cabang = '';
    public $start_date = '';
    public $end_date = '';
    
    public $showModal = false;
    public $isEdit = false;
    public $retur_id;

    public $no_retur; 
    public $tgl_retur;
    public $cabang;
    public $sales_name;
    public $nama_pelanggan;
    public $total_grand;

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
            'no_retur' => 'required|string|max:255',
            'tgl_retur' => 'required|date',
            'cabang' => 'required|string',
            'sales_name' => 'required|string|max:255',
            'nama_pelanggan' => 'required|string|max:255',
            'total_grand' => 'required|numeric|min:0',
        ];
    }

    public function create()
    {
        $this->reset(['no_retur', 'tgl_retur', 'cabang', 'sales_name', 'nama_pelanggan', 'total_grand', 'retur_id']);
        $this->tgl_retur = date('Y-m-d');
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        if(Auth::user()->role === 'operator') {
            session()->flash('error', 'Akses ditolak! Operator tidak diizinkan mengedit data secara langsung.');
            return;
        }

        $data = Retur::findOrFail($id);
        $this->retur_id = $data->id;
        $this->no_retur = $data->no_retur; 
        $this->tgl_retur = $data->tgl_retur;
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

            $data = Retur::findOrFail($this->retur_id);
            $data->update([
                'no_retur' => $this->no_retur,
                'tgl_retur' => $this->tgl_retur,
                'cabang' => $this->cabang,
                'sales_name' => $this->sales_name,
                'nama_pelanggan' => $this->nama_pelanggan,
                'total_grand' => $this->total_grand,
            ]);
            session()->flash('message', 'Data Retur berhasil diperbarui!');
        } else {
            Retur::create([
                'no_retur' => $this->no_retur, 
                'tgl_retur' => $this->tgl_retur,
                'cabang' => $this->cabang,
                'sales_name' => $this->sales_name,
                'nama_pelanggan' => $this->nama_pelanggan,
                'total_grand' => $this->total_grand,
            ]);
            session()->flash('message', 'Data Retur baru berhasil ditambahkan secara manual!');
        }

        $this->showModal = false;
    }

    public function delete($id)
    {
        if(Auth::user()->role === 'operator') {
            session()->flash('error', 'Akses ditolak! Operator tidak diizinkan menghapus data secara langsung.');
            return;
        }

        Retur::findOrFail($id)->delete();
        session()->flash('message', 'Satu baris data retur berhasil dihapus secara permanen!');
    }

    public function render()
    {
        $query = Retur::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_name', 'like', '%'.$this->search.'%');
            });
        }

        if (!empty($this->filter_cabang)) {
            $query->where('cabang', $this->filter_cabang);
        }

        if (!empty($this->start_date) && !empty($this->end_date)) {
            $query->whereBetween('tgl_retur', [$this->start_date, $this->end_date]);
        } elseif (!empty($this->start_date)) {
            $query->whereDate('tgl_retur', '>=', $this->start_date);
        } elseif (!empty($this->end_date)) {
            $query->whereDate('tgl_retur', '<=', $this->end_date);
        }

        $retur = $query->orderBy('tgl_retur', 'desc')->paginate(15);

        return view('livewire.transaksi.retur-index', compact('retur'))
            ->layout('layouts.app', ['header' => 'Operasional - Data Retur']);
    }
}