import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, usePage } from '@inertiajs/react';
import { format } from 'date-fns';

export default function Dashboard({ auth }) {
    // Ambil data dari props, berikan nilai default array kosong untuk keamanan
    const { bidangList = [], reports = [] } = usePage().props;

    // Cek role pengguna
    const isSuperAdmin = auth.userRoles.includes('admin_utama');
    const isAdminBidang = auth.userRoles.includes('admin_bidang');
    const isPengurus = auth.userRoles.includes('pengurus');

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="mb-8 px-4 sm:px-0">
                        <h2 className="text-2xl font-bold text-gray-800 mb-2">Selamat Datang, {auth.user.name}</h2>
                        <p className="text-gray-600">
                            {isSuperAdmin || isPengurus
                                ? 'Pilih bidang untuk membuat laporan atau kelola data melalui admin panel.'
                                : 'Berikut adalah daftar laporan untuk bidang Anda.'}
                        </p>
                    </div>

                    {/* Tampilan untuk Admin Utama dan Pengurus */}
                    {(isSuperAdmin || isPengurus) && (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {bidangList.map((bidang) => (
                                <Link
                                    key={bidang.id}
                                    href={route('report.create', { bidang: bidang.slug })}
                                    className="bg-white rounded-lg shadow-md p-6 transition-all duration-300 ease-in-out hover:transform hover:-translate-y-1 hover:shadow-lg cursor-pointer"
                                >
                                    <div className="flex items-center mb-4">
                                        <div className={`${bidang.color} w-12 h-12 rounded-lg flex items-center justify-center text-white mr-4`}>
                                            <i className={`${bidang.icon} text-xl`}></i>
                                        </div>
                                        <h3 className="text-lg font-semibold text-gray-800">{bidang.name}</h3>
                                    </div>
                                    <p className="text-gray-600 text-sm">Klik untuk membuat laporan untuk bidang ini.</p>
                                </Link>
                            ))}
                             {isSuperAdmin && (
                                <Link
                                    href={route('admin.rekap')}
                                    className="bg-white rounded-lg shadow-md p-6 transition-all duration-300 ease-in-out hover:transform hover:-translate-y-1 hover:shadow-lg cursor-pointer"
                                >
                                    <div className="flex items-center mb-4">
                                        <div className="bg-gray-600 w-12 h-12 rounded-lg flex items-center justify-center text-white mr-4">
                                            <i className="fas fa-cog text-xl"></i>
                                        </div>
                                        <h3 className="text-lg font-semibold text-gray-800">Admin Panel</h3>
                                    </div>
                                    <p className="text-gray-600 text-sm">Kelola data dan lihat rekap laporan.</p>
                                </Link>
                            )}
                        </div>
                    )}

                    {/* Tampilan untuk Admin Bidang */}
                    {isAdminBidang && (
                         <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6 text-gray-900">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">
                                    Laporan untuk {bidangList.length > 0 ? bidangList[0].name : 'Bidang Anda'}
                                </h3>
                                {reports.length > 0 ? (
                                    <ul className="space-y-4">
                                        {reports.map((report) => (
                                            <li key={report.id}>
                                                <Link href={route('report.show', report.id)} className="block p-4 rounded-lg shadow-sm hover:bg-gray-50 border">
                                                    <div className="flex justify-between items-center">
                                                        <div>
                                                            <p className="font-semibold text-gray-800">{report.title || `Laporan tanggal ${format(new Date(report.tanggal), 'dd MMMM yyyy')}`}</p>
                                                            <p className="text-sm text-gray-600 mt-1">Oleh: {report.user.name}</p>
                                                        </div>
                                                        <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${report.status === 'selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}`}>
                                                            {report.status}
                                                        </span>
                                                    </div>
                                                </Link>
                                            </li>
                                        ))}
                                    </ul>
                                ) : (
                                    <p className="mt-4 text-gray-500">Belum ada laporan.</p>
                                )}
                            </div>
                        </div>
                    )}

                </div>
            </div>
        </AuthenticatedLayout>
    );
}