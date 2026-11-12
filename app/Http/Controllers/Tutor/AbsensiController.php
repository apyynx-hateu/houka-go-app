<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();

        $query = Absensi::with('jadwal.sekolah')
            ->where('id_tutor', $tutor->id_tutor);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', substr($request->bulan, 5, 2))
                  ->whereYear('tanggal', substr($request->bulan, 0, 4));
        }

        $absensis = $query->orderBy('tanggal', 'desc')->paginate(15)->withQueryString();

        // Jadwal milik tutor untuk opsi check-in
        $myJadwals = Jadwal::with('sekolah')
            ->where('id_tutor', $tutor->id_tutor)
            ->get();

        return view('tutor-portal.absensi', compact('absensis', 'myJadwals'));
    }

    public function checkIn(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();

        $validated = $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Verifikasi kepemilikan jadwal
        $jadwal = Jadwal::where('id_jadwal', $validated['id_jadwal'])
            ->where('id_tutor', $tutor->id_tutor)
            ->first();

        if (!$jadwal) {
            return back()->withInput()->withErrors(['id_jadwal' => 'Jadwal yang dipilih bukan penugasan mengajar Anda.']);
        }

        // Cek duplikasi absensi pada tanggal tersebut
        $exists = Absensi::where('id_tutor', $tutor->id_tutor)
            ->where('id_jadwal', $validated['id_jadwal'])
            ->where('tanggal', $validated['tanggal'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['tanggal' => 'Anda sudah melakukan absensi untuk jadwal dan tanggal ini.']);
        }

        Absensi::create([
            'id_tutor' => $tutor->id_tutor,
            'id_jadwal' => $validated['id_jadwal'],
            'tanggal' => $validated['tanggal'],
            'status' => 'Hadir',
            'waktu_check_in' => now()->format('H:i:s'),
            'keterangan' => $validated['keterangan'] ?? 'Check-in mandiri oleh tutor',
        ]);

        return redirect()->route('tutor.attendance')->with('success', 'Presensi kehadiran (check-in) berhasil dicatat!');
    }
}
