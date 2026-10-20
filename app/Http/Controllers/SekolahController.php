<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    public function index(Request $request)
    {
        $query = Sekolah::query();

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_sekolah', 'like', '%' . $request->cari . '%')
                  ->orWhere('alamat', 'like', '%' . $request->cari . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status_aktif', $request->status);
        }

        $sekolahs = $query->orderBy('nama_sekolah')->paginate(15)->withQueryString();

        return view('sekolah.index', compact('sekolahs'));
    }

    public function create()
    {
        return view('sekolah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'ruangan' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|integer|min:1',
            'status_aktif' => 'required|in:Aktif,Tidak Aktif',
        ]);

        Sekolah::create($validated);

        return redirect()->route('sekolah.index')->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function show(Sekolah $sekolah)
    {
        $sekolah->load(['jadwal.tutor']);
        return view('sekolah.show', compact('sekolah'));
    }

    public function edit(Sekolah $sekolah)
    {
        return view('sekolah.edit', compact('sekolah'));
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        $validated = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'ruangan' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|integer|min:1',
            'status_aktif' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $sekolah->update($validated);

        return redirect()->route('sekolah.index')->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function destroy(Sekolah $sekolah)
    {
        if ($sekolah->jadwal()->exists()) {
            $sekolah->update(['status_aktif' => 'Tidak Aktif']);
            return redirect()->route('sekolah.index')->with('success', 'Sekolah dinonaktifkan karena memiliki jadwal terkait.');
        }

        $sekolah->delete();
        return redirect()->route('sekolah.index')->with('success', 'Sekolah berhasil dihapus.');
    }
}
