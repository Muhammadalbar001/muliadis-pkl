<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use App\Models\Master\Produk;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Transaksi\Retur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
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

    // --- DATA OPSI ---
    public $salesOptions = [];
    public $supplierOptionsStok = [];
    public $productOptionsStok = [];
    public $supplierOptionsProfit = [];
    public $productOptionsProfit = [];

    // --- SELECTIONS ---
    public $selectedCabangSales = 'Semua Cabang'; 
    public $selectedSalesIds = []; 
    public $selectedCabangStok;
    public $selectedSupplierStok = []; 
    public $selectedProductStok = [];  
    public $selectedCabangProfit;
    public $selectedSupplierProfit = []; 
    public $selectedProductProfit = [];  

    public function mount()
    {
        $this->bulan = date('m');
        $this->tahun = date('Y');
        $this->tglMulaiKomparasi = date('Y-m-01');
        $this->tglSelesaiKomparasi = date('Y-m-d');

        $firstBranch = Produk::whereNotNull('cabang')->where('cabang', '!=', '')->orderBy('cabang')->first();
        $defaultCabang = $firstBranch->cabang ?? '';

        $this->selectedCabangStok = $defaultCabang;
        $this->selectedCabangProfit = $defaultCabang;

        $this->loadSalesOptions();
        $this->loadSupplierStok();
        $this->loadProductStok();
        $this->loadSupplierProfit();
        $this->loadProductProfit();
    }

    public function updatedSelectedCabangSales() { $this->selectedSalesIds = []; $this->loadSalesOptions(); }
    public function loadSalesOptions()
    {
        $query = Sales::where('status', 'Active');
        if ($this->selectedCabangSales !== 'Semua Cabang' && !empty($this->selectedCabangSales)) {
            $query->where('city', $this->selectedCabangSales);
        }
        $this->salesOptions = $query->orderBy('sales_name')->get(['id', 'sales_name', 'sales_code'])->toArray();
    }

    public function updatedSelectedCabangStok() { $this->selectedSupplierStok = []; $this->selectedProductStok = []; $this->loadSupplierStok(); $this->loadProductStok(); }
    public function updatedSelectedSupplierStok() { $this->selectedProductStok = []; $this->loadProductStok(); }
    public function loadSupplierStok() { $this->supplierOptionsStok = Produk::select('supplier')->where('cabang', $this->selectedCabangStok)->distinct()->orderBy('supplier')->pluck('supplier')->toArray(); }
    public function loadProductStok() { $query = Produk::where('cabang', $this->selectedCabangStok); if (!empty($this->selectedSupplierStok)) { $query->whereIn('supplier', $this->selectedSupplierStok); } $this->productOptionsStok = $query->orderBy('name_item')->select('sku', 'name_item')->limit(1000)->get()->toArray(); }

    public function updatedSelectedCabangProfit() { $this->selectedSupplierProfit = []; $this->selectedProductProfit = []; $this->loadSupplierProfit(); $this->loadProductProfit(); }
    public function updatedSelectedSupplierProfit() { $this->selectedProductProfit = []; $this->loadProductProfit(); }
    public function loadSupplierProfit() { $this->supplierOptionsProfit = Produk::select('supplier')->where('cabang', $this->selectedCabangProfit)->distinct()->orderBy('supplier')->pluck('supplier')->toArray(); }
    public function loadProductProfit() { $query = Produk::where('cabang', $this->selectedCabangProfit); if (!empty($this->selectedSupplierProfit)) { $query->whereIn('supplier', $this->selectedSupplierProfit); } $this->productOptionsProfit = $query->orderBy('name_item')->select('sku', 'name_item')->limit(1000)->get()->toArray(); }

    // ========================================================================
    // CENTRALIZED PRINTING ENGINE FOR 8 SALES & ANALYTICS REPORTS
    // ========================================================================
    public function cetakSales($jenis)
    {
        $querySales = Sales::where('status', 'Active');
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

        $dateObj = Carbon::createFromDate($this->tahun, $this->bulan, 1);
        $start = $dateObj->startOfMonth()->format('Y-m-d');
        $end = $dateObj->endOfMonth()->format('Y-m-d');
        $periodeStr = $dateObj->translatedFormat('F Y');

        $dataView = [
            'periode' => $periodeStr,
            'cetak_oleh' => auth()->user()->name ?? 'Pimpinan Eksekutif',
            'tgl_cetak' => now()->translatedFormat('d F Y H:i') . ' WITA'
        ];

        switch ($jenis) {
            case 'penjualan':
                $targets = SalesTarget::where('year', $this->tahun)->where('month', (int)$this->bulan)->get()->keyBy('sales_id');
                $salesStats = DB::table('penjualans')->select('sales_name', DB::raw("SUM(total_grand) as total_ims"))->whereBetween('tgl_penjualan', [$start, $end])->groupBy('sales_name')->get()->keyBy('sales_name');
                
                $dataView['data'] = $salesman->map(function($sales) use ($targets, $salesStats) {
                    $t = $targets->get($sales->id);
                    $r = $salesStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($sales->sales_name));
                    $targetIMS = $t ? (float)$t->target_ims : 0;
                    $realIMS = $r ? (float)$r->total_ims : 0;
                    return [
                        'kode' => $sales->sales_code, 'nama' => $sales->sales_name, 'cabang' => $sales->city,
                        'target_ims' => $targetIMS, 'real_ims' => $realIMS,
                        'persen_ims' => $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0, 'gap' => $realIMS - $targetIMS,
                    ];
                })->sortByDesc('persen_ims')->values();
                
                $view = 'livewire.laporan.exports.kinerja-penjualan-pdf';
                $fileName = 'Laporan_Kinerja_Penjualan_'.$this->tahun.'_'.$this->bulan.'.pdf';
                break;

            case 'ar':
                $arStats = AccountReceivable::selectRaw("sales_name, SUM(nilai) as total_ar, SUM(CASE WHEN umur_piutang > 30 THEN nilai ELSE 0 END) as ar_macet")->where('status', '!=', 'Lunas')->groupBy('sales_name')->get()->keyBy('sales_name');
                
                $dataView['data'] = $salesman->map(function($sales) use ($arStats) {
                    $ar = $arStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($sales->sales_name));
                    $arTotal = $ar ? (float)$ar->total_ar : 0;
                    $arMacet = $ar ? (float)$ar->ar_macet : 0;
                    return [
                        'kode' => $sales->sales_code, 'nama' => $sales->sales_name, 'cabang' => $sales->city,
                        'ar_total' => $arTotal, 'ar_macet' => $arMacet, 'ar_persen_macet' => $arTotal > 0 ? ($arMacet / $arTotal) * 100 : 0,
                    ];
                })->sortByDesc('ar_total')->values();

                $view = 'livewire.laporan.exports.kinerja-ar-pdf';
                $fileName = 'Laporan_Monitoring_Kredit_'.$this->tahun.'_'.$this->bulan.'.pdf';
                break;

            case 'produktifitas':
                $subQuery = DB::table('penjualans')->select('sales_name', 'trans_no', 'kode_pelanggan', DB::raw("SUM(total_grand) as total_per_nota"))->whereBetween('tgl_penjualan', [$start, $end])->groupBy('sales_name', 'trans_no', 'kode_pelanggan');
                $salesStats = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))->mergeBindings($subQuery)->selectRaw("sales_name, COUNT(DISTINCT kode_pelanggan) as total_oa, COUNT(DISTINCT CASE WHEN total_per_nota >= {$this->minNominal} THEN trans_no END) as total_ec")->groupBy('sales_name')->get()->keyBy('sales_name');
                
                $dataView['data'] = $salesman->map(function($sales) use ($salesStats) {
                    $stat = $salesStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($sales->sales_name));
                    return [
                        'kode' => $sales->sales_code, 'nama' => $sales->sales_name,
                        'real_oa' => $stat ? (int)$stat->total_oa : 0, 'ec' => $stat ? (int)$stat->total_ec : 0,
                    ];
                })->sortByDesc('ec')->values();
                $dataView['minNominal'] = $this->minNominal;

                $view = 'livewire.laporan.exports.kinerja-produktivitas-pdf';
                $fileName = 'Laporan_Produktivitas_Sales_'.$this->tahun.'_'.$this->bulan.'.pdf';
                break;

            case 'supplier':
                $topSuppliers = Penjualan::select('supplier', DB::raw("SUM(total_grand) as val"))->whereBetween('tgl_penjualan', [$start, $end])->whereNotNull('supplier')->groupBy('supplier')->orderByDesc('val')->limit(10)->pluck('supplier');
                $rawPivot = Penjualan::selectRaw("sales_name, supplier, SUM(total_grand) as total")->whereBetween('tgl_penjualan', [$start, $end])->whereIn('supplier', $topSuppliers)->groupBy('sales_name', 'supplier')->get();
                $matrixSupplier = [];
                foreach ($rawPivot as $p) { $matrixSupplier[$p->sales_name][$p->supplier] = $p->total; }

                $dataView['data'] = $salesman->map(function($sales) use ($matrixSupplier) {
                    $name = $sales->sales_name;
                    return [
                        'kode' => $sales->sales_code, 'nama' => $name,
                        'jml_supplier' => isset($matrixSupplier[$name]) ? count($matrixSupplier[$name]) : 0,
                        'total_supplier_val' => isset($matrixSupplier[$name]) ? array_sum($matrixSupplier[$name]) : 0,
                    ];
                })->sortByDesc('total_supplier_val')->values();
                $dataView['topSuppliers'] = $topSuppliers;
                $dataView['matrixSupplier'] = $matrixSupplier;

                $view = 'livewire.laporan.exports.kinerja-supplier-pdf';
                $fileName = 'Laporan_Penjualan_Supplier_'.$this->tahun.'_'.$this->bulan.'.pdf';
                break;

            // --- 4 LAPORAN BARU BERBASIS RELASIONAL SKRIPSI ---
            case 'segmentasi':
                $sub = DB::table('penjualans')->select('sales_name', 'kode_pelanggan', DB::raw("COUNT(DISTINCT trans_no) as freq, SUM(total_grand) as m_val"))->whereBetween('tgl_penjualan', [$start, $end])->groupBy('sales_name', 'kode_pelanggan');
                $klaster = DB::table(DB::raw("({$sub->toSql()}) as sub"))->mergeBindings($sub)->selectRaw("sales_name, SUM(CASE WHEN freq >= 3 AND m_val >= 1000000 THEN 1 ELSE 0 END) as vip, SUM(CASE WHEN freq = 1 THEN 1 ELSE 0 END) as pasif, SUM(CASE WHEN freq < 3 AND freq > 1 THEN 1 ELSE 0 END) as menengah")->groupBy('sales_name')->get()->keyBy('sales_name');

                $dataView['data'] = $salesman->map(function($sales) use ($klaster) {
                    $k = $klaster->get($sales->sales_name);
                    return [
                        'kode' => $sales->sales_code, 'nama' => $sales->sales_name,
                        'vip' => $k ? $k->vip : 0, 'menengah' => $k ? $k->menengah : 0, 'pasif' => $k ? $k->pasif : 0, 'total' => $k ? ($k->vip + $k->menengah + $k->pasif) : 0
                    ];
                })->sortByDesc('vip')->values();

                $view = 'livewire.laporan.exports.kinerja-segmentasi-pdf';
                $fileName = 'Laporan_Kinerja_Segmentasi_'.$this->tahun.'_'.$this->bulan.'.pdf';
                break;

            case 'kualitas':
                $penjualan = DB::table('penjualans')->select('sales_name', DB::raw("SUM(total_grand) as gross_sales"))->whereBetween('tgl_penjualan', [$start, $end])->groupBy('sales_name')->get()->keyBy('sales_name');
                $retur = DB::table('returs')->select('sales_name', DB::raw("SUM(total_grand) as total_retur, COUNT(*) as qty_retur"))->whereBetween('tgl_retur', [$start, $end])->groupBy('sales_name')->get()->keyBy('sales_name');

                $dataView['data'] = $salesman->map(function($sales) use ($penjualan, $retur) {
                    $p = $penjualan->get($sales->sales_name); $r = $retur->get($sales->sales_name);
                    $gross = $p ? (float)$p->gross_sales : 0; $retVal = $r ? (float)$r->total_retur : 0;
                    return [
                        'kode' => $sales->sales_code, 'nama' => $sales->sales_name, 'gross' => $gross, 'retur' => $retVal, 'qty_retur' => $r ? $r->qty_retur : 0, 'net' => $gross - $retVal, 'rasio' => $gross > 0 ? ($retVal / $gross) * 100 : 0
                    ];
                })->sortByDesc('retur')->values();

                $view = 'livewire.laporan.exports.kinerja-kualitas-pdf';
                $fileName = 'Laporan_Kualitas_Penjualan_'.$this->tahun.'_'.$this->bulan.'.pdf';
                break;

            case 'efisiensi':
                $ar = DB::table('account_receivables')->select('sales_name', DB::raw("SUM(nilai) as tagihan"))->whereBetween('tgl_penjualan', [$start, $end])->groupBy('sales_name')->get()->keyBy('sales_name');
                $collection = DB::table('collections')->select('sales_name', DB::raw("SUM(receive_amount) as pelunasan"))->whereBetween('tanggal', [$start, $end])->groupBy('sales_name')->get()->keyBy('sales_name');

                $dataView['data'] = $salesman->map(function($sales) use ($ar, $collection) {
                    $a = $ar->get($sales->sales_name); $c = $collection->get($sales->sales_name);
                    $tagihan = $a ? (float)$a->tagihan : 0; $pelunasan = $c ? (float)$c->pelunasan : 0;
                    return [
                        'kode' => $sales->sales_code, 'nama' => $sales->sales_name, 'tagihan' => $tagihan, 'pelunasan' => $pelunasan, 'rasio' => $tagihan > 0 ? ($pelunasan / $tagihan) * 100 : ($pelunasan > 0 ? 100 : 0)
                    ];
                })->sortByDesc('rasio')->values();

                $view = 'livewire.laporan.exports.kinerja-efisiensi-pdf';
                $fileName = 'Laporan_Efisiensi_Penagihan_'.$this->tahun.'_'.$this->bulan.'.pdf';
                break;

            case 'akuisisi':
                $currentMonthCust = DB::table('penjualans')->whereBetween('tgl_penjualan', [$start, $end])->select('sales_name', 'kode_pelanggan', DB::raw('SUM(total_grand) as omzet'))->groupBy('sales_name', 'kode_pelanggan')->get();
                $pastCustArray = DB::table('penjualans')->where('tgl_penjualan', '<', $start)->distinct()->pluck('kode_pelanggan')->toArray();
                $pastCustMap = array_flip($pastCustArray);

                $salesStats = [];
                foreach($currentMonthCust as $c) {
                    $s = strtoupper($c->sales_name);
                    if(!isset($salesStats[$s])) $salesStats[$s] = ['lama' => 0, 'baru' => 0, 'omzet_baru' => 0];
                    if(isset($pastCustMap[$c->kode_pelanggan])) $salesStats[$s]['lama']++;
                    else { $salesStats[$s]['baru']++; $salesStats[$s]['omzet_baru'] += $c->omzet; }
                }

                $dataView['data'] = $salesman->map(function($sales) use ($salesStats) {
                    $sName = strtoupper($sales->sales_name); $stat = $salesStats[$sName] ?? ['lama' => 0, 'baru' => 0, 'omzet_baru' => 0];
                    $totalToko = $stat['lama'] + $stat['baru'];
                    return [
                        'kode' => $sales->sales_code, 'nama' => $sales->sales_name, 'lama' => $stat['lama'], 'baru' => $stat['baru'], 'total_toko' => $totalToko, 'omzet_baru' => $stat['omzet_baru'], 'rasio' => $totalToko > 0 ? ($stat['baru'] / $totalToko) * 100 : 0
                    ];
                })->sortByDesc('baru')->values();

                $view = 'livewire.laporan.exports.kinerja-akuisisi-pdf';
                $fileName = 'Laporan_Akuisisi_Toko_Baru_'.$this->tahun.'_'.$this->bulan.'.pdf';
                break;
        }

        $pdf = Pdf::loadView($view, $dataView)->setPaper('a4', 'landscape');
        return response()->streamDownload(function () use ($pdf) { echo $pdf->output(); }, $fileName);
    }

    public function cetakStok() { /* Kode Cetak Stok Tetap */ }
    public function cetakProfit() { /* Kode Cetak Profit Tetap */ }
    public function cetakKomparasi($jenis) { /* Kode Cetak Komparasi Tetap */ }

    public function render()
    {
        $cabangOptions = Produk::select('cabang')->distinct()->whereNotNull('cabang')->where('cabang','!=','')->orderBy('cabang')->pluck('cabang');
        return view('livewire.laporan.pusat-cetak', ['cabangOptions' => $cabangOptions])->layout('layouts.app', ['header' => 'Pusat Cetak']);
    }
}