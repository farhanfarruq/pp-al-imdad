import { useState, useEffect } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';

export default function ReportForm({ auth, bidang, pengurusList, jobdeskList }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        tanggal: new Date().toISOString().split('T')[0],
        pengurus_id: '',
        bidang_id: bidang.id,
        waktu: bidang.slug === 'bapakamar' ? 'malam' : null,
        tasks: [],
        bukti: null,
    });

    const [currentTab, setCurrentTab] = useState('malam');

    // Inisialisasi 'tasks' state dari jobdeskList
    useEffect(() => {
        const filteredJobdesks = jobdeskList.filter(j => {
            if (bidang.slug !== 'bapakamar') return true;
            return j.waktu === data.waktu;
        });

        const initialTasks = filteredJobdesks.map(jobdesk => ({
            id: jobdesk.id,
            deskripsi: jobdesk.deskripsi,
            status: 'selesai', // default status
            alasan: '',
            solusi: '',
        }));
        setData('tasks', initialTasks);
    }, [jobdeskList, data.waktu, bidang.slug]);


    const handleTaskStatusChange = (index, newStatus) => {
        const updatedTasks = [...data.tasks];
        updatedTasks[index].status = newStatus;
        // Reset alasan & solusi if status is back to 'selesai'
        if (newStatus === 'selesai') {
            updatedTasks[index].alasan = '';
            updatedTasks[index].solusi = '';
        }
        setData('tasks', updatedTasks);
    };

    const handleIncompleteDetailChange = (index, field, value) => {
        const updatedTasks = [...data.tasks];
        updatedTasks[index][field] = value;
        setData('tasks', updatedTasks);
    };

    const handleTabChange = (tab) => {
        setCurrentTab(tab);
        setData('waktu', tab);
    }

    const submit = (e) => {
        e.preventDefault();
        post(route('report.store'), {
            onSuccess: () => reset(),
        });
    };
    
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex justify-between items-center">
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        Form Laporan: {bidang.name}
                    </h2>
                     <Link href={route('dashboard')} className="text-gray-500 hover:text-gray-700">
                        <i className="fas fa-arrow-left mr-2"></i>Kembali
                    </Link>
                </div>
            }
        >
            <Head title={`Laporan ${bidang.name}`} />

            <div className="py-12">
                <div className="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form onSubmit={submit} className="p-6 space-y-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">Nama Pengurus</label>
                                    <select
                                        id="pengurusSelect"
                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        value={data.pengurus_id}
                                        onChange={(e) => setData('pengurus_id', e.target.value)}
                                        required
                                    >
                                        <option value="">Pilih Pengurus</option>
                                        {pengurusList.map(p => (
                                            <option key={p.id} value={p.id}>
                                                {p.nama} {p.kelas && `(${p.kelas === 'tahfidz' ? 'Tahfidz' : 'Kelas ' + p.kelas})`}
                                            </option>
                                        ))}
                                    </select>
                                    <InputError message={errors.pengurus_id} className="mt-2" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                                    <input
                                        type="date"
                                        id="tanggalInput"
                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50"
                                        value={data.tanggal}
                                        readOnly
                                    />
                                </div>
                            </div>
                            
                            {bidang.slug === 'bapakamar' && (
                                <div className="border-b border-gray-200 mb-6">
                                    <nav className="flex space-x-8">
                                        <button
                                            type="button"
                                            onClick={() => handleTabChange('malam')}
                                            className={`py-2 px-1 border-b-2 font-medium text-sm ${currentTab === 'malam' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300'}`}
                                        >
                                            Laporan Malam
                                        </button>
                                        <button
                                            type="button"
                                            onClick={() => handleTabChange('subuh')}
                                            className={`py-2 px-1 border-b-2 font-medium text-sm ${currentTab === 'subuh' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300'}`}
                                        >
                                            Laporan Subuh
                                        </button>
                                    </nav>
                                </div>
                            )}

                            <div>
                                <h3 className="text-lg font-semibold text-gray-800 mb-4">Daftar Tugas</h3>
                                <div className="space-y-4">
                                    {data.tasks.map((task, index) => (
                                        <div key={task.id} className="p-4 border border-gray-200 rounded-lg">
                                            <div className="flex items-start">
                                                <div className="flex-1">
                                                    <p className="text-sm text-gray-800">{task.deskripsi}</p>
                                                </div>
                                                <div className="flex items-center space-x-4 ml-4">
                                                    <label className="flex items-center space-x-2 cursor-pointer">
                                                        <input type="radio" name={`status_${task.id}`} checked={task.status === 'selesai'} onChange={() => handleTaskStatusChange(index, 'selesai')} className="text-blue-600 focus:ring-blue-500"/>
                                                        <span className="text-sm text-green-600 font-medium">Selesai</span>
                                                    </label>
                                                    <label className="flex items-center space-x-2 cursor-pointer">
                                                        <input type="radio" name={`status_${task.id}`} checked={task.status === 'tidak_selesai'} onChange={() => handleTaskStatusChange(index, 'tidak_selesai')} className="text-blue-600 focus:ring-blue-500"/>
                                                        <span className="text-sm text-red-600 font-medium">Tidak</span>
                                                    </label>
                                                </div>
                                            </div>
                                            {task.status === 'tidak_selesai' && (
                                                <div className="mt-4 p-4 border border-red-200 rounded-lg bg-red-50 space-y-3 animate-fade-in">
                                                    <div>
                                                        <label className="block text-sm font-medium text-red-700 mb-1">Alasan tidak selesai: <span className="text-red-500">*</span></label>
                                                        <textarea
                                                            className="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 transition-colors"
                                                            rows="2" required
                                                            placeholder="Jelaskan alasan mengapa tugas tidak dapat diselesaikan..."
                                                            value={task.alasan}
                                                            onChange={(e) => handleIncompleteDetailChange(index, 'alasan', e.target.value)}
                                                        ></textarea>
                                                    </div>
                                                     <div>
                                                        <label className="block text-sm font-medium text-red-700 mb-1">Solusi yang akan dilakukan: <span className="text-red-500">*</span></label>
                                                        <textarea
                                                            className="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 transition-colors"
                                                            rows="2" required
                                                            placeholder="Jelaskan solusi atau tindakan yang akan dilakukan..."
                                                            value={task.solusi}
                                                            onChange={(e) => handleIncompleteDetailChange(index, 'solusi', e.target.value)}
                                                        ></textarea>
                                                    </div>
                                                </div>
                                            )}
                                        </div>
                                    ))}
                                </div>
                                <InputError message={errors.tasks} className="mt-2" />
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">Upload Bukti (Opsional)</label>
                                <input
                                    type="file"
                                    id="buktiFile"
                                    onChange={(e) => setData('bukti', e.target.files[0])}
                                    className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                />
                                <p className="text-sm text-gray-500 mt-1">Maksimal 5MB, format: JPG, PNG, PDF</p>
                                <InputError message={errors.bukti} className="mt-2" />
                            </div>

                            <div className="flex justify-end">
                               <PrimaryButton disabled={processing}>
                                   {processing ? 'Menyimpan...' : 'Submit Laporan'}
                               </PrimaryButton>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}