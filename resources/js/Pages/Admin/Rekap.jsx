import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage, Link, router } from '@inertiajs/react';
import { format } from 'date-fns';
import React from 'react';

export default function Rekap({ auth }) {
    const { failedTasks = { data: [], links: [] }, filters, bidangList } = usePage().props;

    const {
        data: tasks,
        links
    } = failedTasks;

    const [start, setStart] = React.useState(filters.tanggal_mulai || '');
    const [end, setEnd] = React.useState(filters.tanggal_akhir || '');
    const [bidang, setBidang] = React.useState(filters.bidang_id || '');

    const handleFilter = (e) => {
        e.preventDefault();
        router.get(route('admin.rekap'), {
            tanggal_mulai: start,
            tanggal_akhir: end,
            bidang_id: bidang,
        }, {
            preserveState: true,
            replace: true,
        });
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Rekap Tugas Tidak Dilaksanakan</h2>}
        >
            <Head title="Rekap Pelanggaran Tugas" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            
                            {/* --- MULAI PENAMBAHAN TOMBOL --- */}
                            <div className="flex justify-between items-center mb-4">
                                <h1 className="text-2xl font-bold">Rekap Tugas Tidak Dilaksanakan</h1>
                                <Link
                                    href={route('dashboard')}
                                    className="px-4 py-2 bg-gray-600 text-white rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 flex items-center transition ease-in-out duration-150"
                                >
                                    <i className="fas fa-arrow-left mr-2"></i>
                                    Kembali ke Dashboard
                                </Link>
                            </div>
                            {/* --- AKHIR PENAMBAHAN TOMBOL --- */}

                            <form onSubmit={handleFilter} className="flex flex-wrap gap-4 mb-4 items-end">
                                <div className="flex-grow">
                                    <label className="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                    <input type="date" value={start} onChange={e => setStart(e.target.value)} className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                </div>
                                <div className="flex-grow">
                                    <label className="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                                    <input type="date" value={end} onChange={e => setEnd(e.target.value)} className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                </div>
                                <div className="flex-grow">
                                    <label className="block text-sm font-medium text-gray-700">Bidang</label>
                                    <select value={bidang} onChange={e => setBidang(e.target.value)} className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Semua Bidang</option>
                                        {bidangList.map(b => <option key={b.id} value={b.id}>{b.name}</option>)}
                                    </select>
                                </div>
                                <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700">
                                    Filter
                                </button>
                            </form>

                            <div className="overflow-x-auto mt-6">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bidang</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengurus Bertugas</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solusi</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {tasks.map((task) => (
                                            <tr key={task.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{format(new Date(task.report.tanggal), 'dd MMM yyyy')}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{task.report.bidang.name}</td>
                                                <td className="px-6 py-4 whitespace-normal text-sm text-gray-900">{task.jobdesk.deskripsi}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{task.report.pengurus.nama}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{task.report.user.name}</td>
                                                <td className="px-6 py-4 whitespace-normal text-sm text-red-600">{task.alasan || '-'}</td>
                                                <td className="px-6 py-4 whitespace-normal text-sm text-green-600">{task.solusi || '-'}</td>
                                            </tr>
                                        ))}
                                        {tasks.length === 0 && (
                                            <tr>
                                                <td colSpan="7" className="px-6 py-4 text-center text-gray-500">Tidak ada data tugas yang tidak dilaksanakan.</td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                            </div>

                            <div className="mt-4">
                                <div className="flex flex-wrap -mb-1">
                                    {links.map((link, key) => (
                                        link.url === null ?
                                            (<div key={key} className="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" dangerouslySetInnerHTML={{ __html: link.label }} />) :
                                            (<Link key={key} className={`mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white ${link.active ? 'bg-blue-700 text-white' : ''}`} href={link.url} dangerouslySetInnerHTML={{ __html: link.label }} />)
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}