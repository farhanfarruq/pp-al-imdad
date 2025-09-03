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
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mengizinkan admin_utama DAN pengurus_umum untuk mengakses form laporan
    Route::get('/report/create', [ReportController::class, 'create'])->name('report.create')->middleware('role:admin_utama|pengurus_umum');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store')->middleware('role:admin_utama|pengurus_umum');
    
    Route::get('/report/{report}', [ReportController::class, 'show'])->name('report.show');
});

// Grup KHUSUS untuk Admin Utama
Route::middleware(['auth', 'verified', 'role:admin_utama'])->prefix('admin')->as('admin.')->group(function () {
    
    // Fitur Rekap
    Route::get('/rekap', [DashboardController::class, 'rekap'])->name('rekap');
    Route::get('/rekap/{pengurus}', [DashboardController::class, 'rekapDetail'])->name('rekap.detail');
    Route::post('/rekap/export', [ExportController::class, 'exportRekap'])->name('rekap.export');

    // Rute Master Data
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
        
        // Rute untuk User
        Route::put('/users/{user}', [MasterDataController::class, 'updateUser'])->name('users.update');
    });
});

require __DIR__.'/auth.php';