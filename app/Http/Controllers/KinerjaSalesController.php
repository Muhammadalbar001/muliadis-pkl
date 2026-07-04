<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use App\Models\Keuangan\AccountReceivable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class KinerjaSalesController extends Controller
{
    // Fungsi bantuan untuk query dasar list Sales (berlaku untuk semua tab)
    private function getBaseSales(Request $request)
    {
        $query = Sales::whereIn('status', ['Active', 'aktif', 'Aktif']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('sales_name', 'like', '%' . $request->search . '%')
                  ->orWhere('sales_code', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('cabang')) {
            $query->where('city', $request->cabang);
        }

        return $query->orderBy('sales_name')->get();
    }

    private function getBulanDate(Request $request)
    {
        $bulanPilih = $request->bulan ?: date('Y-m');
        return Carbon::parse($bulanPilih . '-01');
    }

    private function paginateArray($items, $perPage = 50)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($items, ($currentPage - 1) * $perPage, $perPage);
        return new LengthAwarePaginator($currentItems, count($items), $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query()
        ]);
    }

    // ==============================================================
    // 1. TAB PENCAPAIAN TARGET (Penjualan)
    // ==============================================================
    public function pencapaian(Request $request)
    {
        $dateObj = $this->getBulanDate($request);
        $allSales = $this->getBaseSales($request);
        
        // Hanya ambil data Penjualan dan Target
        $targets = SalesTarget::where('year', $dateObj->year)->where('month', $dateObj->month)->get()->keyBy('sales_id');
        $realisasi = Penjualan::whereBetween('tgl_penjualan', [$dateObj->copy()->startOfMonth(), $dateObj->copy()->endOfMonth()])
            ->select('sales_name', DB::raw("SUM(total_grand) as total_ims"))
            ->groupBy('sales_name')
            ->get()->keyBy('sales_name');

        $laporan = [];
        $totalTarget = 0; $totalReal = 0;

        foreach ($allSales as $sales) {
            $name = $sales->sales_name;
            $t = $targets->get($sales->id);
            $r = $realisasi->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));

            $targetIMS = $t ? (float)$t->target_ims : 0;
            $realIMS = $r ? (float)$r->total_ims : 0;

            $totalTarget += $targetIMS;
            $totalReal += $realIMS;

            $laporan[] = [
                'kode' => $sales->sales_code ?? '-',
                'nama' => $name,
                'cabang' => $sales->city,
                'target_ims' => $targetIMS,
                'real_ims' => $realIMS,
                'persen_ims' => $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0,
                'gap' => $realIMS - $targetIMS,
            ];
        }

        // Urutkan berdasarkan pencapaian tertinggi
        usort($laporan, fn($a, $b) => $b['persen_ims'] <=> $a['persen_ims']);
        $paginated = $this->paginateArray($laporan);

        $optCabang = Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'));

        return view('laporan.kinerja.pencapaian', compact('paginated', 'optCabang', 'totalTarget', 'totalReal'));
    }

    // ==============================================================
    // 2. TAB RANKING & KREDIT (Account Receivable / AR)
    // ==============================================================
    public function ranking(Request $request)
    {
        $allSales = $this->getBaseSales($request);
        
        // Hanya ambil data Piutang (AR)
        $arStats = AccountReceivable::selectRaw("sales_name, 
            SUM(nilai) as total_ar, 
            SUM(CASE WHEN umur_piutang <= 30 THEN nilai ELSE 0 END) as ar_lancar, 
            SUM(CASE WHEN umur_piutang > 30 THEN nilai ELSE 0 END) as ar_macet")
            ->where('status', '!=', 'Lunas')
            ->groupBy('sales_name')->get()->keyBy('sales_name');

        $laporan = [];
        $totalAR = 0; $totalMacet = 0;

        foreach ($allSales as $sales) {
            $name = $sales->sales_name;
            $ar = $arStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));

            $arTotal = $ar ? (float)$ar->total_ar : 0;
            $arMacet = $ar ? (float)$ar->ar_macet : 0;

            $totalAR += $arTotal;
            $totalMacet += $arMacet;

            $laporan[] = [
                'kode' => $sales->sales_code ?? '-',
                'nama' => $name,
                'cabang' => $sales->city,
                'ar_total' => $arTotal,
                'ar_macet' => $arMacet,
                'ar_persen_macet' => $arTotal > 0 ? ($arMacet / $arTotal) * 100 : 0,
            ];
        }

        // Urutkan berdasarkan piutang terbanyak
        usort($laporan, fn($a, $b) => $b['ar_total'] <=> $a['ar_total']);
        $paginated = $this->paginateArray($laporan);
        
        $optCabang = Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'));

        return view('laporan.kinerja.ranking', compact('paginated', 'optCabang', 'totalAR', 'totalMacet'));
    }

    // ==============================================================
    // 3. TAB PRODUKTIVITAS (Outlet Aktif & Effective Call)
    // ==============================================================
    public function produktivitas(Request $request)
    {
        $dateObj = $this->getBulanDate($request);
        $allSales = $this->getBaseSales($request);
        $minNominal = $request->minNominal ?: 50000;
        
        $subQuery = DB::table('penjualans')
            ->select('sales_name', 'trans_no', 'kode_pelanggan', DB::raw("SUM(total_grand) as total_per_nota"))
            ->whereBetween('tgl_penjualan', [$dateObj->copy()->startOfMonth(), $dateObj->copy()->endOfMonth()])
            ->groupBy('sales_name', 'trans_no', 'kode_pelanggan');

        $salesStats = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->mergeBindings($subQuery)
            ->selectRaw("
                sales_name, 
                COUNT(DISTINCT kode_pelanggan) as total_oa, 
                COUNT(DISTINCT CASE WHEN total_per_nota >= {$minNominal} THEN trans_no END) as total_ec
            ")
            ->groupBy('sales_name')
            ->get()
            ->keyBy('sales_name');

        $laporan = [];
        foreach ($allSales as $sales) {
            $name = $sales->sales_name;
            $stat = $salesStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));

            $laporan[] = [
                'kode' => $sales->sales_code ?? '-',
                'nama' => $name,
                'real_oa' => $stat ? (int)$stat->total_oa : 0,
                'ec' => $stat ? (int)$stat->total_ec : 0,
            ];
        }

        // Urutkan berdasarkan EC tertinggi
        usort($laporan, fn($a, $b) => $b['ec'] <=> $a['ec']);
        $paginated = $this->paginateArray($laporan);

        $optCabang = Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'));

        return view('laporan.kinerja.produktivitas', compact('paginated', 'optCabang', 'minNominal'));
    }

    // ==============================================================
    // 4. TAB KINERJA SUPPLIER
    // ==============================================================
    public function supplier(Request $request)
    {
        $dateObj = $this->getBulanDate($request);
        $allSales = $this->getBaseSales($request);
        
        $start = $dateObj->copy()->startOfMonth();
        $end = $dateObj->copy()->endOfMonth();

        $topSuppliers = Penjualan::select('supplier', DB::raw("SUM(total_grand) as val"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->whereNotNull('supplier')
            ->groupBy('supplier')
            ->orderByDesc('val')
            ->limit(15) // Batasi agar tabel tidak terlalu lebar
            ->pluck('supplier');

        $rawPivot = Penjualan::selectRaw("sales_name, supplier, SUM(total_grand) as total")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->whereIn('supplier', $topSuppliers)
            ->groupBy('sales_name', 'supplier')
            ->get();

        $matrixSupplier = [];
        foreach ($rawPivot as $p) { $matrixSupplier[$p->sales_name][$p->supplier] = $p->total; }

        $laporan = [];
        foreach ($allSales as $sales) {
            $name = $sales->sales_name;
            $countSupplied = isset($matrixSupplier[$name]) ? count($matrixSupplier[$name]) : 0;
            $sumSupplied = isset($matrixSupplier[$name]) ? array_sum($matrixSupplier[$name]) : 0;

            $laporan[] = [
                'kode' => $sales->sales_code ?? '-',
                'nama' => $name,
                'jml_supplier' => $countSupplied,
                'total_supplier_val' => $sumSupplied,
            ];
        }

        usort($laporan, fn($a, $b) => $b['total_supplier_val'] <=> $a['total_supplier_val']);
        $paginated = $this->paginateArray($laporan);

        $optCabang = Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'));

        return view('laporan.kinerja.supplier', compact('paginated', 'optCabang', 'topSuppliers', 'matrixSupplier'));
    }
    // ==============================================================
    // FUNGSI BANTUAN UNTUK EXPORT (EXCEL & PDF)
    // ==============================================================
    private function getAllDataForExport(Request $request)
    {
        $dateObj = $this->getBulanDate($request);
        $start   = $dateObj->copy()->startOfMonth()->format('Y-m-d');
        $end     = $dateObj->copy()->endOfMonth()->format('Y-m-d');
        $minNominal = $request->minNominal ?: 50000;

        $allSales = $this->getBaseSales($request);
        $targets = SalesTarget::where('year', $dateObj->year)->where('month', $dateObj->month)->get()->keyBy('sales_id');

        $subQuery = DB::table('penjualans')
            ->select('sales_name', 'trans_no', 'kode_pelanggan', DB::raw("SUM(total_grand) as total_per_nota"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name', 'trans_no', 'kode_pelanggan');

        $salesStats = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->mergeBindings($subQuery)
            ->selectRaw("
                sales_name, 
                SUM(total_per_nota) as total_ims, 
                COUNT(DISTINCT kode_pelanggan) as total_oa, 
                COUNT(DISTINCT CASE WHEN total_per_nota >= {$minNominal} THEN trans_no END) as total_ec
            ")
            ->groupBy('sales_name')
            ->get()->keyBy('sales_name');

        $arStats = AccountReceivable::selectRaw("sales_name, 
            SUM(nilai) as total_ar, 
            SUM(CASE WHEN umur_piutang <= 30 THEN nilai ELSE 0 END) as ar_lancar, 
            SUM(CASE WHEN umur_piutang > 30 THEN nilai ELSE 0 END) as ar_macet")
            ->where('status', '!=', 'Lunas')
            ->groupBy('sales_name')->get()->keyBy('sales_name');

        $topSuppliers = Penjualan::select('supplier', DB::raw("SUM(total_grand) as val"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->whereNotNull('supplier')
            ->groupBy('supplier')
            ->orderByDesc('val')
            ->pluck('supplier');

        $rawPivot = Penjualan::selectRaw("sales_name, supplier, SUM(total_grand) as total")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->whereIn('supplier', $topSuppliers)
            ->groupBy('sales_name', 'supplier')
            ->get();

        $matrixSupplier = [];
        foreach ($rawPivot as $p) { $matrixSupplier[$p->sales_name][$p->supplier] = $p->total; }

        $laporan = [];
        foreach ($allSales as $sales) {
            $name = $sales->sales_name;
            
            $t = $targets->get($sales->id);
            $stat = $salesStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));
            $ar = $arStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));
            
            $targetIMS = $t ? (float)$t->target_ims : 0;
            $realIMS = $stat ? (float)$stat->total_ims : 0;
            $arTotal = $ar ? (float)$ar->total_ar : 0;
            $arMacet = $ar ? (float)$ar->ar_macet : 0;

            $laporan[] = [
                'kode' => $sales->sales_code ?? '-',
                'nama' => $name,
                'cabang' => $sales->city,
                'divisi' => $sales->divisi,
                'target_ims' => $targetIMS,
                'real_ims' => $realIMS,
                'persen_ims' => $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0,
                'gap' => $realIMS - $targetIMS,
                'ar_total' => $arTotal,
                'ar_lancar' => $ar ? (float)$ar->ar_lancar : 0,
                'ar_macet' => $arMacet,
                'ar_persen_macet' => $arTotal > 0 ? ($arMacet / $arTotal) * 100 : 0,
                'real_oa' => $stat ? (int)$stat->total_oa : 0,
                'ec' => $stat ? (int)$stat->total_ec : 0,
                'jml_supplier' => isset($matrixSupplier[$name]) ? count($matrixSupplier[$name]) : 0,
                'total_supplier_val' => isset($matrixSupplier[$name]) ? array_sum($matrixSupplier[$name]) : 0,
            ];
        }

        return [
            'laporan' => collect($laporan),
            'topSuppliers' => $topSuppliers,
            'matrixSupplier' => $matrixSupplier
        ];
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getAllDataForExport($request);
        $laporan = $data['laporan']->sortByDesc('persen_ims')->values();
        $suppliers = $data['topSuppliers'];
        $matrix = $data['matrixSupplier'];

        $bulanPilih = $request->bulan ?: date('Y-m');
        $writer = \Spatie\SimpleExcel\SimpleExcelWriter::streamDownload('Rapor_Kinerja_Sales_' . $bulanPilih . '.xlsx');

        $header = [
            'Kode Sales', 'Nama Sales', 'Area',
            'Target (Rp)', 'Realisasi (Rp)', 'Ach (%)', 'Gap (Rp)',
            'Total Piutang (AR)', 'AR > 30 Hari', '% Macet',
            'Outlet Aktif (OA)', 'Effective Call (EC)',
            'Jml Brand Terjual', 'Omzet Brand Utama'
        ];

        foreach($suppliers as $supp) { $header[] = $supp; }
        $writer->addHeader($header);

        foreach ($laporan as $row) {
            $rowData = [
                $row['kode'], $row['nama'], $row['cabang'],
                $row['target_ims'], $row['real_ims'], $row['persen_ims'], $row['gap'],
                $row['ar_total'], $row['ar_macet'], $row['ar_persen_macet'],
                $row['real_oa'], $row['ec'],
                $row['jml_supplier'], $row['total_supplier_val']
            ];
            foreach($suppliers as $supp) {
                $rowData[] = $matrix[$row['nama']][$supp] ?? 0;
            }
            $writer->addRow($rowData);
        }
        return $writer->toBrowser();
    }

    public function exportPdf(Request $request, $type)
    {
        $dataRaw = $this->getAllDataForExport($request);
        $laporanCollection = $dataRaw['laporan'];
        
        $dateObj = $this->getBulanDate($request);
        $periodeStr = $dateObj->translatedFormat('F Y');
        $user = auth()->user()->name ?? 'Administrator';
        $now = now()->format('d/m/Y H:i');

        $dataView = [
            'periode' => $periodeStr,
            'cetak_oleh' => $user,
            'tgl_cetak' => $now,
        ];

        $view = '';
        $fileName = '';
        
        switch ($type) {
            case 'ar':
                $view = 'livewire.laporan.exports.kinerja-ar-pdf';
                $fileName = 'Laporan_Monitoring_Kredit_' . $dateObj->format('Y-m') . '.pdf';
                $dataView['data'] = $laporanCollection->sortByDesc('ar_total');
                break;

            case 'supplier':
                $view = 'livewire.laporan.exports.kinerja-supplier-pdf';
                $fileName = 'Laporan_Penjualan_Supplier_' . $dateObj->format('Y-m') . '.pdf';
                $dataView['data'] = $laporanCollection->sortByDesc('total_supplier_val');
                $dataView['topSuppliers'] = $dataRaw['topSuppliers'];
                $dataView['matrixSupplier'] = $dataRaw['matrixSupplier'];
                break;

            case 'produktifitas':
                $view = 'livewire.laporan.exports.kinerja-produktivitas-pdf';
                $fileName = 'Laporan_Produktivitas_Sales_' . $dateObj->format('Y-m') . '.pdf';
                $dataView['data'] = $laporanCollection->sortByDesc('ec');
                $dataView['minNominal'] = $request->minNominal ?: 50000;
                break;

            case 'penjualan':
            default:
                $view = 'livewire.laporan.exports.kinerja-penjualan-pdf'; 
                $fileName = 'Laporan_Kinerja_Penjualan_' . $dateObj->format('Y-m') . '.pdf';
                $dataView['data'] = $laporanCollection->sortByDesc('persen_ims');
                break;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, $dataView)->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }
}