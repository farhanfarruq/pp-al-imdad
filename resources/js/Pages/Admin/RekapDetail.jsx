import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage, Link } from '@inertiajs/react';
import { format } from 'date-fns';

export default function RekapDetail({ auth }) {
    const { pengurus, failedTasks } = usePage().props;
    const { data: tasks, links } = failedTasks;

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Detail Rekap: {pengurus.nama}</h2>}
        >
            <Head title={`Detail Rekap ${pengurus.nama}`} />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <div className="flex justify-between items-center mb-4">
                                <div>
                                    <h1 className="text-2xl font-bold">Detail Tugas Tidak Dikerjakan</h1>
                                    <p className="text-lg text-gray-600">{pengurus.nama} - {pengurus.bidang.name}</p>
                                </div>
                                <Link href={route('admin.rekap')} className="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                    Kembali ke Summary
                                </Link>
                            </div>
                            <div className="overflow-x-auto mt-6">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solusi</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {tasks.map((task) => (
                                            <tr key={task.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{format(new Date(task.report.tanggal), 'dd MMM yyyy')}</td>
                                                <td className="px-6 py-4 whitespace-normal text-sm text-gray-900">{task.jobdesk.deskripsi}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{task.report.user.name}</td>
                                                <td className="px-6 py-4 whitespace-normal text-sm text-red-600">{task.alasan || '-'}</td>
                                                <td className="px-6 py-4 whitespace-normal text-sm text-green-600">{task.solusi || '-'}</td>
                                            </tr>
                                        ))}
                                        {tasks.length === 0 && (
                                            <tr>
                                                <td colSpan="5" className="px-6 py-4 text-center text-gray-500">Tidak ada data tugas yang tidak dilaksanakan.</td>
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
