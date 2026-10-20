<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Honor;
use App\Models\Sekolah;
use App\Models\PembayaranHonor;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function tutor(Request $request)
    {
        $query = Tutor::query();

        if ($request->filled('status')) {
            $query->where('status_kerja', $request->status);
        }

        $tutors = $query->withCount(['jadwal', 'absensi', 'honor'])->orderBy('nama')->paginate(20)->withQueryString();

        return view('laporan.tutor', compact('tutors'));
    }

    public function absensi(Request $request)
    {
        $query = Absensi::with(['tutor', 'jadwal.sekolah']);

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }

        if ($request->filled('tutor')) {
            $query->where('id_tutor', $request->tutor);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $absensis = $query->orderBy('tanggal', 'desc')->paginate(20)->withQueryString();
        $tutors = Tutor::orderBy('nama')->get();

        // Ringkasan
        $ringkasan = [
            'total' => (clone $query)->count(),
            'hadir' => (clone $query)->where('status', 'Hadir')->count(),
            'tidak_hadir' => (clone $query)->where('status', 'Tidak Hadir')->count(),
        ];

        return view('laporan.absensi', compact('absensis', 'tutors', 'ringkasan'));
    }

    public function jadwal(Request $request)
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
                         ->paginate(20)
                         ->withQueryString();

        $tutors = Tutor::orderBy('nama')->get();
        $sekolahs = Sekolah::orderBy('nama_sekolah')->get();

        return view('laporan.jadwal', compact('jadwals', 'tutors', 'sekolahs'));
    }

    public function honor(Request $request)
    {
        $query = Honor::with('tutor');

        if ($request->filled('tutor')) {
            $query->where('id_tutor', $request->tutor);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('periode_bulan', [$request->dari . '-01', $request->sampai . '-28']);
        }

        $honors = $query->orderBy('periode_bulan', 'desc')->paginate(20)->withQueryString();
        $tutors = Tutor::orderBy('nama')->get();

        $ringkasan = [
            'total_honor' => (clone $query)->sum('total_honor'),
            'belum_dibayar' => (clone $query)->where('status', 'Belum Dibayar')->sum('total_honor'),
            'diproses' => (clone $query)->where('status', 'Diproses')->sum('total_honor'),
            'dibayar' => (clone $query)->where('status', 'Dibayar')->sum('total_honor'),
        ];

        return view('laporan.honor', compact('honors', 'tutors', 'ringkasan'));
    }

    public function operasional(Request $request)
    {
        $data = [
            'totalTutor' => Tutor::count(),
            'tutorAktif' => Tutor::aktif()->count(),
            'totalSekolah' => Sekolah::count(),
            'sekolahAktif' => Sekolah::aktif()->count(),
            'totalJadwal' => Jadwal::count(),
            'totalAbsensi' => Absensi::count(),
            'totalHonor' => Honor::sum('total_honor'),
            'totalPembayaran' => PembayaranHonor::where('status', 'Berhasil')->sum('jumlah'),
        ];

        return view('laporan.operasional', compact('data'));
    }
}
