import React from 'react';
import { Head, Link, usePage } from '@inertiajs/react';


export default function Dashboard(){
const { bidang, user } = usePage().props;
return (
<div className="p-6 max-w-7xl mx-auto">
<Head title="Dashboard" />
<div className="flex items-center justify-between mb-6">
<h1 className="text-2xl font-bold">PP Al Imdad Putra</h1>
<div className="text-sm text-gray-600">{user.name}</div>
</div>
<p className="text-gray-600 mb-4">Pilih bidang untuk membuat atau melihat laporan</p>
<div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
{bidang.map(b => (
<Link href={route('report.create', b.slug)} key={b.id} className="bg-white shadow p-6 rounded-lg border hover:shadow-lg transition">
<div className="flex items-center mb-2">
<div className={`w-10 h-10 rounded-lg text-white flex items-center justify-center mr-3 ${b.color ?? 'bg-blue-500'}`}>
<i className={`fa ${b.icon ?? 'fa-mosque'}`}></i>
</div>
<h3 className="font-semibold">{b.name}</h3>
</div>
<p className="text-sm text-gray-600">Klik untuk membuat laporan</p>
</Link>
))}
</div>
<div className="mt-6">
<Link href={route('admin.rekap')} className="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded">Admin Panel</Link>
</div>
</div>
);
}