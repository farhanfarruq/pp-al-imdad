<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| File ini mengatur semua routing aplikasi.
*/

// Halaman utama: Arahkan ke login jika belum masuk, atau ke dashboard jika sudah.
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return Inertia::render('Auth/Login');
});

// Grup untuk semua pengguna yang sudah login dan terverifikasi.
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Fitur Laporan
    Route::get('/report/create/{bidang:slug}', [ReportController::class, 'create'])->name('report.create');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
    Route::get('/report/{report}', [ReportController::class, 'show'])->name('report.show');

    // Fitur Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Grup KHUSUS untuk Admin Utama, dilindungi oleh middleware 'role:admin_utama'.
Route::middleware(['auth', 'verified', 'role:admin_utama'])->prefix('admin')->as('admin.')->group(function () {
    
    // Fitur Rekap
    Route::get('/rekap', [DashboardController::class, 'rekap'])->name('rekap');
    Route::get('/rekap/{pengurus}', [DashboardController::class, 'rekapDetail'])->name('rekap.detail');
    Route::post('/rekap/export', [ExportController::class, 'exportRekap'])->name('rekap.export');

    // Fitur Master Data (CRUD)
    Route::prefix('master-data')->as('master.')->group(function() {
        Route::get('/', [MasterDataController::class, 'index'])->name('index');

        // Rute untuk Pengurus
        Route::post('/pengurus', [MasterDataController::class, 'storePengurus'])->name('pengurus.store');
        Route::put('/pengurus/{pengurus}', [MasterDataController::class, 'updatePengurus'])->name('pengurus.update');
        Route::delete('/pengurus/{pengurus}', [MasterDataController::class, 'destroyPengurus'])->name('pengurus.destroy');

        // Rute untuk Jobdesk
        Route::post('/jobdesk', [MasterDataController::class, 'storeJobdesk'])->name('jobdesk.store');
        Route::put('/jobdesk/{jobdesk}', [MasterDataController::class, 'updateJobdesk'])->name('jobdesk.update');
        Route::delete('/jobdesk/{jobdesk}', [MasterDataController::class, 'destroyJobdesk'])->name('jobdesk.destroy');
    });
});

// Memuat rute untuk autentikasi (login, register, dll.)
require __DIR__.'/auth.php';

