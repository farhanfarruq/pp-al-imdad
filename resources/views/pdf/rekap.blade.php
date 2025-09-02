<!doctype html><html><head><meta charset="utf-8"><style>table{width:100%;border-collapse:collapse}td,th{border:1px solid #ddd;padding:6px}</style></head>
<body>
<h3>Rekap Laporan</h3>
<table><thead><tr><th>Tanggal</th><th>Bidang</th><th>Pengurus</th><th>Persentase</th></tr></thead><tbody>
@foreach($reports as $r)
@php($t=max(1,$r->tasks->count()))
@php($d=$r->tasks->where('done',true)->count())
<tr><td>{{ $r->tanggal->format('Y-m-d') }}</td><td>{{ $r->bidang->name }}</td><td>{{ $r->pengurus_nama }}</td><td>{{ round($d/$t*100) }}%</td></tr>
@endforeach
</tbody></table>
</body></html>