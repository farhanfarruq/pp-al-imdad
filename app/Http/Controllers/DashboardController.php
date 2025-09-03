<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Pengurus;
use App\Models\Report;
use App\Models\ReportTask;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $bidangList = Bidang::all();
        $myReports = [];

        if ($user->hasRole('pengurus_umum')) {
            $myReports = Report::where('user_id', $user->id)
                ->with(['bidang', 'pengurus'])
                ->latest()
                ->paginate(10);
        }

        return Inertia::render('Dashboard', [
            'bidangList' => $bidangList,
            'myReports' => $myReports
        ]);
    }

    /**
     * Menampilkan halaman rekap summary per pengurus (HANYA UNTUK ADMIN).
     */
    public function rekap()
    {
        $pengurusSummary = Pengurus::with('bidang')
            ->withCount(['reportTasks' => function ($query) {
                $query->where('done', false);
            }])
            ->orderBy('nama')
            ->get();

        return Inertia::render('Admin/Rekap', [
            'pengurusSummary' => $pengurusSummary,
        ]);
    }

    /**
     * Menampilkan halaman detail tugas seorang pengurus (HANYA UNTUK ADMIN).
     */
    public function rekapDetail(Pengurus $pengurus)
    {
        // Ambil ID semua report yang dimiliki pengurus ini
        $reportIds = $pengurus->reports()->pluck('id');

        // Ambil semua tugas yang gagal dari report-report tersebut
        $failedTasks = ReportTask::whereIn('report_id', $reportIds)
            ->where('done', false)
            ->with(['report.bidang', 'report.user', 'jobdesk'])
            ->latest()
            ->paginate(15);
            
        return Inertia::render('Admin/RekapDetail', [
            'pengurus' => $pengurus->load('bidang'),
            'failedTasks' => $failedTasks,
        ]);
    }
}
