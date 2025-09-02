<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    private function getFilteredReports(Request $request)
    {
        $query = Report::with(['bidang', 'pengurus', 'user', 'tasks']);

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

        return $query->latest()->get();
    }
    
    public function exportCsv(Request $request)
    {
        $reports = $this->getFilteredReports($request);
        $fileName = 'laporan_tugas_harian.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tanggal', 'Bidang', 'Pengurus', 'Status', 'Tugas Selesai', 'Total Tugas', 'Persentase'];

        $callback = function() use($reports, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($reports as $report) {
                $totalTasks = $report->tasks->count();
                $completedTasks = $report->tasks->where('status', 'selesai')->count();
                $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

                $row['Tanggal']  = $report->tanggal;
                $row['Bidang']    = $report->bidang->name;
                $row['Pengurus']  = $report->pengurus->nama;
                $row['Status']  = $percentage === 100 ? 'Selesai Penuh' : ($percentage > 0 ? 'Selesai Sebagian' : 'Tidak Selesai');
                $row['Tugas Selesai'] = $completedTasks;
                $row['Total Tugas'] = $totalTasks;
                $row['Persentase'] = $percentage . '%';

                fputcsv($file, array($row['Tanggal'], $row['Bidang'], $row['Pengurus'], $row['Status'], $row['Tugas Selesai'], $row['Total Tugas'], $row['Persentase']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $reports = $this->getFilteredReports($request);
        $data = [
            'reports' => $reports,
            'title' => 'Rekap Laporan Tugas Harian',
            'date' => date('d/m/Y'),
            'filters' => $request->only(['tanggal_mulai', 'tanggal_akhir', 'bidang_id']),
            'bidangList' => Bidang::pluck('name', 'id')
        ];

        $pdf = PDF::loadView('pdf.rekap', $data);
        return $pdf->download('rekap-laporan.pdf');
    }
}