<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Master\SalesTarget;
use App\Models\Master\Produk;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Keuangan\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardIndex extends Component
{
    public $startDate;
    public $endDate;
    public $filterCabang = [];
    public $filterSales = [];

    public function mount()
    {
        $this->startDate = date('Y-m-01');
        $this->endDate   = date('Y-m-d');
    }

    public function updated($propertyName) 
    { 
        $this->dispatch('update-charts', data: $this->chartData);
    }

    private function baseFilter($query, $dateCol) {
        return $query->whereDate($dateCol, '>=', $this->startDate)
                     ->whereDate($dateCol, '<=', $this->endDate)
                     ->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
                     ->when(!empty($this->filterSales), fn($q) => $q->whereIn('sales_name', $this->filterSales));
    }

    #[Computed]
    public function kpiStats()
    {
        $salesSum = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $returSum = $this->baseFilter(Retur::query(), 'tgl_retur')->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $arSum    = $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan')->sum(DB::raw('CAST(nilai AS DECIMAL(20,2))'));
        $collSum  = $this->baseFilter(Collection::query(), 'tanggal')->sum(DB::raw('CAST(receive_amount AS DECIMAL(20,2))'));
        
        $persenRetur = $salesSum > 0 ? ($returSum / $salesSum) * 100 : 0;
        $totalOa     = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->distinct('kode_pelanggan')->count();
        $totalEc     = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->distinct('trans_no')->count();

        return compact('salesSum', 'returSum', 'arSum', 'collSum', 'persenRetur', 'totalOa', 'totalEc');
    }

    #[Computed]
    public function chartData()
    {
        $dates = [];
        $start = $this->startDate ? Carbon::parse($this->startDate) : Carbon::now()->startOfMonth();
        $end   = $this->endDate ? Carbon::parse($this->endDate) : Carbon::now();
        
        $c = $start->copy();
        $limit = 0; 
        while ($c <= $end && $limit < 366) { 
            $dates[] = $c->format('Y-m-d'); 
            $c->addDay();
            $limit++;
        }

        $dailySales = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->selectRaw("DATE(tgl_penjualan) as tgl, SUM(total_grand) as total")->groupBy('tgl')->pluck('total', 'tgl');
        $dailyRetur = $this->baseFilter(Retur::query(), 'tgl_retur')->selectRaw("DATE(tgl_retur) as tgl, SUM(total_grand) as total")->groupBy('tgl')->pluck('total', 'tgl');
        $dailyAR    = $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan')->selectRaw("DATE(tgl_penjualan) as tgl, SUM(total_nilai) as total")->groupBy('tgl')->pluck('total', 'tgl');
        $dailyColl  = $this->baseFilter(Collection::query(), 'tanggal')->selectRaw("DATE(tanggal) as tgl, SUM(receive_amount) as total")->groupBy('tgl')->pluck('total', 'tgl');

        $dSales = []; $dRetur = []; $dAR = []; $dColl = [];
        foreach ($dates as $d) {
            $dSales[] = (float)($dailySales[$d] ?? 0);
            $dRetur[] = (float)($dailyRetur[$d] ?? 0);
            $dAR[]    = (float)($dailyAR[$d] ?? 0);
            $dColl[]  = (float)($dailyColl[$d] ?? 0);
        }

        $topProduk = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->selectRaw("nama_item, SUM(qty) as total")->groupBy('nama_item')->orderByDesc('total')->limit(10)->get();
        $topCustomer = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->selectRaw("nama_pelanggan, SUM(total_grand) as total")->groupBy('nama_pelanggan')->orderByDesc('total')->limit(10)->get();
        $topSupplier = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->selectRaw("supplier, SUM(total_grand) as total")->groupBy('supplier')->orderByDesc('total')->limit(10)->get();

        $salesPerf = $this->getSalesmanPerformance();

        return [
            'dates'         => $dates,
            'trend_sales'   => $dSales,
            'trend_retur'   => $dRetur,
            'trend_ar'      => $dAR,
            'trend_coll'    => $dColl,
            'top_produk_lbl' => $topProduk->pluck('nama_item'),
            'top_produk_val' => $topProduk->pluck('total'),
            'top_cust_lbl'   => $topCustomer->pluck('nama_pelanggan'),
            'top_cust_val'   => $topCustomer->pluck('total'),
            'top_supp_lbl'   => $topSupplier->pluck('supplier'),
            'top_supp_val'   => $topSupplier->pluck('total'),
            'sales_names'   => $salesPerf['names'],
            'sales_real'    => $salesPerf['real'],
            'sales_target'  => $salesPerf['target'],
        ];
    }

    private function getSalesmanPerformance()
    {
        $realSales = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')
            ->selectRaw("sales_name, SUM(total_grand) as total")
            ->groupBy('sales_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        
        $names = $realSales->pluck('sales_name')->toArray();
        $start = $this->startDate ? Carbon::parse($this->startDate) : Carbon::now();
        
        $targets = SalesTarget::where('year', $start->year)
            ->where('month', $start->month)
            ->whereHas('sales', fn($q) => $q->whereIn('sales_name', $names))
            ->with('sales')
            ->get()
            ->mapWithKeys(fn($item) => [$item->sales->sales_name => $item->target_ims]);

        $dataTarget = [];
        foreach($names as $n) {
            $dataTarget[] = (float)($targets[strtoupper($n)] ?? $targets[$n] ?? 0);
        }

        return [
            'names'  => $names,
            'real'   => $realSales->pluck('total')->toArray(),
            'target' => $dataTarget
        ];
    }

    // FUNGSI UNTUK MENDAPATKAN SMART ALERTS (SUDAH DIPERBAIKI)
    #[Computed]
    public function smartAlerts()
    {
        $alerts = collect();

        // 1. Alert: Piutang Kritis (> 30 Hari)
        $piutangKritis = AccountReceivable::where('umur_piutang', '>', 30)
                                          ->where('status', '!=', 'Lunas')
                                          ->count();
        if ($piutangKritis > 0) {
            $totalNilaiKritis = AccountReceivable::where('umur_piutang', '>', 30)
                                                 ->where('status', '!=', 'Lunas')
                                                 ->sum(DB::raw('CAST(nilai AS DECIMAL(20,2))'));
            $alerts->push([
                'type' => 'danger',
                'icon' => 'fas fa-exclamation-triangle',
                'title' => 'Peringatan Piutang Macet!',
                'message' => "Terdapat <strong>{$piutangKritis} faktur</strong> piutang yang telah melewati batas 30 hari dengan total nilai <strong>Rp " . number_format($totalNilaiKritis, 0, ',', '.') . "</strong>. Diperlukan tindakan penagihan segera.",
                'link' => route('laporan.rekap-ar')
            ]);
        }

        // 2. Alert: Stok Kosong (Produk Master)
        $stokKosong = Produk::where('stok', '<=', 0)->count();
        if ($stokKosong > 0) {
            $alerts->push([
                'type' => 'warning',
                'icon' => 'fas fa-box-open',
                'title' => 'Perhatian Ketersediaan Stok',
                'message' => "Terdapat <strong>{$stokKosong} item produk</strong> pada Master Data yang saat ini kehabisan stok (Stok = 0). Silakan tinjau valuasi stok untuk mencegah hilangnya potensi penjualan.",
                'link' => route('pimpinan.stock-analysis')
            ]);
        }

        // 3. Alert: Penurunan Penjualan (Menggunakan tgl_penjualan dan membandingkan dengan bulan dari startDate)
        $start = $this->startDate ? Carbon::parse($this->startDate) : Carbon::now();
        $bulanIni = $start->month;
        $tahunIni = $start->year;

        $bulanLalu = $start->copy()->subMonth();

        $omzetBulanLalu = Penjualan::whereMonth('tgl_penjualan', $bulanLalu->month)
                                   ->whereYear('tgl_penjualan', $bulanLalu->year)
                                   ->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
                                   
        $omzetBulanIni = Penjualan::whereMonth('tgl_penjualan', $bulanIni)
                                  ->whereYear('tgl_penjualan', $tahunIni)
                                  ->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        
        // Jangan beri alert jika yang dipilih adalah bulan berjalan (karena omzet belum final sampai akhir bulan)
        if ($omzetBulanLalu > 0 && $omzetBulanIni < $omzetBulanLalu && $bulanIni != Carbon::now()->month) {
            $selisih = $omzetBulanLalu - $omzetBulanIni;
            $persentaseTurun = round(($selisih / $omzetBulanLalu) * 100, 1);
            
            $alerts->push([
                'type' => 'info',
                'icon' => 'fas fa-chart-line',
                'title' => 'Evaluasi Kinerja Penjualan',
                'message' => "Total omzet pada bulan " . Carbon::create($tahunIni, $bulanIni)->translatedFormat('F Y') . " mengalami <strong>penurunan sebesar {$persentaseTurun}%</strong> (Rp " . number_format($selisih, 0, ',', '.') . ") dibandingkan bulan sebelumnya.",
                'link' => route('pimpinan.profit-analysis')
            ]);
        }

        return $alerts;
    }

    public function render()
    {
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        $stats = $this->kpiStats;
        $chartData = $this->chartData;
        $alerts = $this->smartAlerts; // Mengambil koleksi alert

        return view('livewire.dashboard-index', array_merge(
            $stats, 
            compact('optCabang', 'optSales', 'chartData', 'alerts')
        ))->layout('layouts.app', ['header' => 'Executive Dashboard']);
    }

    public function formatCompact($val)
    {
        if ($val >= 1000000000) {
            return number_format($val / 1000000000, 2, ',', '.') . ' M'; 
        } elseif ($val >= 1000000) {
            return number_format($val / 1000000, 1, ',', '.') . ' Jt'; 
        } else {
            return number_format($val, 0, ',', '.');
        }
    }
}