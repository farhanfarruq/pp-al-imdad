import React, { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm, Link } from '@inertiajs/react';
import Modal from '@/Components/Modal';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import DangerButton from '@/Components/DangerButton';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';

// Komponen untuk Tabel Pengurus
function PengurusSection({ pengurusData, bidangList }) {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
    const [editingPengurus, setEditingPengurus] = useState(null);
    const { data, setData, post, put, delete: destroy, processing, errors, reset } = useForm({
        nama: '',
        bidang_id: '',
        kelas: '',
    });

    const openModal = (pengurus = null) => {
        reset();
        setEditingPengurus(pengurus);
        if (pengurus) {
            setData({
                nama: pengurus.nama,
                bidang_id: pengurus.bidang_id,
                kelas: pengurus.kelas || '',
            });
        }
        setIsModalOpen(true);
    };

    const closeModal = () => setIsModalOpen(false);

    const openDeleteModal = (pengurus) => {
        setEditingPengurus(pengurus);
        setIsDeleteModalOpen(true);
    };

    const closeDeleteModal = () => setIsDeleteModalOpen(false);

    const handleSubmit = (e) => {
        e.preventDefault();
        const routeName = editingPengurus ? 'admin.master.pengurus.update' : 'admin.master.pengurus.store';
        const params = editingPengurus ? { pengurus: editingPengurus.id } : {};
        const action = editingPengurus ? put : post;
        
        action(route(routeName, params), {
            onSuccess: () => closeModal(),
        });
    };

    const handleDelete = (e) => {
        e.preventDefault();
        destroy(route('admin.master.pengurus.destroy', { pengurus: editingPengurus.id }), {
            onSuccess: () => closeDeleteModal(),
        });
    };

    return (
        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div className="flex justify-between items-center mb-4">
                <h3 className="text-lg font-semibold">Data Pengurus</h3>
                <PrimaryButton onClick={() => openModal()}>Tambah Pengurus</PrimaryButton>
            </div>
            <div className="overflow-x-auto">
                <table className="min-w-full divide-y divide-gray-200">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bidang</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody className="bg-white divide-y divide-gray-200">
                        {pengurusData.map(p => (
                            <tr key={p.id}>
                                <td className="px-6 py-4 whitespace-nowrap">{p.nama}</td>
                                <td className="px-6 py-4 whitespace-nowrap">{p.bidang.name}</td>
                                <td className="px-6 py-4 whitespace-nowrap">{p.kelas || '-'}</td>
                                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onClick={() => openModal(p)} className="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <button onClick={() => openDeleteModal(p)} className="text-red-600 hover:text-red-900 ml-4">Hapus</button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            <Modal show={isModalOpen} onClose={closeModal}>
                <form onSubmit={handleSubmit} className="p-6">
                    <h2 className="text-lg font-medium text-gray-900">{editingPengurus ? 'Edit Pengurus' : 'Tambah Pengurus'}</h2>
                    <div className="mt-6">
                        <InputLabel htmlFor="nama" value="Nama" />
                        <TextInput id="nama" value={data.nama} onChange={e => setData('nama', e.target.value)} className="mt-1 block w-full" required/>
                        <InputError message={errors.nama} className="mt-2" />
                    </div>
                    <div className="mt-4">
                        <InputLabel htmlFor="bidang_id" value="Bidang" />
                        <select id="bidang_id" value={data.bidang_id} onChange={e => setData('bidang_id', e.target.value)} className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">Pilih Bidang</option>
                            {bidangList.map(b => <option key={b.id} value={b.id}>{b.name}</option>)}
                        </select>
                        <InputError message={errors.bidang_id} className="mt-2" />
                    </div>
                    {Number(data.bidang_id) === 1 && (
                        <div className="mt-4">
                            <InputLabel htmlFor="kelas" value="Kelas (Khusus Bapak Kamar)" />
                            <TextInput id="kelas" value={data.kelas} onChange={e => setData('kelas', e.target.value)} className="mt-1 block w-full" />
                            <InputError message={errors.kelas} className="mt-2" />
                        </div>
                    )}
                    <div className="mt-6 flex justify-end">
                        <SecondaryButton onClick={closeModal}>Batal</SecondaryButton>
                        <PrimaryButton className="ml-3" disabled={processing}>{processing ? 'Menyimpan...' : 'Simpan'}</PrimaryButton>
                    </div>
                </form>
            </Modal>
            
             <Modal show={isDeleteModalOpen} onClose={closeDeleteModal}>
                <form onSubmit={handleDelete} className="p-6">
                    <h2 className="text-lg font-medium text-gray-900">Yakin ingin menghapus?</h2>
                    <p className="mt-1 text-sm text-gray-600">Data pengurus "{editingPengurus?.nama}" akan dihapus permanen.</p>
                    <div className="mt-6 flex justify-end">
                        <SecondaryButton onClick={closeDeleteModal}>Batal</SecondaryButton>
                        <DangerButton className="ml-3" disabled={processing}>Hapus</DangerButton>
                    </div>
                </form>
            </Modal>
        </div>
    );
}

function JobdeskSection({ jobdeskData, bidangList }) {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
    const [editingJobdesk, setEditingJobdesk] = useState(null);
    const { data, setData, post, put, delete: destroy, processing, errors, reset } = useForm({
        deskripsi: '',
        bidang_id: '',
        waktu: '',
    });

    const openModal = (jobdesk = null, bidangId = null) => {
        reset();
        setEditingJobdesk(jobdesk);
        if (jobdesk) {
            setData({ deskripsi: jobdesk.deskripsi, bidang_id: jobdesk.bidang_id, waktu: jobdesk.waktu || '' });
        } else if (bidangId) {
             setData('bidang_id', bidangId);
        }
        setIsModalOpen(true);
    };
    const closeModal = () => setIsModalOpen(false);
    const openDeleteModal = (jobdesk) => {
        setEditingJobdesk(jobdesk);
        setIsDeleteModalOpen(true);
    };
    const closeDeleteModal = () => setIsDeleteModalOpen(false);

    const handleSubmit = (e) => {
        e.preventDefault();
        const routeName = editingJobdesk ? 'admin.master.jobdesk.update' : 'admin.master.jobdesk.store';
        const params = editingJobdesk ? { jobdesk: editingJobdesk.id } : {};
        const action = editingJobdesk ? put : post;
        action(route(routeName, params), { onSuccess: () => closeModal() });
    };
    
    const handleDelete = (e) => {
        e.preventDefault();
        destroy(route('admin.master.jobdesk.destroy', { jobdesk: editingJobdesk.id }), { onSuccess: () => closeDeleteModal() });
    };

    return (
        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
             <h3 className="text-lg font-semibold mb-4">Data Jobdesk per Bidang</h3>
             <div className="space-y-6">
                {bidangList.map(bidang => (
                    <div key={bidang.id}>
                        <div className="flex justify-between items-center mb-2">
                            <h4 className="font-medium">{bidang.name}</h4>
                             <PrimaryButton onClick={() => openModal(null, bidang.id)}>Tambah Jobdesk</PrimaryButton>
                        </div>
                        <div className="overflow-x-auto border rounded-lg">
                             <table className="min-w-full divide-y divide-gray-200">
                                <tbody>
                                    {(jobdeskData[bidang.id] || []).map(jobdesk => (
                                        <tr key={jobdesk.id}>
                                            <td className="px-6 py-3 whitespace-normal">{jobdesk.deskripsi}</td>
                                            {bidang.slug === 'bapakamar' && (
                                                <td className="px-6 py-3 whitespace-nowrap capitalize">{jobdesk.waktu}</td>
                                            )}
                                            <td className="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <button onClick={() => openModal(jobdesk)} className="text-indigo-600 hover:text-indigo-900">Edit</button>
                                                <button onClick={() => openDeleteModal(jobdesk)} className="text-red-600 hover:text-red-900 ml-4">Hapus</button>
                                            </td>
                                        </tr>
                                    ))}
                                     {(jobdeskData[bidang.id] || []).length === 0 && (
                                        <tr><td className="px-6 py-3 text-gray-500">Belum ada jobdesk.</td></tr>
                                     )}
                                </tbody>
                             </table>
                        </div>
                    </div>
                ))}
             </div>
            <Modal show={isModalOpen} onClose={closeModal}>
                <form onSubmit={handleSubmit} className="p-6">
                    <h2 className="text-lg font-medium text-gray-900">{editingJobdesk ? 'Edit Jobdesk' : 'Tambah Jobdesk'}</h2>
                     <div className="mt-4">
                        <InputLabel htmlFor="jobdesk_bidang_id" value="Bidang" />
                        <select id="jobdesk_bidang_id" value={data.bidang_id} onChange={e => setData('bidang_id', e.target.value)} className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required disabled={!!editingJobdesk}>
                            <option value="">Pilih Bidang</option>
                            {bidangList.map(b => <option key={b.id} value={b.id}>{b.name}</option>)}
                        </select>
                        <InputError message={errors.bidang_id} className="mt-2" />
                    </div>
                    <div className="mt-6">
                        <InputLabel htmlFor="deskripsi" value="Deskripsi Tugas" />
                        <textarea id="deskripsi" value={data.deskripsi} onChange={e => setData('deskripsi', e.target.value)} className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required rows="3"></textarea>
                        <InputError message={errors.deskripsi} className="mt-2" />
                    </div>
                    {Number(data.bidang_id) === 1 && (
                        <div className="mt-4">
                            <InputLabel htmlFor="waktu" value="Waktu (Khusus Bapak Kamar)" />
                            <select id="waktu" value={data.waktu} onChange={e => setData('waktu', e.target.value)} className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Pilih Waktu</option>
                                <option value="malam">Malam</option>
                                <option value="subuh">Subuh</option>
                            </select>
                             <InputError message={errors.waktu} className="mt-2" />
                        </div>
                    )}
                    <div className="mt-6 flex justify-end">
                        <SecondaryButton onClick={closeModal}>Batal</SecondaryButton>
                        <PrimaryButton className="ml-3" disabled={processing}>{processing ? 'Menyimpan...' : 'Simpan'}</PrimaryButton>
                    </div>
                </form>
            </Modal>
            <Modal show={isDeleteModalOpen} onClose={closeDeleteModal}>
                <form onSubmit={handleDelete} className="p-6">
                    <h2 className="text-lg font-medium text-gray-900">Yakin ingin menghapus?</h2>
                    <p className="mt-1 text-sm text-gray-600">Jobdesk "{editingJobdesk?.deskripsi.substring(0, 30)}..." akan dihapus permanen.</p>
                    <div className="mt-6 flex justify-end">
                        <SecondaryButton onClick={closeDeleteModal}>Batal</SecondaryButton>
                        <DangerButton className="ml-3" disabled={processing}>Hapus</DangerButton>
                    </div>
                </form>
            </Modal>
        </div>
    );
}

export default function MasterData({ auth, bidangList, pengurusData, jobdeskData }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                 <div className="flex justify-between items-center">
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">Master Data</h2>
                 </div>
            }
        >
            <Head title="Master Data" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <PengurusSection pengurusData={pengurusData} bidangList={bidangList} />
                    <JobdeskSection jobdeskData={jobdeskData} bidangList={bidangList} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
