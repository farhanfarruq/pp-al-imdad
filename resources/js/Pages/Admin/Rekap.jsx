import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import RekapTable from '@/Components/RekapTable'; // Pastikan import ini ada
import { useRekap } from '@/hooks/useRekap'; // Pastikan import ini ada

export default function Rekap({ auth, reports, bidangs, filters: initialFilters }) {
    const { filters, handleFilterChange } = useRekap(initialFilters);

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
                            {/* Filter Section */}
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <input
                                    type="text"
                                    name="search"
                                    value={filters.search || ''}
                                    onChange={handleFilterChange}
                                    placeholder="Cari nama pengurus..."
                                    className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                />
                                <select
                                    name="bidang"
                                    value={filters.bidang || ''}
                                    onChange={handleFilterChange}
                                    className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                >
                                    <option value="">Semua Bidang</option>
                                    {bidangs.map(bidang => (
                                        <option key={bidang.id} value={bidang.id}>
                                            {bidang.name}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            {/* Komponen Tabel Rekap */}
                            <RekapTable reports={reports} />

                            {/* Paginasi */}
                            <div className="mt-4">
                                {reports && reports.meta && reports.data.length > 0 && (
                                    <div className="flex justify-between items-center">
                                        <div className="text-sm text-gray-700">
                                            Menampilkan {reports.meta.from} sampai {reports.meta.to} dari {reports.meta.total} hasil
                                        </div>
                                        <div>
                                            {reports.meta.links.map((link, index) => (
                                                <Link
                                                    key={index}
                                                    href={link.url || '#'}
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