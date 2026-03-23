<?php

use Illuminate\Support\Facades\Route;

// --- 1. IMPORT DASHBOARD BERDASARKAN ROLE ---
use App\Livewire\DashboardIndex; // Dashboard Pimpinan
use App\Livewire\Admin\Dashboard as AdminDashboard; // Dashboard Admin Operasional
use App\Livewire\Supervisor\Dashboard as SupervisorDashboard; // Dashboard Supervisor

// --- 2. MASTER DATA ---
use App\Livewire\Master\SalesIndex;
use App\Livewire\Master\ProdukIndex;
use App\Livewire\Master\SupplierIndex;
use App\Livewire\Master\UserIndex;

// --- 3. TRANSAKSI ---
use App\Livewire\Transaksi\PenjualanIndex;
use App\Livewire\Transaksi\ReturIndex;
use App\Livewire\Transaksi\ArIndex;
use App\Livewire\Transaksi\CollectionIndex;

// --- 4. LAPORAN REKAP ---
use App\Livewire\Laporan\RekapPenjualanIndex;
use App\Livewire\Laporan\RekapReturIndex;
use App\Livewire\Laporan\RekapArIndex;
use App\Livewire\Laporan\RekapCollectionIndex;
use App\Livewire\Laporan\KinerjaSalesIndex;

// --- 5. ANALISA PIMPINAN (Strategis & Keuangan) ---
use App\Livewire\Pimpinan\StockAnalysis;
use App\Livewire\Pimpinan\ProfitAnalysis;
use App\Livewire\Laporan\PusatCetak; 

// --- 6. SPK & DATA MINING  ---
use App\Livewire\Pimpinan\SpkSales; 
use App\Livewire\Pimpinan\RfmPelanggan;

// --- PROFILE ---
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // ====================================================
    // 0. REDIRECT DASHBOARD DINAMIS
    // ====================================================
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        
        // Cek Role dan arahkan ke dashboard yang sesuai
        if (in_array($role, ['supervisor'])) {
            return redirect()->route('supervisor.dashboard');
        } elseif (in_array($role, ['admin'])) {
            return redirect()->route('admin.dashboard');
        }
        
        // Default: Pimpinan & Super Admin diarahkan ke dashboard eksekutif
        return redirect()->route('pimpinan.dashboard');
    })->name('dashboard');

    // ====================================================
    // PROFILE ROUTES
    // ====================================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ====================================================
    // 1. DASHBOARD PIMPINAN & SUPERADMIN
    // ====================================================
    Route::middleware(['role:super_admin,superadmin,pimpinan'])->prefix('pimpinan')->group(function () {
        Route::get('/dashboard', DashboardIndex::class)->name('pimpinan.dashboard');
    });

    // ====================================================
    // 2. DASHBOARD ADMIN (Operasional)
    // ====================================================
    Route::middleware(['role:super_admin,superadmin,admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
    });

    // ====================================================
    // 3. DASHBOARD SUPERVISOR
    // ====================================================
    Route::middleware(['role:super_admin,superadmin,supervisor'])->prefix('supervisor')->group(function () {
        Route::get('/dashboard', SupervisorDashboard::class)->name('supervisor.dashboard');
    });

    // ====================================================
    // 4. MASTER DATA (Supervisor & Superadmin)
    // ====================================================
    Route::middleware(['role:super_admin,superadmin,supervisor'])->prefix('admin/master')->name('master.')->group(function () {
        Route::get('/sales', SalesIndex::class)->name('sales');
        Route::get('/produk', ProdukIndex::class)->name('produk');
        Route::get('/supplier', SupplierIndex::class)->name('supplier');
        
        // Akses Data Pengguna kini telah dibuka untuk Supervisor
        Route::get('/user', UserIndex::class)->name('user'); 
    });

    // ====================================================
    // 5. OPERASIONAL / TRANSAKSI (Admin & Superadmin)
    // ====================================================
    Route::middleware(['role:super_admin,superadmin,admin'])->prefix('admin/transaksi')->name('transaksi.')->group(function () {
        Route::get('/penjualan', PenjualanIndex::class)->name('penjualan');
        Route::get('/retur', ReturIndex::class)->name('retur');
        Route::get('/ar', ArIndex::class)->name('ar');
        Route::get('/collection', CollectionIndex::class)->name('collection');
    });

    // ====================================================
    // 6. ANALISA & LAPORAN (Pimpinan & Superadmin)
    // ====================================================
    Route::middleware(['role:super_admin,superadmin,pimpinan'])->prefix('admin')->group(function () {
        
        // LAPORAN REKAPITULASI (8 Laporan Awal)
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/rekap-penjualan', RekapPenjualanIndex::class)->name('rekap-penjualan');
            Route::get('/rekap-retur', RekapReturIndex::class)->name('rekap-retur');
            Route::get('/rekap-ar', RekapArIndex::class)->name('rekap-ar');
            Route::get('/rekap-collection', RekapCollectionIndex::class)->name('rekap-collection');
            Route::get('/kinerja-sales', KinerjaSalesIndex::class)->name('kinerja-sales');
        });

        // ANALISA PIMPINAN (Strategis)
        Route::get('/stock-analysis', StockAnalysis::class)->name('pimpinan.stock-analysis');
        Route::get('/profit-analysis', ProfitAnalysis::class)->name('pimpinan.profit-analysis');

        // PUSAT CETAK
        Route::get('/laporan/pusat-cetak', PusatCetak::class)->name('pusat-cetak');
        Route::get('/pdf/spk-sales', [SpkSales::class, 'generatePDF'])->name('spk-sales.pdf');
        
        // ====================================================
        // FITUR UTAMA (INTELLIGENT REPORTS)
        // ====================================================
        Route::prefix('keputusan')->name('keputusan.')->group(function () {
            Route::get('/spk-sales', SpkSales::class)->name('spk-sales');
            Route::get('/rfm-pelanggan', RfmPelanggan::class)->name('rfm-pelanggan');
        });
    });
});

require __DIR__.'/auth.php';