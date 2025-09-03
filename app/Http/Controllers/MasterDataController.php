<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Jobdesk;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class MasterDataController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/MasterData', [
            'bidangList' => Bidang::all(['id', 'name', 'slug']),
            'pengurusData' => Pengurus::with('bidang')->orderBy('nama')->get(),
            'jobdeskData' => Jobdesk::all()->groupBy('bidang_id'),
        ]);
    }

    public function storePengurus(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'bidang_id' => 'required|exists:bidangs,id',
            'kelas' => ['nullable', 'string', 'max:50', Rule::requiredIf($request->bidang_id == 1)],
        ], [
            'nama.required' => 'Nama pengurus tidak boleh kosong.',
            'bidang_id.required' => 'Bidang harus dipilih.',
            'kelas.required' => 'Kelas wajib diisi untuk Bapak Kamar.',
        ]);
        Pengurus::create($validated);
        return Redirect::route('admin.master.index')->with('success', 'Pengurus berhasil ditambahkan.');
    }

    public function updatePengurus(Request $request, Pengurus $pengurus)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'bidang_id' => 'required|exists:bidangs,id',
            'kelas' => ['nullable', 'string', 'max:50', Rule::requiredIf($request->bidang_id == 1)],
        ], [
            'nama.required' => 'Nama pengurus tidak boleh kosong.',
            'bidang_id.required' => 'Bidang harus dipilih.',
            'kelas.required' => 'Kelas wajib diisi untuk Bapak Kamar.',
        ]);
        $pengurus->update($validated);
        return Redirect::route('admin.master.index')->with('success', 'Pengurus berhasil diperbarui.');
    }

    public function destroyPengurus(Pengurus $pengurus)
    {
        if ($pengurus->reports()->exists()) {
            return Redirect::back()->with('error', 'Pengurus tidak dapat dihapus karena memiliki riwayat laporan.');
        }
        $pengurus->delete();
        return Redirect::route('admin.master.index')->with('success', 'Pengurus berhasil dihapus.');
    }

    public function storeJobdesk(Request $request)
    {
        $validated = $request->validate([
            'deskripsi' => 'required|string',
            'bidang_id' => 'required|exists:bidangs,id',
            'waktu' => ['nullable', Rule::in(['malam', 'subuh']), Rule::requiredIf($request->bidang_id == 1)],
        ], [
            'deskripsi.required' => 'Deskripsi tugas tidak boleh kosong.',
            'bidang_id.required' => 'Bidang harus dipilih.',
            'waktu.required' => 'Waktu wajib dipilih untuk jobdesk Bapak Kamar.',
        ]);
        Jobdesk::create($validated);
        return Redirect::route('admin.master.index')->with('success', 'Jobdesk berhasil ditambahkan.');
    }

    public function updateJobdesk(Request $request, Jobdesk $jobdesk)
    {
        $validated = $request->validate([
            'deskripsi' => 'required|string',
            'bidang_id' => 'required|exists:bidangs,id',
            'waktu' => ['nullable', Rule::in(['malam', 'subuh']), Rule::requiredIf($request->bidang_id == 1)],
        ], [
            'deskripsi.required' => 'Deskripsi tugas tidak boleh kosong.',
            'bidang_id.required' => 'Bidang harus dipilih.',
            'waktu.required' => 'Waktu wajib dipilih untuk jobdesk Bapak Kamar.',
        ]);
        $jobdesk->update($validated);
        return Redirect::route('admin.master.index')->with('success', 'Jobdesk berhasil diperbarui.');
    }

    public function destroyJobdesk(Jobdesk $jobdesk)
    {
        if ($jobdesk->reportTasks()->exists()) {
            return Redirect::back()->with('error', 'Jobdesk tidak dapat dihapus karena sudah pernah digunakan dalam laporan.');
        }
        $jobdesk->delete();
        return Redirect::route('admin.master.index')->with('success', 'Jobdesk berhasil dihapus.');
    }
}
