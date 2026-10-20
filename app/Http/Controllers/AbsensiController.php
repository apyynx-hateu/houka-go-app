<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Tutor;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::with(['tutor', 'jadwal.sekolah']);

        if ($request->filled('tutor')) {
            $query->where('id_tutor', $request->tutor);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        } elseif ($request->filled('dari')) {
            $query->where('tanggal', '>=', $request->dari);
        } elseif ($request->filled('sampai')) {
            $query->where('tanggal', '<=', $request->sampai);
        }

        $absensis = $query->orderBy('tanggal', 'desc')->paginate(15)->withQueryString();
        $tutors = Tutor::aktif()->orderBy('nama')->get();

        return view('absensi.index', compact('absensis', 'tutors'));
    }

    public function create()
    {
        $tutors = Tutor::aktif()->orderBy('nama')->get();
        $jadwals = Jadwal::with(['tutor', 'sekolah'])->orderBy('id_tutor')->get();
        return view('absensi.create', compact('tutors', 'jadwals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tutor' => 'required|exists:tutor,id_tutor',
            'id_jadwal' => 'nullable|exists:jadwal,id_jadwal',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Tidak Hadir',
            'keterangan' => 'nullable|string',
        ]);

        // Validasi konsistensi tutor-jadwal
        if (!empty($validated['id_jadwal'])) {
            $jadwal = Jadwal::find($validated['id_jadwal']);
            if ($jadwal && $jadwal->id_tutor != $validated['id_tutor']) {
                return back()->withInput()->withErrors(['id_jadwal' => 'Jadwal yang dipilih bukan milik tutor ini.']);
            }
        }

        // Cek duplikasi
        $exists = Absensi::where('id_tutor', $validated['id_tutor'])
            ->where('id_jadwal', $validated['id_jadwal'])
            ->where('tanggal', $validated['tanggal'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['tanggal' => 'Absensi untuk tutor, jadwal, dan tanggal ini sudah ada.']);
        }

        Absensi::create($validated);

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dicatat.');
    }

    public function show(Absensi $absensi)
    {
        $absensi->load(['tutor', 'jadwal.sekolah']);
        return view('absensi.show', compact('absensi'));
    }

    public function edit(Absensi $absensi)
    {
        $tutors = Tutor::aktif()->orderBy('nama')->get();
        $jadwals = Jadwal::with(['tutor', 'sekolah'])->orderBy('id_tutor')->get();
        return view('absensi.edit', compact('absensi', 'tutors', 'jadwals'));
    }

    public function update(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'id_tutor' => 'required|exists:tutor,id_tutor',
            'id_jadwal' => 'nullable|exists:jadwal,id_jadwal',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Tidak Hadir',
            'keterangan' => 'nullable|string',
        ]);

        if (!empty($validated['id_jadwal'])) {
            $jadwal = Jadwal::find($validated['id_jadwal']);
            if ($jadwal && $jadwal->id_tutor != $validated['id_tutor']) {
                return back()->withInput()->withErrors(['id_jadwal' => 'Jadwal yang dipilih bukan milik tutor ini.']);
            }
        }

        // Cek duplikasi (exclude current)
        $exists = Absensi::where('id_tutor', $validated['id_tutor'])
            ->where('id_jadwal', $validated['id_jadwal'])
            ->where('tanggal', $validated['tanggal'])
            ->where('id_absensi', '!=', $absensi->id_absensi)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['tanggal' => 'Absensi untuk tutor, jadwal, dan tanggal ini sudah ada.']);
        }

        $absensi->update($validated);

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(Absensi $absensi)
    {
        $absensi->delete();
        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus.');
    }
}
