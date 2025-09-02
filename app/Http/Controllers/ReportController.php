<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Jobdesk;
use App\Models\Pengurus;
use App\Models\Report;
use App\Models\ReportTask;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function create(Bidang $bidang)
    {
        return Inertia::render('Report/Form', [
            'bidang' => $bidang,
            'pengurusList' => Pengurus::where('bidang_id', $bidang->id)->get(),
            'jobdeskList' => Jobdesk::where('bidang_id', $bidang->id)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'pengurus_id' => 'required|exists:penguruses,id',
            'bidang_id' => 'required|exists:bidangs,id',
            'waktu' => 'nullable|in:malam,subuh',
            'tasks' => 'required|array',
            'bukti' => 'nullable|file|mimes:jpg,png,pdf|max:5120', // 5MB
        ]);

        DB::beginTransaction();
        try {
            $report = Report::create([
                'user_id' => Auth::id(),
                'bidang_id' => $request->bidang_id,
                'pengurus_id' => $request->pengurus_id,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
            ]);

            foreach ($request->tasks as $task) {
                ReportTask::create([
                    'report_id' => $report->id,
                    'jobdesk_id' => $task['id'],
                    'status' => $task['status'],
                    'alasan' => $task['status'] === 'tidak_selesai' ? $task['alasan'] : null,
                    'solusi' => $task['status'] === 'tidak_selesai' ? $task['solusi'] : null,
                ]);
            }

            if ($request->hasFile('bukti')) {
                $filePath = $request->file('bukti')->store('bukti', 'public');
                Upload::create([
                    'report_id' => $report->id,
                    'file_path' => $filePath,
                    'file_name' => $request->file('bukti')->getClientOriginalName(),
                ]);
            }

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Laporan berhasil disubmit!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan laporan: ' . $e->getMessage());
        }
    }

    public function show(Report $report)
    {
        $this->authorize('view', $report); // Policy
        
        $report->load(['bidang', 'pengurus', 'user', 'tasks.jobdesk', 'upload']);
        return Inertia::render('Report/Detail', ['report' => $report]);
    }
}