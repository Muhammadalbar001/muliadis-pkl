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

    public $showSegmentModal = false;
    public $segmentModalTitle = '';
    public $segmentDetails = [];

    public function mount()
    {
        $this->startDate = date('Y-m-01');
        $this->endDate   = date('Y-m-d');
    }

    public function updated($propertyName) 
    { 
        $this->dispatch('update-charts', data: $this->chartData);
    }

    // DIMODIFIKASI: Menambahkan parameter custom start & end untuk perhitungan mundur (MoM)
    private function baseFilter($query, $dateCol, $customStart = null, $customEnd = null) {
        $start = $customStart ?? $this->startDate;
        $end = $customEnd ?? $this->endDate;

        return $query->whereDate($dateCol, '>=', $start)
                     ->whereDate($dateCol, '<=', $end)
                     ->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
                     ->when(!empty($this->filterSales), fn($q) => $q->whereIn('sales_name', $this->filterSales));
    }

    // FUNGSI BARU: Menghitung persentase tren
    private function getTrend($current, $previous) {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return (($current - $previous) / $previous) * 100;
    }

    #[Computed]
    public function kpiStats()
    {
        // Perhitungan Periode Berjalan (Current)
        $salesSum = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $returSum = $this->baseFilter(Retur::query(), 'tgl_retur')->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $arSum    = $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan')->sum(DB::raw('CAST(nilai AS DECIMAL(20,2))'));
        $collSum  = $this->baseFilter(Collection::query(), 'tanggal')->sum(DB::raw('CAST(receive_amount AS DECIMAL(20,2))'));
        
        $persenRetur = $salesSum > 0 ? ($returSum / $salesSum) * 100 : 0;
        $totalOa     = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->distinct('kode_pelanggan')->count();
        $totalEc     = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->distinct('trans_no')->count();

        // Perhitungan Periode Sebelumnya / Month-over-Month (MoM)
        $prevStart = Carbon::parse($this->startDate)->subMonth()->format('Y-m-d');
        $prevEnd = Carbon::parse($this->endDate)->subMonth()->format('Y-m-d');

        $salesSumPrev = $this->baseFilter(Penjualan::query(), 'tgl_penjualan', $prevStart, $prevEnd)->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $returSumPrev = $this->baseFilter(Retur::query(), 'tgl_retur', $prevStart, $prevEnd)->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $arSumPrev    = $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan', $prevStart, $prevEnd)->sum(DB::raw('CAST(nilai AS DECIMAL(20,2))'));
        $collSumPrev  = $this->baseFilter(Collection::query(), 'tanggal', $prevStart, $prevEnd)->sum(DB::raw('CAST(receive_amount AS DECIMAL(20,2))'));

        $trendSales = $this->getTrend($salesSum, $salesSumPrev);
        $trendRetur = $this->getTrend($returSum, $returSumPrev);
        $trendAr    = $this->getTrend($arSum, $arSumPrev);
        $trendColl  = $this->getTrend($collSum, $collSumPrev);

        return compact(
            'salesSum', 'returSum', 'arSum', 'collSum', 'persenRetur', 'totalOa', 'totalEc',
            'trendSales', 'trendRetur', 'trendAr', 'trendColl'
        );
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

    private function getQuickFCM()
    {
        $pelanggan = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')
            ->selectRaw('nama_pelanggan, MAX(tgl_penjualan) as last_order, COUNT(*) as f, SUM(CAST(total_grand AS UNSIGNED)) as m')
            ->groupBy('nama_pelanggan')
            ->get();

        $summary = ['utama' => 0, 'menengah' => 0, 'pasif' => 0];
        $now = Carbon::parse($this->endDate);

        foreach ($pelanggan as $p) {
            $r = Carbon::parse($p->last_order)->diffInDays($now);
            $f = $p->f;
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

    private function getQuickAHPSAW()
    {
        $salesList = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')
            ->selectRaw('sales_name, COUNT(*) as nota, SUM(CAST(total_grand AS UNSIGNED)) as omzet')
            ->groupBy('sales_name')->get();

        if($salesList->isEmpty()) return [];

        $maxOmzet = $salesList->max('omzet');
        $maxNota = $salesList->max('nota');
        
        $hasil = [];
        foreach ($salesList as $s) {
            $n1 = $maxOmzet > 0 ? ($s->omzet / $maxOmzet) : 0;
            $n2 = $maxNota > 0 ? ($s->nota / $maxNota) : 0;
            $skor = ($n1 * 0.42) + ($n2 * 0.12) + (1 * 0.46); 
            
            $hasil[] = ['nama' => $s->sales_name, 'skor' => round($skor, 3)];
        }

        usort($hasil, fn($a, $b) => $b['skor'] <=> $a['skor']);
        return array_slice($hasil, 0, 5);
    }

    #[Computed]
    public function smartAlerts()
    {
        $alerts = collect();
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

    #[Computed]
    public function autoInsight()
    {
        $kpi = $this->kpiStats;
        $salesStr = $this->formatCompact($kpi['salesSum']);
        $returPersen = number_format($kpi['persenRetur'], 1);
        
        $fcm = $this->getQuickFCM();
        $pasif = $fcm['pasif'];
        
        $topSalesArr = $this->getQuickAHPSAW();
        $topSalesName = count($topSalesArr) > 0 ? $topSalesArr[0]['nama'] : 'Belum ada data';
        
        $piutangKritis = AccountReceivable::where('umur_piutang', '>', 30)->where('status', '!=', 'Lunas')->count();
        
        // Membaca tren MoM
        $trendSales = $kpi['trendSales'];
        $trendStr = $trendSales >= 0 
            ? "dan mengalami <strong>kenaikan ".number_format($trendSales, 1)."%</strong> dibanding periode sebelumnya" 
            : "namun mengalami <strong class='text-rose-500'>penurunan ".number_format(abs($trendSales), 1)."%</strong> dibanding periode sebelumnya";

        // Merakit paragraf bahasa natural yang lebih kaya
        $text = "Berdasarkan rentang data yang dipilih, realisasi omzet mencapai <strong class='text-indigo-600 dark:text-indigo-400'>Rp {$salesStr}</strong>, {$trendStr}. ";
        
        if ($kpi['persenRetur'] > 5) {
            $text .= "Tingkat pengembalian barang perlu diwaspadai karena mencapai <strong class='text-rose-600'>{$returPersen}%</strong> (di atas batas wajar 5%). ";
        } else {
            $text .= "Kualitas penjualan sangat baik dengan rasio retur yang aman di <strong class='text-emerald-600'>{$returPersen}%</strong>. ";
        }
        
        if ($topSalesName !== 'Belum ada data') {
            $text .= "Kalkulasi DSS AHP-SAW menempatkan <strong class='text-blue-600 dark:text-blue-400'>{$topSalesName}</strong> sebagai personel sales dengan performa tertinggi. ";
        }
        
        if ($pasif > 0 || $piutangKritis > 0) {
            $text .= "Namun, direkomendasikan tindakan preventif untuk ";
            $actions = [];
            if ($pasif > 0) $actions[] = "reaktivasi <strong class='text-orange-600'>{$pasif} pelanggan pasif (churn)</strong> hasil klasifikasi FCM";
            if ($piutangKritis > 0) $actions[] = "penagihan <strong class='text-rose-600'>{$piutangKritis} nota piutang macet</strong>";
            $text .= implode(" dan ", $actions) . ".";
        } else {
            $text .= "Tidak terdeteksi anomali piutang macet maupun churn pelanggan secara signifikan pada periode ini.";
        }

        return $text;
    }

    public function showSegmentDetails($index)
    {
        $pelanggan = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')
            ->selectRaw('nama_pelanggan, MAX(tgl_penjualan) as last_order, COUNT(*) as f, SUM(CAST(total_grand AS UNSIGNED)) as m')
            ->groupBy('nama_pelanggan')
            ->get();

        $now = Carbon::parse($this->endDate);
        $details = [];

        foreach ($pelanggan as $p) {
            $r = Carbon::parse($p->last_order)->diffInDays($now);
            $f = $p->f;
            
            $kategori = -1;
            if ($r <= 14 && $f >= 3) {
                $kategori = 0; 
            } elseif ($r > 30 && $f <= 2) {
                $kategori = 2; 
            } else {
                $kategori = 1; 
            }

            if ($kategori === $index) {
                $details[] = [
                    'nama' => $p->nama_pelanggan,
                    'terakhir_belanja' => Carbon::parse($p->last_order)->translatedFormat('d M Y'),
                    'frekuensi' => $f,
                    'monetary' => (float) $p->m
                ];
            }
        }

        usort($details, fn($a, $b) => $b['monetary'] <=> $a['monetary']);

        $titles = ['Daftar Pelanggan VIP (Utama)', 'Daftar Pelanggan Menengah', 'Daftar Pelanggan Pasif (Risiko Churn)'];
        $this->segmentModalTitle = $titles[$index] ?? 'Detail Segmen';
        $this->segmentDetails = $details;
        $this->showSegmentModal = true;
    }

    public function closeSegmentModal()
    {
        $this->showSegmentModal = false;
        $this->segmentDetails = [];
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