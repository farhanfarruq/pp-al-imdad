<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use App\Models\{Report}; use Symfony\Component\HttpFoundation\StreamedResponse; use Barryvdh\DomPDF\Facade\Pdf;


class ExportController extends Controller {
public function csv(Request $r){
$fileName = 'laporan_'.now()->toDateString().'.csv';
$reports = Report::with('bidang','tasks')->when($r->start, fn($q)=>$q->whereDate('tanggal','>=',$r->start))
->when($r->end, fn($q)=>$q->whereDate('tanggal','<=',$r->end))
->get();
$response = new StreamedResponse(function() use ($reports){
$handle = fopen('php://output','w');
fputcsv($handle,['Tanggal','Bidang','Pengurus','Persentase']);
foreach($reports as $rep){
$total = max(1,$rep->tasks->count());
$done = $rep->tasks->where('done',true)->count();
$pct = round($done/$total*100);
fputcsv($handle,[$rep->tanggal->toDateString(),$rep->bidang->name,$rep->pengurus_nama,$pct.'%']);
}
fclose($handle);
});
$response->headers->set('Content-Type','text/csv');
$response->headers->set('Content-Disposition','attachment; filename="'.$fileName.'"');
return $response;
}


public function pdf(Request $r){
// install dulu: composer require barryvdh/laravel-dompdf
$reports = Report::with('bidang','tasks')->get();
$pdf = Pdf::loadView('pdf.rekap', compact('reports'));
return $pdf->download('rekap.pdf');
}
}