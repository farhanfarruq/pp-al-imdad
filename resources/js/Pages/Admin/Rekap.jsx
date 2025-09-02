// resources/js/Pages/Admin/Rekap.jsx
import React from 'react';
import { Head, usePage, router } from '@inertiajs/react';
// Jika route() tidak terdefinisi, install ziggy & import:
// import route from 'ziggy-js';

export default function Rekap() {
  const { data } = usePage().props; // pagination dari controller
  const [start, setStart] = React.useState('');
  const [end, setEnd] = React.useState('');
  const [bidang, setBidang] = React.useState('');

  function filter() {
    router.get(route('admin.rekap'), { start, end, bidang }, { preserveState: true, preserveScroll: true });
  }

  const rows = Array.isArray(data?.data) ? data.data : [];

  return (
    <div className="p-6 max-w-7xl mx-auto">
      <Head title="Rekap Admin" />
      <h2 className="text-2xl font-bold mb-4">Rekap Laporan</h2>

      <div className="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
        <input
          type="date"
          value={start}
          onChange={(e) => setStart(e.target.value)}
          className="border rounded px-3 py-2"
        />
        <input
          type="date"
          value={end}
          onChange={(e) => setEnd(e.target.value)}
          className="border rounded px-3 py-2"
        />
        <input
          placeholder="slug bidang (opsional)"
          value={bidang}
          onChange={(e) => setBidang(e.target.value)}
          className="border rounded px-3 py-2"
        />
        <button onClick={filter} className="px-4 py-2 bg-blue-600 text-white rounded">
          Filter
        </button>
      </div>

      <div className="overflow-x-auto">
        <table className="min-w-full border">
          <thead>
            <tr className="bg-gray-50 text-left">
              <th className="p-2">Tanggal</th>
              <th className="p-2">Bidang</th>
              <th className="p-2">Pengurus</th>
              <th className="p-2">Persentase</th>
            </tr>
          </thead>
          <tbody>
            {rows.length === 0 ? (
              <tr>
                <td colSpan={4} className="p-4 text-center text-gray-500">
                  Tidak ada data
                </td>
              </tr>
            ) : (
              rows.map((r) => {
                const tasks = Array.isArray(r.tasks) ? r.tasks : [];
                const total = Math.max(1, tasks.length);
                const done = tasks.filter((x) => x.done).length;
                const pct = Math.round((done / total) * 100);

                return (
                  <tr key={r.id} className="border-t">
                    <td className="p-2">{r.tanggal}</td>
                    <td className="p-2">{r.bidang?.name ?? '-'}</td>
                    <td className="p-2">{r.pengurus_nama}</td>
                    <td className="p-2">{pct}%</td>
                  </tr>
                );
              })
            )}
          </tbody>
        </table>
      </div>

      {/* Contoh tombol export, kalau mau dipasang di sini juga:*/}
      <div className="mt-4 flex gap-2">
        <a className="px-3 py-2 bg-green-600 text-white rounded" href={route('export.csv', { start, end, bidang })}>
          Export CSV
        </a>
        <a className="px-3 py-2 bg-red-600 text-white rounded" href={route('export.pdf', { start, end, bidang })}>
          Export PDF
        </a>
      </div>
    </div>
  );
}
