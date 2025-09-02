<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController, ReportController, MasterDataController, ExportController};


Route::get('/', fn()=>redirect()->route('dashboard'))->middleware('auth');


Route::middleware(['auth'])->group(function(){
Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');


// Report
Route::get('/report/create/{bidang:slug}', [ReportController::class,'create'])->name('report.create');
Route::post('/report', [ReportController::class,'store'])->name('report.store');
Route::get('/report/{report}', [ReportController::class,'show'])->name('report.show');


// Admin Rekap
Route::get('/admin/rekap', [DashboardController::class,'rekap'])->middleware('role:admin_utama|admin_bidang')->name('admin.rekap');


// Master Data (Admin Utama saja)
Route::middleware('role:admin_utama')->group(function(){
Route::get('/admin/master', [MasterDataController::class,'index'])->name('admin.master');
Route::post('/admin/master/pengurus', [MasterDataController::class,'storePengurus']);
Route::put('/admin/master/pengurus/{pengurus}', [MasterDataController::class,'updatePengurus']);
Route::delete('/admin/master/pengurus/{pengurus}', [MasterDataController::class,'destroyPengurus']);


Route::post('/admin/master/jobdesk', [MasterDataController::class,'storeJobdesk']);
Route::put('/admin/master/jobdesk/{jobdesk}', [MasterDataController::class,'updateJobdesk']);
Route::delete('/admin/master/jobdesk/{jobdesk}', [MasterDataController::class,'destroyJobdesk']);
});


// Export
Route::get('/export/csv', [ExportController::class,'csv'])->name('export.csv');
Route::get('/export/pdf', [ExportController::class,'pdf'])->name('export.pdf');
});


require __DIR__.'/auth.php';