import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { format } from 'date-fns';
import { id } from 'date-fns/locale';

export default function Rekap({ auth, reports }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Rekap Laporan Kegiatan</h2>}
        >
            <Head title="Rekap Laporan" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <div className="flex justify-between items-center mb-4">
                                <h3 className="text-lg font-bold">Daftar Semua Laporan</h3>
                                <div>
                                    <a href={route('export.rekap.excel')} className="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                                        Export Excel
                                    </a>
                                    <a href={route('export.rekap.pdf')} className="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Export PDF
                                    </a>
                                </div>
                            </div>
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Laporan</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembuat</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bidang</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {/* PERBAIKAN: Gunakan reports.data.map dan tambahkan pengecekan */}
                                    {reports && reports.data && reports.data.length > 0 ? (
                                        reports.data.map((report, index) => (
                                            <tr key={report.id}>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{reports.meta.from + index}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{report.title}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{report.user.name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{report.bidang.nama_bidang}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {format(new Date(report.date), "d MMMM yyyy", { locale: id })}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <Link href={route('admin.rekap.detail', report.id)} className="text-indigo-600 hover:text-indigo-900">
                                                        Detail
                                                    </Link>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="6" className="px-6 py-4 text-center text-sm text-gray-500">
                                                Tidak ada data laporan.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>

                            {/* Tambahkan Navigasi Paginasi */}
                            <div className="mt-4">
                                {reports.links && (
                                    <div className="flex justify-between items-center">
                                        <div className="text-sm text-gray-700">
                                            Menampilkan {reports.meta.from} sampai {reports.meta.to} dari {reports.meta.total} hasil
                                        </div>
                                        <div>
                                            {reports.meta.links.map((link, index) => (
                                                <Link
                                                    key={index}
                                                    href={link.url}
                                                    className={`px-3 py-1 border rounded-md text-sm ${link.active ? 'bg-indigo-500 text-white' : 'bg-white'} ${!link.url ? 'text-gray-400 cursor-not-allowed' : 'hover:bg-gray-50'}`}
                                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                                    as="button"
                                                    disabled={!link.url}
                                                />
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}