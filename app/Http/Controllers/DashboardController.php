<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Bidang::query();

        // Jika user adalah admin bidang, hanya tampilkan bidangnya saja
        if ($user->hasRole('admin_bidang')) {
            $query->where('id', $user->bidang_id);
        }

        return Inertia::render('Dashboard', [
            'bidangList' => $query->get(['id', 'slug', 'name', 'icon', 'color']),
            'userRoles' => $user->getRoleNames()
        ]);
    }

    public function rekap(Request $request)
    {
        $query = Report::with(['bidang', 'pengurus', 'user']);

        // Filter berdasarkan role
        if (Auth::user()->hasRole('admin_bidang')) {
            $query->where('bidang_id', Auth::user()->bidang_id);
        }

        // Filter berdasarkan request
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('bidang_id')) {
            $query->where('bidang_id', $request->bidang_id);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        $reports->getCollection()->transform(function ($report) {
            $totalTasks = $report->tasks()->count();
            $completedTasks = $report->tasks()->where('status', 'selesai')->count();
            $report->completion_rate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
            $report->completed_tasks = $completedTasks;
            $report->total_tasks = $totalTasks;
            return $report;
        });

        return Inertia::render('Admin/Rekap', [
            'reports' => $reports,
            'filters' => $request->only(['tanggal_mulai', 'tanggal_akhir', 'bidang_id']),
            'bidangList' => Bidang::all(['id', 'name']),
        ]);
    }
}