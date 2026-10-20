<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Tutor;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::with(['tutor', 'sekolah']);

        if ($request->filled('tutor')) {
            $query->where('id_tutor', $request->tutor);
        }

        if ($request->filled('sekolah')) {
            $query->where('id_sekolah', $request->sekolah);
        }

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        $jadwals = $query->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
                         ->orderBy('jam_mulai')
                         ->paginate(15)
                         ->withQueryString();

        $tutors = Tutor::aktif()->orderBy('nama')->get();
        $sekolahs = Sekolah::aktif()->orderBy('nama_sekolah')->get();

        return view('jadwal.index', compact('jadwals', 'tutors', 'sekolahs'));
    }

    public function create()
    {
        $tutors = Tutor::aktif()->orderBy('nama')->get();
        $sekolahs = Sekolah::aktif()->orderBy('nama_sekolah')->get();
        return view('jadwal.create', compact('tutors', 'sekolahs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tutor' => 'required|exists:tutor,id_tutor',
            'id_sekolah' => 'required|exists:sekolah,id_sekolah',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tanggal' => 'nullable|date',
        ]);

        // Cek konflik jadwal tutor
        $konflik = Jadwal::where('id_tutor', $validated['id_tutor'])
            ->where('hari', $validated['hari'])
            ->where(function ($q) use ($validated) {
                $q->where(function ($q2) use ($validated) {
                    $q2->where('jam_mulai', '<', $validated['jam_selesai'])
                        ->where('jam_selesai', '>', $validated['jam_mulai']);
                });
            })
            ->exists();

        if ($konflik) {
            return back()->withInput()->withErrors(['jam_mulai' => 'Terdapat konflik jadwal untuk tutor ini pada hari dan jam yang sama.']);
        }

        Jadwal::create($validated);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function show(Jadwal $jadwal)
    {
        $jadwal->load(['tutor', 'sekolah', 'absensi']);
        return view('jadwal.show', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal)
    {
        $tutors = Tutor::aktif()->orderBy('nama')->get();
        $sekolahs = Sekolah::aktif()->orderBy('nama_sekolah')->get();
        return view('jadwal.edit', compact('jadwal', 'tutors', 'sekolahs'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $request->validate([
            'id_tutor' => 'required|exists:tutor,id_tutor',
            'id_sekolah' => 'required|exists:sekolah,id_sekolah',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tanggal' => 'nullable|date',
        ]);

        // Cek konflik (exclude jadwal ini)
        $konflik = Jadwal::where('id_tutor', $validated['id_tutor'])
            ->where('hari', $validated['hari'])
            ->where('id_jadwal', '!=', $jadwal->id_jadwal)
            ->where(function ($q) use ($validated) {
                $q->where('jam_mulai', '<', $validated['jam_selesai'])
                  ->where('jam_selesai', '>', $validated['jam_mulai']);
                })
            ->exists();

        if ($konflik) {
            return back()->withInput()->withErrors(['jam_mulai' => 'Terdapat konflik jadwal untuk tutor ini pada hari dan jam yang sama.']);
        }

        $jadwal->update($validated);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        if ($jadwal->absensi()->exists()) {
            return redirect()->route('jadwal.index')->with('error', 'Jadwal tidak dapat dihapus karena memiliki data absensi terkait.');
        }

        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
