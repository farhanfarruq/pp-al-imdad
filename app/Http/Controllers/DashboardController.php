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

        if ($user->hasRole('pengurus_umum') || $user->hasRole('admin_utama')) {
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

    public function rekap()
    {
        $pengurusSummary = Pengurus::with('bidang')
            ->withCount([
                'reportTasks as tugas_selesai_count' => function ($query) {
                    $query->where('done', true);
                },
                'reportTasks as tugas_tidak_dikerjakan_count' => function ($query) {
                    $query->where('done', false);
                }
            ])
            ->orderBy('nama')
            ->get();

        return Inertia::render('Admin/Rekap', [
            'pengurusSummary' => $pengurusSummary,
        ]);
    }

    public function rekapDetail(Pengurus $pengurus)
    {
        $reportIds = $pengurus->reports()->pluck('id');

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