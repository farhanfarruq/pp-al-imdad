<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Jobdesk;
use App\Models\Pengurus;
use App\Models\Report;
use App\Models\ReportTask;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $bidang = $user->bidang;

        if (!$bidang) {
            return Redirect::route('dashboard')->with('error', 'Anda tidak terhubung dengan bidang manapun. Silakan hubungi admin.');
        }

        $jobdeskList = Jobdesk::where('bidang_id', $bidang->id)->get();
        $pengurusList = Pengurus::where('bidang_id', $bidang->id)->orderBy('nama')->get();

        return Inertia::render('Report/Form', [
            'bidang' => $bidang,
            'jobdeskList' => $jobdeskList,
            'pengurusList' => $pengurusList
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'pengurus_id' => 'required|exists:penguruses,id',
            'tanggal' => 'required|date',
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:jobdesks,id',
            'tasks.*.status' => 'required|in:selesai,tidak_selesai',
            'tasks.*.alasan' => 'nullable|string',
            'tasks.*.solusi' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $report = Report::create([
            'user_id' => auth()->id(),
            'bidang_id' => $request->bidang_id,
            'pengurus_id' => $request->pengurus_id,
            'tanggal' => $request->tanggal,
        ]);

        foreach ($request->tasks as $task) {
            ReportTask::create([
                'report_id' => $report->id,
                'jobdesk_id' => $task['id'],
                'done' => $task['status'] === 'selesai', // LOGIKA DIPERBAIKI
                'alasan' => $task['status'] === 'tidak_selesai' ? $task['alasan'] : null,
                'solusi' => $task['status'] === 'tidak_selesai' ? $task['solusi'] : null,
            ]);
        }
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('reports', $filename, 'public');

                Upload::create([
                    'report_id' => $report->id,
                    'filename' => $filename,
                    'filepath' => $path,
                ]);
            }
        }

        return Redirect::route('dashboard')->with('success', 'Laporan berhasil dibuat.');
    }

    public function show(Report $report)
    {
        $this->authorize('view', $report);

        $report->load(['bidang', 'pengurus', 'user', 'tasks.jobdesk', 'uploads']);

        return Inertia::render('Report/Detail', [
            'report' => $report
        ]);
    }
}