<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Jobdesk;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MasterDataController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/MasterData', [
            'bidangList' => Bidang::all(),
            'pengurusData' => Pengurus::with('bidang')->get(),
            'jobdeskData' => Jobdesk::with('bidang')->get()->groupBy('bidang_id'),
        ]);
    }

    // Pengurus Methods
    public function storePengurus(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'bidang_id' => 'required|exists:bidangs,id',
            'kelas' => 'nullable|string|max:50',
        ]);

        Pengurus::create($request->all());

        return back()->with('success', 'Pengurus berhasil ditambahkan.');
    }

    public function updatePengurus(Request $request, Pengurus $pengurus)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'bidang_id' => 'required|exists:bidangs,id',
            'kelas' => 'nullable|string|max:50',
        ]);

        // Jika bidang bukan "bapakamar", pastikan kelas null
        if ($request->bidang_id != 1) {
            $request->merge(['kelas' => null]);
        }

        $pengurus->update($request->all());

        return back()->with('success', 'Pengurus berhasil diperbarui.');
    }

    public function destroyPengurus(Pengurus $pengurus)
    {
        $pengurus->delete();
        return back()->with('success', 'Pengurus berhasil dihapus.');
    }

    // Jobdesk Methods
    public function storeJobdesk(Request $request)
    {
        $request->validate([
            'deskripsi' => 'required|string',
            'bidang_id' => 'required|exists:bidangs,id',
            'waktu' => 'nullable|in:malam,subuh',
        ]);

        Jobdesk::create($request->all());

        return back()->with('success', 'Jobdesk berhasil ditambahkan.');
    }

    public function updateJobdesk(Request $request, Jobdesk $jobdesk)
    {
        $request->validate([
            'deskripsi' => 'required|string',
            'bidang_id' => 'required|exists:bidangs,id',
            'waktu' => 'nullable|in:malam,subuh',
        ]);

        $jobdesk->update($request->all());
        
        return back()->with('success', 'Jobdesk berhasil diperbarui.');
    }

    public function destroyJobdesk(Jobdesk $jobdesk)
    {
        $jobdesk->delete();
        return back()->with('success', 'Jobdesk berhasil dihapus.');
    }
}