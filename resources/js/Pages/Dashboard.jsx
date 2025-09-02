import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Dashboard({ auth }) { // auth di sini sudah berisi user dan userRoles
    const { bidangList } = usePage().props; // Kita hanya perlu bidangList dari props utama
    const isAdmin = auth.userRoles.includes('admin_utama') || auth.userRoles.includes('admin_bidang');

    return (
        <AuthenticatedLayout
            user={auth.user} // Kirim user ke layout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="mb-8 px-4 sm:px-0">
                        <h2 className="text-2xl font-bold text-gray-800 mb-2">Selamat Datang, {auth.user.name}</h2>
                        <p className="text-gray-600">Pilih bidang untuk membuat laporan atau kelola data melalui admin panel.</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {bidangList.map((bidang) => (
                             <Link
                                key={bidang.id}
                                href={route('report.create', bidang.slug)}
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

                        {isAdmin && (
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
                </div>
            </div>
        </AuthenticatedLayout>
    );
}