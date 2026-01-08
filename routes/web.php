<?php

use Illuminate\Support\Facades\Route;

// --- 1. IMPORT DASHBOARD ---
use App\Livewire\Admin\Dashboard as AdminDashboard;

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

// --- 5. ANALISA & PIMPINAN ---
use App\Livewire\Pimpinan\StockAnalysis;
use App\Livewire\Pimpinan\ProfitAnalysis;
use App\Livewire\Laporan\PusatCetak; // <--- WAJIB ADA INI

// --- PROFILE ---
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // REDIRECT DASHBOARD
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ====================================================
    // AREA CENTRAL (Semua Role: super_admin, pimpinan, supervisor, admin)
    // ====================================================
    Route::middleware(['role:super_admin,pimpinan,supervisor,admin'])->prefix('admin')->group(function () {
        
        Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');

        // MASTER DATA
        Route::prefix('master')->name('master.')->group(function () {
            Route::get('/sales', SalesIndex::class)->name('sales');
            Route::get('/produk', ProdukIndex::class)->name('produk');
            Route::get('/supplier', SupplierIndex::class)->name('supplier');
            
            // User tetap dibatasi hanya Super Admin agar aman
            Route::middleware(['role:super_admin'])->get('/user', UserIndex::class)->name('user'); 
        });

        // TRANSAKSI
        Route::prefix('transaksi')->name('transaksi.')->group(function () {
            Route::get('/penjualan', PenjualanIndex::class)->name('penjualan');
            Route::get('/retur', ReturIndex::class)->name('retur');
            Route::get('/ar', ArIndex::class)->name('ar');
            Route::get('/collection', CollectionIndex::class)->name('collection');
        });

        // LAPORAN REKAP
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/rekap-penjualan', RekapPenjualanIndex::class)->name('rekap-penjualan');
            Route::get('/rekap-retur', RekapReturIndex::class)->name('rekap-retur');
            Route::get('/rekap-ar', RekapArIndex::class)->name('rekap-ar');
            Route::get('/rekap-collection', RekapCollectionIndex::class)->name('rekap-collection');
            Route::get('/kinerja-sales', KinerjaSalesIndex::class)->name('kinerja-sales');
        });

        // ANALISA PIMPINAN
        Route::get('/stock-analysis', StockAnalysis::class)->name('pimpinan.stock-analysis');
        Route::get('/profit-analysis', ProfitAnalysis::class)->name('pimpinan.profit-analysis');

        // PUSAT CETAK (Ditaruh diluar prefix 'laporan.' agar namanya pas 'pusat-cetak')
        // Ini sinkron dengan sidebar: route('pusat-cetak')
        Route::get('/laporan/pusat-cetak', PusatCetak::class)->name('pusat-cetak');
    });

});

require __DIR__.'/auth.php';