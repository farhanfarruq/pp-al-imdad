<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use Inertia\Inertia; use Illuminate\Support\Facades\Auth; use Illuminate\Support\Facades\Storage;
use App\Models\{Bidang, Jobdesk, Report, ReportTask, Upload};


class ReportController extends Controller {
public function create(Bidang $bidang){
$jobdesks = Jobdesk::where('bidang_id',$bidang->id)->orderBy('bpk_tab')->get();
return Inertia::render('Report/Form', [
'bidang'=>$bidang,
'jobdesks'=>$jobdesks,
]);
}


public function store(Request $r){
$r->validate([
'tanggal'=>'required|date',
'bidang_id'=>'required|exists:bidangs,id',
'pengurus_nama'=>'required|string',
'bpk_tab'=>'nullable|in:malam,subuh',
'tasks'=>'required|array', // [{jobdesk_id, done, alasan, solusi}]
'bukti.*'=>'file|mimes:jpg,jpeg,png,pdf|max:5120'
]);
$report = Report::create([
'tanggal'=>$r->tanggal,
'bidang_id'=>$r->bidang_id,
'pengurus_nama'=>$r->pengurus_nama,
'bpk_tab'=>$r->bpk_tab,
'user_id'=>Auth::id(),
]);
foreach($r->tasks as $t){
ReportTask::create([
'report_id'=>$report->id,
'jobdesk_id'=>$t['jobdesk_id'],
'done'=> (bool)($t['done'] ?? false),
'alasan'=>$t['alasan'] ?? null,
'solusi'=>$t['solusi'] ?? null,
]);
}
if($r->hasFile('bukti')){
foreach($r->file('bukti') as $file){
$path = $file->store('reports','public');
Upload::create([
'report_id'=>$report->id,
'path'=>$path,
'mime'=>$file->getClientMimeType(),
'size'=>$file->getSize(),
]);
}
}
return redirect()->route('report.show',$report)->with('success','Laporan tersimpan');
}


public function show(Report $report){
$report->load(['bidang','tasks.jobdesk','uploads']);
return Inertia::render('Report/Detail', ['report'=>$report]);
}
}