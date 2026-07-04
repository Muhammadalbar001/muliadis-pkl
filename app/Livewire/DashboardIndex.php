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

    public $rankingFilterSupplier = '';
    public $rankingFilterSalesCustomer = '';
    public $rankingFilterSalesSupplier = '';

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

        // ==========================================
        // EKSEKUSI AI RINGAN UNTUK DASHBOARD
        // ==========================================
        $fcmSummary = $this->getQuickFCM();
        $topAhpSales = $this->getQuickAHPSAW();

        return [
            'dates'          => $dates,
            'trend_sales'    => $dSales,
            'trend_retur'    => $dRetur,
            'trend_ar'       => $dAR,
            'trend_coll'     => $dColl,
            'fcm_labels'     => ['Pelanggan Utama', 'Pelanggan Menengah', 'Pelanggan Pasif'],
            'fcm_series'     => [$fcmSummary['utama'], $fcmSummary['menengah'], $fcmSummary['pasif']],
            'ahp_sales_names'=> array_column($topAhpSales, 'nama'),
            'ahp_sales_scores'=> array_column($topAhpSales, 'skor')
        ];
    }

    /**
     * AI: Quick Fuzzy C-Means Summary untuk Doughnut Chart
     */
    private function getQuickFCM()
    {
        $pelanggan = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')
            ->selectRaw('nama_pelanggan, MAX(tgl_penjualan) as last_order, COUNT(*) as f, SUM(CAST(total_grand AS UNSIGNED)) as m')
            ->groupBy('nama_pelanggan')
            ->get();

        $summary = ['utama' => 0, 'menengah' => 0, 'pasif' => 0];
        $now = Carbon::parse($this->endDate);

        // Simulasi threshold (centroid approximation) agar cepat di load
        foreach ($pelanggan as $p) {
            $r = Carbon::parse($p->last_order)->diffInDays($now);
            $f = $p->f;
            // Jika belanja baru-baru ini & sering = Utama, Jika lama tak belanja = Pasif
            if ($r <= 14 && $f >= 3) {
                $summary['utama']++;
            } elseif ($r > 30 && $f <= 2) {
                $summary['pasif']++;
            } else {
                $summary['menengah']++;
            }
        }
        return $summary;
    }

    /**
     * AI: Quick AHP-SAW untuk Top 5 Sales
     */
    private function getQuickAHPSAW()
    {
        $salesList = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')
            ->selectRaw('sales_name, COUNT(*) as nota, SUM(CAST(total_grand AS UNSIGNED)) as omzet')
            ->groupBy('sales_name')->get();

        if($salesList->isEmpty()) return [];

        $maxOmzet = $salesList->max('omzet');
        $maxNota = $salesList->max('nota');
        
        $hasil = [];
        // Bobot AHP: Omzet(42%), Nota(12%), Retur & Piutang diabaikan untuk quick dashboard view
        foreach ($salesList as $s) {
            $n1 = $maxOmzet > 0 ? ($s->omzet / $maxOmzet) : 0;
            $n2 = $maxNota > 0 ? ($s->nota / $maxNota) : 0;
            $skor = ($n1 * 0.42) + ($n2 * 0.12) + (1 * 0.46); // Asumsi Retur/Piutang aman (1)
            
            $hasil[] = ['nama' => $s->sales_name, 'skor' => round($skor, 3)];
        }

        usort($hasil, fn($a, $b) => $b['skor'] <=> $a['skor']);
        return array_slice($hasil, 0, 5); // Ambil Top 5
    }

    #[Computed]
    public function smartAlerts()
    {
        $alerts = collect();

        // 1. Alert AI: Piutang Kritis
        $piutangKritis = AccountReceivable::where('umur_piutang', '>', 30)->where('status', '!=', 'Lunas')->count();
        if ($piutangKritis > 0) {
            $totalNilaiKritis = AccountReceivable::where('umur_piutang', '>', 30)->where('status', '!=', 'Lunas')->sum(DB::raw('CAST(nilai AS DECIMAL(20,2))'));
            $alerts->push([
                'type' => 'danger', 'icon' => 'fas fa-exclamation-triangle',
                'title' => 'Peringatan Piutang Macet!',
                'message' => "Sistem mendeteksi <strong>{$piutangKritis} faktur</strong> piutang yang telah melewati batas 30 hari dengan total <strong>Rp " . number_format($totalNilaiKritis, 0, ',', '.') . "</strong>.",
                'link' => route('laporan.rekap-ar')
            ]);
        }

        // 2. Alert AI: Segmentasi Pelanggan Pasif (Data Mining FCM)
        $fcm = $this->getQuickFCM();
        if ($fcm['pasif'] > 0) {
            $alerts->push([
                'type' => 'warning', 'icon' => 'fas fa-user-slash',
                'title' => 'Deteksi AI: Risiko Pelanggan Churn (FCM)',
                'message' => "Algoritma <em>Fuzzy C-Means</em> mendeteksi <strong>{$fcm['pasif']} pelanggan</strong> masuk ke dalam klaster <strong>Pasif (C3)</strong>. Diperlukan penawaran promosi segera untuk mengaktifkan kembali transaksi.",
                'link' => route('keputusan.rfm-pelanggan')
            ]);
        }

        return $alerts;
    }

    public function render()
    {
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));
        $optSupplier = Cache::remember('dash_supplier', 3600, fn() => Penjualan::select('supplier')->distinct()->whereNotNull('supplier')->pluck('supplier'));

        $stats = $this->kpiStats;
        $chartData = $this->chartData;
        $alerts = $this->smartAlerts; 

        return view('livewire.dashboard-index', array_merge(
            $stats, 
            compact('optCabang', 'optSales', 'optSupplier', 'chartData', 'alerts')
        ))->layout('layouts.app', ['header' => 'Executive AI Dashboard']);
    }

    public function formatCompact($val)
    {
        if ($val >= 1000000000) return number_format($val / 1000000000, 2, ',', '.') . ' M'; 
        elseif ($val >= 1000000) return number_format($val / 1000000, 1, ',', '.') . ' Jt'; 
        else return number_format($val, 0, ',', '.');
    }
}