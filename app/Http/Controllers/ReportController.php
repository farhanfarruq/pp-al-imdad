<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Jobdesk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (is_null(Auth::user()->bidang_id)) {
            return redirect()->back()->with('error', 'Anda tidak terhubung dengan bidang manapun. Silakan hubungi admin.');
        }

        if (!Gate::allows('create', Report::class)) {
            abort(403);
        }

        $user = Auth::user();
        $jobdesks = Jobdesk::where('bidang_id', $user->bidang_id)->get();

        return Inertia::render('Report/Form', [
            'jobdesks' => $jobdesks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (is_null(Auth::user()->bidang_id)) {
            return redirect()->back()->with('error', 'Anda tidak terhubung dengan bidang manapun. Silakan hubungi admin.');
        }

        if (!Gate::allows('create', Report::class)) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'tasks' => 'required|array|min:1',
            'tasks.*.jobdesk_id' => 'required|exists:jobdesks,id',
            'tasks.*.description' => 'required|string',
            'tasks.*.status' => 'required|in:terlaksana,tidak terlaksana',
            'uploads.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        $report = Report::create([
            'user_id' => Auth::id(),
            'bidang_id' => Auth::user()->bidang_id,
            'title' => $request->title,
            'date' => $request->date,
        ]);

        foreach ($request->tasks as $taskData) {
            $report->tasks()->create($taskData);
        }

        if ($request->hasFile('uploads')) {
            foreach ($request->file('uploads') as $file) {
                $path = $file->store('reports', 'public');
                $report->uploads()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName()
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        $report->load('user', 'bidang', 'tasks.jobdesk', 'uploads');
        return Inertia::render('Report/Detail', [
            'report' => $report,
        ]);
    }

    // Metode untuk admin melihat semua laporan
    public function rekap()
    {
        $reports = Report::with('user', 'bidang')->latest()->paginate(10);
        return Inertia::render('Admin/Rekap', [
            'reports' => $reports
        ]);
    }

    // Metode untuk admin melihat detail laporan
    public function rekapDetail(Report $report)
    {
        $report->load('user', 'bidang', 'tasks.jobdesk', 'uploads');
        return Inertia::render('Admin/RekapDetail', [
            'report' => $report
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}