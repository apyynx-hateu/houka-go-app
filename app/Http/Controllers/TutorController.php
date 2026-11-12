<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TutorController extends Controller
{
    public function index(Request $request)
    {
        $query = Tutor::query();

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%')
                  ->orWhere('username', 'like', '%' . $request->cari . '%')
                  ->orWhere('email', 'like', '%' . $request->cari . '%')
                  ->orWhere('no_telepon', 'like', '%' . $request->cari . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status_kerja', $request->status);
        }

        $tutors = $query->orderBy('nama')->paginate(15)->withQueryString();

        return view('tutor.index', compact('tutors'));
    }

    public function create()
    {
        return view('tutor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:tutor,username',
            'password' => 'nullable|string|min:6',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date|before_or_equal:today',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'status_kerja' => 'required|in:Aktif,Tidak Aktif',
            'tanggal_bergabung' => 'nullable|date',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } elseif (!empty($validated['username'])) {
            // Default password jika diisi username tapi password kosong
            $validated['password'] = Hash::make('password');
        }

        Tutor::create($validated);

        return redirect()->route('tutor.index')->with('success', 'Tutor berhasil ditambahkan.');
    }

    public function show(Tutor $tutor)
    {
        $tutor->load(['jadwal.sekolah', 'absensi', 'honor', 'penilaian.penilai']);
        return view('tutor.show', compact('tutor'));
    }

    public function edit(Tutor $tutor)
    {
        return view('tutor.edit', compact('tutor'));
    }

    public function update(Request $request, Tutor $tutor)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('tutor', 'username')->ignore($tutor->id_tutor, 'id_tutor'),
            ],
            'password' => 'nullable|string|min:6',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date|before_or_equal:today',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'status_kerja' => 'required|in:Aktif,Tidak Aktif',
            'tanggal_bergabung' => 'nullable|date',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $tutor->update($validated);

        return redirect()->route('tutor.index')->with('success', 'Data tutor berhasil diperbarui.');
    }

    public function destroy(Tutor $tutor)
    {
        // Data integrity safeguard (PRD Section 3.1)
        if ($tutor->jadwal()->exists() || $tutor->absensi()->exists() || $tutor->honor()->exists() || $tutor->penilaian()->exists()) {
            $tutor->update(['status_kerja' => 'Tidak Aktif']);
            return redirect()->route('tutor.index')->with('success', 'Tutor dinonaktifkan untuk menjaga integritas riwayat jadwal/absensi/honor.');
        }

        $tutor->delete();
        return redirect()->route('tutor.index')->with('success', 'Tutor berhasil dihapus.');
    }
}
