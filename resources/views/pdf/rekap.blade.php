<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .badge { display: inline-block; padding: .25em .4em; font-size: 75%; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; }
        .badge-success { color: #fff; background-color: #28a745; }
        .badge-warning { color: #212529; background-color: #ffc107; }
        .badge-danger { color: #fff; background-color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>PP Al Imdad Putra</p>
        <p>Tanggal Cetak: {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Bidang</th>
                <th>Pengurus</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $index => $report)
                @php
                    $totalTasks = $report->tasks->count();
                    $completedTasks = $report->tasks->where('status', 'selesai')->count();
                    $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                    $statusClass = '';
                    if ($percentage == 100) $statusClass = 'badge-success';
                    elseif ($percentage > 0) $statusClass = 'badge-warning';
                    else $statusClass = 'badge-danger';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $report->bidang->name }}</td>
                    <td>{{ $report->pengurus->nama }}</td>
                    <td class="text-center">
                        <span class="badge {{ $statusClass }}">
                            {{ $percentage }}% ({{$completedTasks}}/{{$totalTasks}})
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data laporan yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>