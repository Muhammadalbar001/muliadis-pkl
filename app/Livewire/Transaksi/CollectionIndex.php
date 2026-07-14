<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Keuangan\Collection;
use Illuminate\Support\Facades\Auth;

class CollectionIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filter_cabang = '';
    public $start_date = '';
    public $end_date = '';
    
    public $showModal = false;
    public $isEdit = false;
    public $collection_id;

    public $sing_no; 
    public $tanggal;
    public $cabang;
    public $sales_name;
    public $nama_pelanggan;
    public $receive_amount;

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
            'sing_no' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'cabang' => 'required|string',
            'sales_name' => 'required|string|max:255',
            'nama_pelanggan' => 'required|string|max:255',
            'receive_amount' => 'required|numeric|min:0',
        ];
    }

    public function create()
    {
        $this->reset(['sing_no', 'tanggal', 'cabang', 'sales_name', 'nama_pelanggan', 'receive_amount', 'collection_id']);
        $this->tanggal = date('Y-m-d');
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        if(Auth::user()->role === 'operator') {
            session()->flash('error', 'Akses ditolak! Operator tidak diizinkan mengedit data secara langsung.');
            return;
        }

        $data = Collection::findOrFail($id);
        $this->collection_id = $data->id;
        $this->sing_no = $data->sing_no; 
        $this->tanggal = $data->tanggal;
        $this->cabang = $data->cabang;
        $this->sales_name = $data->sales_name;
        $this->nama_pelanggan = $data->nama_pelanggan;
        $this->receive_amount = $data->receive_amount;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        if ($this->isEdit) {
            if(Auth::user()->role === 'operator') return; 

            $data = Collection::findOrFail($this->collection_id);
            $data->update([
                'sing_no' => $this->sing_no,
                'tanggal' => $this->tanggal,
                'cabang' => $this->cabang,
                'sales_name' => $this->sales_name,
                'nama_pelanggan' => $this->nama_pelanggan,
                'receive_amount' => $this->receive_amount,
            ]);
            session()->flash('message', 'Data Pelunasan (Collection) berhasil diperbarui!');
        } else {
            Collection::create([
                'sing_no' => $this->sing_no, 
                'tanggal' => $this->tanggal,
                'cabang' => $this->cabang,
                'sales_name' => $this->sales_name,
                'nama_pelanggan' => $this->nama_pelanggan,
                'receive_amount' => $this->receive_amount,
            ]);
            session()->flash('message', 'Data Pelunasan baru berhasil ditambahkan secara manual!');
        }

        $this->showModal = false;
    }

    public function delete($id)
    {
        if(Auth::user()->role === 'operator') {
            session()->flash('error', 'Akses ditolak! Operator tidak diizinkan menghapus data secara langsung.');
            return;
        }

        Collection::findOrFail($id)->delete();
        session()->flash('message', 'Satu baris data pelunasan berhasil dihapus secara permanen!');
    }

    public function render()
    {
        $query = Collection::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('sing_no', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_name', 'like', '%'.$this->search.'%');
            });
        }

        if (!empty($this->filter_cabang)) {
            $query->where('cabang', $this->filter_cabang);
        }

        if (!empty($this->start_date) && !empty($this->end_date)) {
            $query->whereBetween('tanggal', [$this->start_date, $this->end_date]);
        } elseif (!empty($this->start_date)) {
            $query->whereDate('tanggal', '>=', $this->start_date);
        } elseif (!empty($this->end_date)) {
            $query->whereDate('tanggal', '<=', $this->end_date);
        }

        $collection = $query->orderBy('tanggal', 'desc')->paginate(15);

        return view('livewire.transaksi.collection-index', compact('collection'))
            ->layout('layouts.app', ['header' => 'Operasional - Data Pelunasan']);
    }
}