<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;

// Halaman login akan menjadi halaman utama jika belum terautentikasi
Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Laporan
    Route::get('/report/create/{bidang:slug}', [ReportController::class, 'create'])->name('report.create');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
    Route::get('/report/{report}', [ReportController::class, 'show'])->name('report.show');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware(['role:admin_utama|admin_bidang'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/rekap', [DashboardController::class, 'rekap'])->name('rekap');

        // Master Data (Hanya untuk Admin Utama)
        Route::middleware(['role:admin_utama'])->group(function() {
            Route::get('/master-data', [MasterDataController::class, 'index'])->name('master.index');
            // Pengurus
            Route::post('/master-data/pengurus', [MasterDataController::class, 'storePengurus'])->name('master.pengurus.store');
            Route::put('/master-data/pengurus/{pengurus}', [MasterDataController::class, 'updatePengurus'])->name('master.pengurus.update');
            Route::delete('/master-data/pengurus/{pengurus}', [MasterDataController::class, 'destroyPengurus'])->name('master.pengurus.destroy');
            // Jobdesk
            Route::post('/master-data/jobdesk', [MasterDataController::class, 'storeJobdesk'])->name('master.jobdesk.store');
            Route::put('/master-data/jobdesk/{jobdesk}', [MasterDataController::class, 'updateJobdesk'])->name('master.jobdesk.update');
            Route::delete('/master-data/jobdesk/{jobdesk}', [MasterDataController::class, 'destroyJobdesk'])->name('master.jobdesk.destroy');
        });

        // Export
        Route::get('/export/csv', [ExportController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');
    });
});


require __DIR__.'/auth.php';