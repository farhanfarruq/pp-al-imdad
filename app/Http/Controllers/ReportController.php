<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Bidang;
use App\Models\Jobdesk;
use App\Models\ReportTask;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller 
{
    // TIDAK ADA METHOD __construct() DI SINI. SENGAJA DIHAPUS.

    public function index()
    {
        $reports = Report::with(['user', 'bidang'])->latest()->paginate(9);
        return Inertia::render('Reports/Index', ['reports' => $reports]);
    }

    public function show(Report $report)
    {
        $report->load(['user', 'bidang', 'tasks.jobdesk', 'uploads']);
        return Inertia::render('Reports/Show', ['report' => $report]);
    }

    public function create()
    {
        $bidangs = Bidang::all();
        $jobdesks = Jobdesk::all();
        return Inertia::render('Report/Form', [
            'bidangs' => $bidangs,
            'jobdesks' => $jobdesks,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'bidang_id' => 'required|exists:bidangs,id',
            'date' => 'required|date',
            'description' => 'required|string',
            'tasks' => 'required|array',
            'uploads.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);
        DB::transaction(function () use ($request) {
            $report = Report::create([
                'user_id' => Auth::id(),
                'bidang_id' => $request->bidang_id,
                'title' => $request->title,
                'date' => $request->date,
                'description' => $request->description,
            ]);
            foreach ($request->tasks as $task) {
                ReportTask::create(['report_id' => $report->id, 'jobdesk_id' => $task['jobdesk_id'], 'is_done' => $task['is_done']]);
            }
            if ($request->hasFile('uploads')) {
                foreach ($request->file('uploads') as $file) {
                    $path = $file->store('uploads', 'public');
                    Upload::create(['report_id' => $report->id, 'file_path' => $path, 'file_name' => $file->getClientOriginalName()]);
                }
            }
        });
        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dibuat.');
    }

    public function edit(Report $report)
    {
        $this->authorize('update', $report);
        $bidangs = Bidang::all();
        $jobdesks = Jobdesk::all();
        $report->load('tasks');
        return Inertia::render('Report/Form', [
            'report' => $report,
            'bidangs' => $bidangs,
            'jobdesks' => $jobdesks,
        ]);
    }

    public function update(Request $request, Report $report)
    {
        $this->authorize('update', $report);
        $request->validate([
            'title' => 'required|string|max:255',
            'bidang_id' => 'required|exists:bidangs,id',
            'date' => 'required|date',
            'description' => 'required|string',
            'tasks' => 'required|array',
        ]);
        DB::transaction(function () use ($request, $report) {
            $report->update($request->only('title', 'bidang_id', 'date', 'description'));
            $report->tasks()->delete();
            foreach ($request->tasks as $task) {
                ReportTask::create(['report_id' => $report->id, 'jobdesk_id' => $task['jobdesk_id'], 'is_done' => $task['is_done']]);
            }
        });
        return redirect()->route('dashboard')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);
        DB::transaction(function () use ($report) {
            foreach ($report->uploads as $upload) {
                Storage::disk('public')->delete($upload->file_path);
            }
            $report->delete();
        });
        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dihapus.');
    }

    public function rekap(Request $request)
    {
        $query = Report::with(['user', 'bidang', 'tasks.jobdesk'])->latest();
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) { $q->where('name', 'like', '%' . $request->search . '%'); });
        }
        if ($request->filled('bidang')) {
            $query->where('bidang_id', $request->bidang);
        }
        $reports = $query->paginate(10)->withQueryString();
        return Inertia::render('Admin/Rekap', ['reports' => $reports, 'bidangs' => Bidang::all(), 'filters' => $request->only(['search', 'bidang'])]);
    }

    public function rekapDetail(Report $report)
    {
        $report->load(['user', 'bidang', 'tasks.jobdesk', 'uploads']);
        $tasks = $report->tasks->groupBy('is_done');
        $successTasks = $tasks->get(1, collect());
        $failedTasks = $tasks->get(0, collect());
        return Inertia::render('Admin/RekapDetail', ['report' => $report, 'successTasks' => $successTasks, 'failedTasks' => $failedTasks]);
    }
}