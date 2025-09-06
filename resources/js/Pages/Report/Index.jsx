import { Link, Head } from '@inertiajs/react';
import { format } from 'date-fns';
import { id } from 'date-fns/locale';

export default function Index({ reports }) {
    return (
        <>
            <Head title="Daftar Laporan" />
            <div className="bg-gray-100 min-h-screen">
                <div className="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <header className="text-center mb-12">
                        <h1 className="text-4xl font-extrabold text-gray-900">Semua Laporan Kegiatan</h1>
                        <p className="mt-2 text-lg text-gray-600">Telusuri semua laporan yang telah dipublikasikan.</p>
                    </header>

                    <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        {reports.data.map((report) => (
                            <div key={report.id} className="bg-white rounded-lg shadow-md overflow-hidden transform hover:-translate-y-1 transition-transform duration-300">
                                <div className="p-6">
                                    <p className="text-sm text-gray-500">{report.bidang.name}</p>
                                    <h2 className="text-xl font-bold text-gray-800 mt-1 truncate">
                                        {report.title}
                                    </h2>
                                    <p className="text-sm text-gray-600 mt-2">
                                        Oleh {report.user.name} &bull; {format(new Date(report.date), "d MMM yyyy", { locale: id })}
                                    </p>
                                    <Link 
                                        href={route('reports.show', report.id)} 
                                        className="inline-block mt-4 text-indigo-600 hover:text-indigo-800 font-semibold"
                                    >
                                        Lihat Detail &rarr;
                                    </Link>
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Paginasi */}
                    <div className="mt-12">
                         {reports.meta.links.map((link, index) => (
                            <Link
                                key={index}
                                href={link.url || '#'}
                                className={`px-4 py-2 mx-1 border rounded-md text-sm ${link.active ? 'bg-indigo-600 text-white' : 'bg-white'} ${!link.url ? 'text-gray-400 cursor-not-allowed' : 'hover:bg-gray-50'}`}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                                as="button"
                                disabled={!link.url}
                            />
                        ))}
                    </div>
                </div>
            </div>
        </>
    );
}