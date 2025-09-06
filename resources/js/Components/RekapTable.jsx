import React from 'react';
import { Link } from '@inertiajs/react';

const RekapTable = ({ reports }) => {
    return (
        <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                    <tr>
                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pembuat</th>
                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bidang</th>
                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pekerjaan Dilaksanakan</th>
                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pekerjaan Tidak Dilaksanakan</th>
                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                    {reports && reports.data && reports.data.length > 0 ? (
                        reports.data.map((report, index) => {
                            const implementedTasks = report.tasks.filter(task => task.is_done);
                            const unimplementedTasks = report.tasks.filter(task => !task.is_done);

                            return (
                                <tr key={report.id}>
                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {/* PERBAIKAN: Cek keberadaan reports.meta */}
                                        {reports.meta ? reports.meta.from + index : index + 1}
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{report.user.name}</td>
                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{report.bidang.name}</td>
                                    <td className="px-6 py-4 align-top text-sm text-gray-500">
                                        <ul className="list-disc list-inside space-y-1">
                                            {implementedTasks.length > 0 ? (
                                                implementedTasks.map(task => (
                                                    <li key={task.id}>{task.jobdesk?.name || 'Jobdesk tidak ada'}</li>
                                                ))
                                            ) : <li>-</li>}
                                        </ul>
                                    </td>
                                    <td className="px-6 py-4 align-top text-sm text-gray-500">
                                        <ul className="list-disc list-inside space-y-1">
                                            {unimplementedTasks.length > 0 ? (
                                                unimplementedTasks.map(task => (
                                                    <li key={task.id}>{task.jobdesk?.name || 'Jobdesk tidak ada'}</li>
                                                ))
                                            ) : <li>-</li>}
                                        </ul>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <Link href={route('admin.rekap.detail', report.id)} className="text-indigo-600 hover:text-indigo-900">
                                            Detail
                                        </Link>
                                    </td>
                                </tr>
                            );
                        })
                    ) : (
                        <tr>
                            <td colSpan="6" className="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada data laporan yang cocok.
                            </td>
                        </tr>
                    )}
                </tbody>
            </table>
        </div>
    );
};

export default RekapTable;