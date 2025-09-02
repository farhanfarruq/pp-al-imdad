import React, { useMemo, useState } from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';


export default function ReportForm(){
const { bidang, jobdesks } = usePage().props;
const [tab, setTab] = useState('malam');
const tabs = useMemo(()=>{
const hasTab = jobdesks.some(j=>j.bpk_tab);
return hasTab ? ['malam','subuh'] : [null];
},[jobdesks]);


const form = useForm({
tanggal: new Date().toISOString().slice(0,10),
bidang_id: bidang.id,
pengurus_nama: '',
bpk_tab: tabs[0] ?? null,
tasks: [],
bukti: [],
});


const visibleJobs = jobdesks.filter(j=>!j.bpk_tab || j.bpk_tab===tab);


function toggleTask(jobdesk_id, done){
const idx = form.data.tasks.findIndex(t=>t.jobdesk_id===jobdesk_id);
const copy = [...form.data.tasks];
if(idx>=0){ copy[idx] = { ...copy[idx], done }; }
else copy.push({ jobdesk_id, done, alasan:null, solusi:null });
form.setData('tasks', copy);
}


function setNota(jobdesk_id, field, val){
const idx = form.data.tasks.findIndex(t=>t.jobdesk_id===jobdesk_id);
const copy = [...form.data.tasks];
if(idx>=0) copy[idx] = { ...copy[idx], [field]: val };
else copy.push({ jobdesk_id, done:false, [field]: val });
form.setData('tasks', copy);
}
}