<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// RUTE PUBLIK (TIDAK PERLU LOGIN)
Route::get('/', function () {
    return Inertia::render('Welcome', [ 'canLogin' => Route::has('login'), 'canRegister' => Route::has('register') ]);
});
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');

// RUTE YANG HARUS LOGIN
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/report/create', [ReportController::class, 'create'])->name('report.create');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
    Route::get('/report/{report}/edit', [ReportController::class, 'edit'])->name('report.edit');
    Route::put('/report/{report}', [ReportController::class, 'update'])->name('report.update');
    Route::delete('/report/{report}', [ReportController::class, 'destroy'])->name('report.destroy');
});

// RUTE KHUSUS ADMIN UTAMA
Route::middleware(['auth', 'role:admin_utama'])->prefix('admin')->group(function () {
    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data.index');
    Route::post('/master-data/bidang', [MasterDataController::class, 'storeBidang'])->name('master-data.storeBidang');
    Route::post('/master-data/jobdesk', [MasterDataController::class, 'storeJobdesk'])->name('master-data.storeJobdesk');
    Route::put('/master-data/user/{user}', [MasterDataController::class, 'updateUser'])->name('master-data.updateUser');

    Route::get('/rekap', [ReportController::class, 'rekap'])->name('admin.rekap');
    Route::get('/rekap/{report}', [ReportController::class, 'rekapDetail'])->name('admin.rekap.detail');
    
    Route::get('/export/rekap/excel', [ExportController::class, 'exportRekapToExcel'])->name('export.rekap.excel');
    Route::get('/export/rekap/pdf', [ExportController::class, 'exportRekapToPdf'])->name('export.rekap.pdf');
});

require __DIR__.'/auth.php';