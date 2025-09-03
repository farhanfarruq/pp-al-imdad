<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Report;
use App\Models\ReportTask; // 1. Tambahkan model ReportTask
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('roles'); 

        $bidangList = collect();
        $reports = collect();

        if ($user->hasRole('admin_utama') || $user->hasRole('pengurus')) {
            $bidangList = Bidang::all();
        }

        if ($user->hasRole('admin_bidang') && $user->bidang_id) {
            $reports = Report::where('bidang_id', $user->bidang_id)
                ->with(['bidang', 'user'])
                ->latest()
                ->get();
            $bidangList = Bidang::where('id', $user->bidang_id)->get();
        }

        return Inertia::render('Dashboard', [
            'bidangList' => $bidangList,
            'reports' => $reports,
        ]);
    }

    // --- MULAI PERUBAHAN DI SINI ---
    // app/Http/Controllers/DashboardController.php

public function rekap(Request $request)
{
    // Query sekarang dimulai dari ReportTask
    $query = ReportTask::where('done', false) // <-- PERBAIKAN FINAL DAN PASTI BENAR
        ->with([
            'report.bidang',
            'report.user',
            'report.pengurus',
            'jobdesk'
        ]);

    // Terapkan filter tanggal pada data LAPORAN-nya
    $query->whereHas('report', function ($q) use ($request) {
        if ($request->filled('tanggal_mulai')) {
            $q->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $q->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
    });

    // Terapkan filter bidang pada data LAPORAN-nya
    if ($request->filled('bidang_id')) {
        $query->whereHas('report', function ($q) use ($request) {
            $q->where('bidang_id', $request->bidang_id);
        });
    }

    $failedTasks = $query->latest()->paginate(15)->withQueryString();

    return Inertia::render('Admin/Rekap', [
        'failedTasks' => $failedTasks,
        'filters' => $request->only(['tanggal_mulai', 'tanggal_akhir', 'bidang_id']),
        'bidangList' => Bidang::all(['id', 'name']),
    ]);
}
    // --- AKHIR PERUBAHAN ---
}