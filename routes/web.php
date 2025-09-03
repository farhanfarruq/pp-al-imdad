<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MasterDataController;

// Redirect halaman utama ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Grup untuk semua user yang sudah terotentikasi (login)
Route::middleware('auth')->group(function () {
    // Rute Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Laporan (Bisa diakses semua user yang login)
    Route::get('/report/create', [ReportController::class, 'create'])->name('report.create');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
    Route::get('/report/{report}', [ReportController::class, 'show'])->name('report.show');

    // Grup KHUSUS untuk Admin Panel (Hanya bisa diakses oleh 'admin_utama')
    Route::middleware('role:admin_utama')->group(function () {
        // Menggunakan nama fungsi yang benar: rekap() dan rekapDetail()
        Route::get('/admin/rekap', [ReportController::class, 'rekap'])->name('admin.rekap');
        Route::get('/admin/rekap/{report}', [ReportController::class, 'rekapDetail'])->name('admin.rekap.detail');
        
        Route::get('/admin/master-data', [MasterDataController::class, 'index'])->name('admin.master-data');

        // Rute untuk ekspor data (hanya admin)
        Route::get('/export/rekap/excel', [ExportController::class, 'exportRekapToExcel'])->name('export.rekap.excel');
        Route::get('/export/rekap/pdf', [ExportController::class, 'exportRekapToPdf'])->name('export.rekap.pdf');
    });
});

// Memuat rute-rute otentikasi (login, register, dll.)
require __DIR__.'/auth.php';