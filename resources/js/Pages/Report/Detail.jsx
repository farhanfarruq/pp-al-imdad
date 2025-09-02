// resources/js/Pages/Admin/Detail.jsx
import React from 'react';
import { Head, usePage } from '@inertiajs/react';

export default function Detail() {
  const { report } = usePage().props;

  const total = Math.max(1, report?.tasks?.length || 0);
  const done = (report?.tasks || []).filter((t) => t.done).length;
  const pct = Math.round((done / total) * 100);

  return (
    <div className="p-6 max-w-4xl mx-auto">
      <Head title="Detail Laporan" />
      <h2 className="text-xl font-bold mb-2">Detail Laporan</h2>

      <div className="text-sm text-gray-600 mb-4">
        {report?.tanggal} • {report?.bidang?.name} • {report?.pengurus_nama}
      </div>

      <div className="mb-4">
        Persentase: <span className="font-semibold">{pct}%</span>
      </div>

      <div className="space-y-2">
        {(report?.tasks || []).map((t) => (
          <div
            key={t.id}
            className={`p-3 border rounded ${t.done ? 'bg-green-50' : 'bg-red-50'}`}
          >
            <div className="font-medium">{t.jobdesk?.label}</div>
            {!t.done && (
              <div className="text-sm text-red-700 mt-1">
                <div>
                  <b>Alasan:</b> {t.alasan || '-'}
                </div>
                <div>
                  <b>Solusi:</b> {t.solusi || '-'}
                </div>
              </div>
            )}
          </div>
        ))}
      </div>

      <div className="mt-4">
        <h4 className="font-semibold">Bukti</h4>
        <ul className="list-disc ml-5">
          {(report?.uploads || []).map((u) => (
            <li key={u.id}>
              <a
                className="text-blue-600 underline"
                href={`/storage/${u.path}`}
                target="_blank"
                rel="noreferrer"
              >
                {u.path.split('/').pop()}
              </a>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}
