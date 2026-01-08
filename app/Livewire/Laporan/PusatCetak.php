<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use App\Models\Master\Produk;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Transaksi\Retur;
use App\Models\Transaksi\Collection;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PusatCetak extends Component
{
    // --- FILTER GLOBAL SALES ---
    public $bulan;
    public $tahun;
    public $minNominal = 50000;

    // --- FILTER KOMPARASI ---
    public $tglMulaiKomparasi;
    public $tglSelesaiKomparasi;
    public $selectedCabangKomparasi = '';

    // --- DATA OPSI (List untuk Dropdown) ---
    public $salesOptions = [];
    
    public $supplierOptionsStok = [];
    public $productOptionsStok = [];
    
    public $supplierOptionsProfit = [];
    public $productOptionsProfit = [];

    // --- SALES FILTER ---
    public $selectedCabangSales = 'Semua Cabang'; 
    public $selectedSalesIds = []; // Multi Select ID

    // --- STOK FILTER ---
    public $selectedCabangStok;
    public $selectedSupplierStok = []; // Multi Select Supplier
    public $selectedProductStok = [];  // Multi Select Produk

    // --- PROFIT FILTER ---
    public $selectedCabangProfit;
    public $selectedSupplierProfit = []; // Multi Select Supplier
    public $selectedProductProfit = [];  // Multi Select Produk

    public function mount()
    {
        $this->bulan = date('m');
        $this->tahun = date('Y');
        
        // Default Tanggal Komparasi
        $this->tglMulaiKomparasi = date('Y-m-01');
        $this->tglSelesaiKomparasi = date('Y-m-d');

        // Default Cabang
        $firstBranch = Produk::whereNotNull('cabang')->where('cabang', '!=', '')->orderBy('cabang')->first();
        $defaultCabang = $firstBranch->cabang ?? '';

        $this->selectedCabangStok = $defaultCabang;
        $this->selectedCabangProfit = $defaultCabang;

        // Load Initial Data
        $this->loadSalesOptions();
        $this->loadSupplierStok();
        $this->loadProductStok();
        $this->loadSupplierProfit();
        $this->loadProductProfit();
    }

    // ========================================================================
    // LOGIC LOAD DATA (DEPENDENCY & UPDATE)
    // ========================================================================

    // --- SALES ---
    public function updatedSelectedCabangSales()
    {
        $this->selectedSalesIds = [];
        $this->loadSalesOptions();
    }

    public function loadSalesOptions()
    {
        $query = Sales::where('status', 'Active');
        if ($this->selectedCabangSales !== 'Semua Cabang' && !empty($this->selectedCabangSales)) {
            $query->where('city', $this->selectedCabangSales);
        }
        $this->salesOptions = $query->orderBy('sales_name')->get(['id', 'sales_name', 'sales_code'])->toArray();
    }

    // --- STOK ---
    public function updatedSelectedCabangStok()
    {
        $this->selectedSupplierStok = [];
        $this->selectedProductStok = [];
        $this->loadSupplierStok();
        $this->loadProductStok();
    }

    public function updatedSelectedSupplierStok()
    {
        $this->selectedProductStok = []; // Reset produk saat supplier berubah
        $this->loadProductStok(); // Load ulang produk sesuai supplier baru
    }

    public function loadSupplierStok()
    {
        $this->supplierOptionsStok = Produk::select('supplier')
            ->where('cabang', $this->selectedCabangStok)
            ->distinct()->orderBy('supplier')->pluck('supplier')->toArray();
    }

    public function loadProductStok()
    {
        $query = Produk::where('cabang', $this->selectedCabangStok);

        if (!empty($this->selectedSupplierStok)) {
            $query->whereIn('supplier', $this->selectedSupplierStok);
        }

        // Ambil data untuk dropdown (SKU & Nama)
        $this->productOptionsStok = $query->orderBy('name_item')
            ->select('sku', 'name_item')
            ->limit(1000) // Limit agar performa terjaga
            ->get()->toArray();
    }

    // --- PROFIT ---
    public function updatedSelectedCabangProfit()
    {
        $this->selectedSupplierProfit = [];
        $this->selectedProductProfit = [];
        $this->loadSupplierProfit();
        $this->loadProductProfit();
    }

    public function updatedSelectedSupplierProfit()
    {
        $this->selectedProductProfit = [];
        $this->loadProductProfit();
    }

    public function loadSupplierProfit()
    {
        $this->supplierOptionsProfit = Produk::select('supplier')
            ->where('cabang', $this->selectedCabangProfit)
            ->distinct()->orderBy('supplier')->pluck('supplier')->toArray();
    }

    public function loadProductProfit()
    {
        $query = Produk::where('cabang', $this->selectedCabangProfit);

        if (!empty($this->selectedSupplierProfit)) {
            $query->whereIn('supplier', $this->selectedSupplierProfit);
        }

        $this->productOptionsProfit = $query->orderBy('name_item')
            ->select('sku', 'name_item')
            ->limit(1000)
            ->get()->toArray();
    }


    // ========================================================================
    // 1. CETAK KINERJA SALES
    // ========================================================================
    public function cetakSales($jenis)
    {
        $querySales = Sales::where('status', 'Active');

        // Filter: ID Sales atau Cabang
        if (!empty($this->selectedSalesIds)) {
            $querySales->whereIn('id', $this->selectedSalesIds);
        } elseif ($this->selectedCabangSales !== 'Semua Cabang') {
            $querySales->where('city', $this->selectedCabangSales);
        }

        $salesman = $querySales->orderBy('sales_name')->get();

        if ($salesman->isEmpty()) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Data Salesman tidak ditemukan.']);
            return;
        }

        // Setup Periode
        $dateObj = Carbon::createFromDate($this->tahun, $this->bulan, 1);
        $start = $dateObj->startOfMonth()->format('Y-m-d');
        $end = $dateObj->endOfMonth()->format('Y-m-d');

        // --- QUERY DATA PENDUKUNG ---
        $targets = SalesTarget::where('year', $this->tahun)->where('month', (int)$this->bulan)->get()->keyBy('sales_id');
        
        $salesStats = DB::table('penjualans')
            ->select('sales_name', 'trans_no', 'kode_pelanggan', DB::raw("SUM(total_grand) as total_per_nota"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name', 'trans_no', 'kode_pelanggan')->get();

        $arStats = AccountReceivable::selectRaw("sales_name, SUM(total_nilai) as total_ar, SUM(CASE WHEN umur_piutang <= 30 THEN total_nilai ELSE 0 END) as ar_lancar, SUM(CASE WHEN umur_piutang > 30 THEN total_nilai ELSE 0 END) as ar_macet")
            ->groupBy('sales_name')->get()->keyBy('sales_name');

        $topSuppliers = Penjualan::select('supplier', DB::raw("SUM(total_grand) as val"))
            ->whereBetween('tgl_penjualan', [$start, $end])->whereNotNull('supplier')
            ->groupBy('supplier')->orderByDesc('val')->pluck('supplier');
            
        $rawPivot = Penjualan::selectRaw("sales_name, supplier, SUM(total_grand) as total")
            ->whereBetween('tgl_penjualan', [$start, $end])->whereIn('supplier', $topSuppliers)
            ->groupBy('sales_name', 'supplier')->get();
            
        $matrixSupplier = [];
        foreach ($rawPivot as $p) { $matrixSupplier[$p->sales_name][$p->supplier] = $p->total; }

        // --- MAPPING DATA ---
        $laporan = $salesman->map(function($sales) use ($targets, $salesStats, $arStats, $matrixSupplier) {
            $name = $sales->sales_name;
            $t = $targets->get($sales->id);
            $ar = $arStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));
            $myStats = $salesStats->where('sales_name', $name);
            
            $realIMS = $myStats->sum('total_per_nota');
            $realOA = $myStats->unique('kode_pelanggan')->count();
            $realEC = $myStats->filter(fn($row) => $row->total_per_nota >= $this->minNominal)->unique('trans_no')->count();
            $targetIMS = $t ? (float)$t->target_ims : 0;
            $arTotal = $ar ? (float)$ar->total_ar : 0;
            $arMacet = $ar ? (float)$ar->ar_macet : 0;
            $sumSupplied = isset($matrixSupplier[$name]) ? array_sum($matrixSupplier[$name]) : 0;

            return [
                'kode' => $sales->sales_code,
                'nama' => $name,
                'cabang' => $sales->city,
                'target_ims' => $targetIMS,
                'real_ims' => $realIMS,
                'persen_ims' => $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0,
                'gap' => $realIMS - $targetIMS,
                'ar_total' => $arTotal,
                'ar_lancar' => $ar ? (float)$ar->ar_lancar : 0,
                'ar_macet' => $arMacet,
                'ar_persen_macet' => $arTotal > 0 ? ($arMacet / $arTotal) * 100 : 0,
                'real_oa' => $realOA,
                'ec' => $realEC,
                'total_supplier_val' => $sumSupplied,
            ];
        });

        // Generate PDF
        $periodeStr = $dateObj->translatedFormat('F Y');
        $dataView = ['periode' => $periodeStr, 'cetak_oleh' => auth()->user()->name ?? 'System', 'tgl_cetak' => now()->format('d/m/Y H:i')];

        switch ($jenis) {
            case 'penjualan':
                $view = 'livewire.laporan.exports.kinerja-penjualan-pdf';
                $fileName = 'Kinerja_Penjualan_'.$periodeStr.'.pdf';
                $dataView['data'] = $laporan->sortByDesc('persen_ims')->values();
                break;
            case 'ar':
                $view = 'livewire.laporan.exports.kinerja-ar-pdf';
                $fileName = 'Monitoring_Kredit_'.$periodeStr.'.pdf';
                $dataView['data'] = $laporan->sortByDesc('ar_total')->values();
                break;
            case 'supplier':
                $view = 'livewire.laporan.exports.kinerja-supplier-pdf';
                $fileName = 'Penjualan_Supplier_'.$periodeStr.'.pdf';
                $dataView['data'] = $laporan->sortByDesc('total_supplier_val')->values();
                $dataView['topSuppliers'] = $topSuppliers;
                $dataView['matrixSupplier'] = $matrixSupplier;
                break;
            case 'produktifitas':
                $view = 'livewire.laporan.exports.kinerja-produktivitas-pdf';
                $fileName = 'Produktivitas_'.$periodeStr.'.pdf';
                $dataView['data'] = $laporan->sortByDesc('ec')->values();
                $dataView['minNominal'] = $this->minNominal;
                break;
        }

        $pdf = Pdf::loadView($view, $dataView)->setPaper('a4', 'landscape');
        return response()->streamDownload(function () use ($pdf) { echo $pdf->output(); }, $fileName);
    }

    // ========================================================================
    // 2. CETAK VALUASI STOK
    // ========================================================================
    public function cetakStok()
    {
        $query = Produk::query()->where('cabang', $this->selectedCabangStok);

        $label = 'SEMUA PEMASOK';
        if (!empty($this->selectedSupplierStok)) {
            $query->whereIn('supplier', $this->selectedSupplierStok);
            $label = count($this->selectedSupplierStok) . ' Supplier Terpilih';
        }

        if (!empty($this->selectedProductStok)) {
            $query->whereIn('sku', $this->selectedProductStok);
        }

        $data = $query->orderBy('supplier')->orderBy('name_item')->get();

        if ($data->isEmpty()) { 
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Data Stok kosong.']);
            return; 
        }

        $totalQty = 0; $totalAset = 0;
        foreach($data as $item) {
            $stok = (float)$item->stok;
            $avg = (float)$item->avg;
            $totalQty += $stok;
            $totalAset += ($stok * $avg);
        }

        $summary = [
            'cabang' => $this->selectedCabangStok,
            'supplier' => $label,
            'total_item' => $data->count(),
            'total_qty' => $totalQty,
            'total_aset' => $totalAset
        ];

        $pdf = Pdf::loadView('livewire.pimpinan.exports.stock-valuation-pdf', [
            'data' => $data,
            'summary' => $summary,
            'tanggal_cetak' => now()->format('d F Y H:i'),
            'user' => auth()->user()->name ?? 'System'
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) { echo $pdf->output(); }, 'Valuasi_Stok.pdf');
    }

    // ========================================================================
    // 3. CETAK PROFIT
    // ========================================================================
    public function cetakProfit()
    {
        $query = Produk::query()->where('cabang', $this->selectedCabangProfit);

        $labelSupplier = 'SEMUA PEMASOK';
        if (!empty($this->selectedSupplierProfit)) {
            $query->whereIn('supplier', $this->selectedSupplierProfit);
            $labelSupplier = count($this->selectedSupplierProfit) . ' Supplier Terpilih';
        }

        if (!empty($this->selectedProductProfit)) {
            $query->whereIn('sku', $this->selectedProductProfit);
        }

        $products = $query->orderBy('supplier')->orderBy('name_item')->get()
            ->map(function ($item) {
                $modalDasar = (float) $item->avg > 0 ? (float) $item->avg : (float) $item->buy;
                $rawPpn = $item->ppn; 
                $persenPpn = (is_numeric($rawPpn) && $rawPpn > 0) ? (float) $rawPpn : (strtoupper(trim($rawPpn)) === 'Y' ? 11 : 0);
                $hppFinal = $modalDasar + ($modalDasar * ($persenPpn / 100));
                $hargaJual = (float) $item->fix; 
                $marginRp = $hargaJual - $hppFinal;
                $marginPersen = ($hppFinal > 0) ? ($marginRp / $hppFinal) * 100 : 0;

                return [
                    'last_supplier' => $item->supplier,
                    'name_item' => $item->name_item,
                    'sku' => $item->sku,
                    'stock' => $item->stok,
                    'avg_ppn' => $hppFinal,
                    'harga_jual' => $hargaJual,
                    'margin_rp' => $marginRp,
                    'margin_persen' => $marginPersen,
                ];
            });

        if ($products->isEmpty()) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Data kosong.']);
            return;
        }

        $pdf = Pdf::loadView('livewire.pimpinan.exports.profit-pdf', [
            'cabang' => $this->selectedCabangProfit,
            'products' => $products,
            'suppliers' => $labelSupplier,
            'tanggal_cetak' => now()->format('d F Y H:i'),
            'user_pencetak' => auth()->user()->name ?? 'System'
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) { echo $pdf->output(); }, 'Laba_Rugi.pdf');
    }

    // ========================================================================
    // 4. CETAK KOMPARASI
    // ========================================================================
    public function cetakKomparasi($jenis)
    {
        // Validasi
        if(!$this->tglMulaiKomparasi || !$this->tglSelesaiKomparasi){
            $this->tglMulaiKomparasi = date('Y-m-01');
            $this->tglSelesaiKomparasi = date('Y-m-d');
        }

        $start = Carbon::parse($this->tglMulaiKomparasi);
        $end = Carbon::parse($this->tglSelesaiKomparasi);
        $period = CarbonPeriod::create($start, $end);

        $dataHarian = [];
        $summary = ['total_A' => 0, 'total_B' => 0, 'label_A' => '', 'label_B' => ''];
        $filterCabang = !empty($this->selectedCabangKomparasi) ? $this->selectedCabangKomparasi : null;

        foreach ($period as $date) {
            $currentDate = $date->format('Y-m-d');
            
            if ($jenis == 'omzet') {
                $summary['label_A'] = 'Penjualan'; $summary['label_B'] = 'Retur';
                $judul = 'Komparasi Omzet vs Retur';
                
                $valA = Penjualan::whereDate('tgl_penjualan', $currentDate)
                    ->when($filterCabang, fn($q) => $q->where('cabang', $filterCabang))->sum('total_grand');
                $valB = Retur::whereDate('tgl_retur', $currentDate)
                    ->when($filterCabang, fn($q) => $q->where('cabang', $filterCabang))->sum('total_grand');
            } else {
                $summary['label_A'] = 'Piutang (AR)'; $summary['label_B'] = 'Pelunasan';
                $judul = 'Komparasi Piutang vs Pelunasan';

                $valA = AccountReceivable::whereDate('tgl_penjualan', $currentDate) 
                    ->when($filterCabang, fn($q) => $q->where('cabang', $filterCabang))->sum('total_nilai');
                
                $valB = DB::table('collections')->whereDate('tanggal', $currentDate)
                    ->when($filterCabang, fn($q) => $q->where('cabang', $filterCabang))->sum('receive_amount'); 
            }

            $gap = $valA - $valB;
            $persen = $valA > 0 ? ($valB / $valA) * 100 : 0; 

            $dataHarian[] = [
                'tanggal' => $date->day, 
                'full_date' => $currentDate,
                'val_A' => $valA, 'val_B' => $valB,
                'gap' => $gap, 'persen' => $persen
            ];
            $summary['total_A'] += $valA;
            $summary['total_B'] += $valB;
        }

        $periodeStr = $start->translatedFormat('d M Y') . ' s/d ' . $end->translatedFormat('d M Y');

        $pdf = Pdf::loadView('livewire.laporan.exports.komparasi-pdf', [
            'data' => $dataHarian,
            'summary' => $summary,
            'judul' => $judul,
            'periode' => $periodeStr,
            'cabang' => $this->selectedCabangKomparasi ?: 'SEMUA CABANG',
            'cetak_oleh' => auth()->user()->name ?? 'System',
            'tgl_cetak' => now()->format('d/m/Y H:i'),
            'jenis' => $jenis
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) { echo $pdf->output(); }, 'Laporan_'.$judul.'.pdf');
    }

    public function render()
    {
        $cabangOptions = Produk::select('cabang')->distinct()->whereNotNull('cabang')->where('cabang','!=','')->orderBy('cabang')->pluck('cabang');
        
        return view('livewire.laporan.pusat-cetak', [
            'cabangOptions' => $cabangOptions
        ])->layout('layouts.app', ['header' => 'Pusat Cetak']);
    }
}