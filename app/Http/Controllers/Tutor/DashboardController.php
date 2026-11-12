<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Honor;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $tutor = Auth::guard('tutor')->user();

        $totalJadwal = Jadwal::where('id_tutor', $tutor->id_tutor)->count();
        $absensiBulanIni = Absensi::where('id_tutor', $tutor->id_tutor)
            ->where('status', 'Hadir')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $honorPending = Honor::where('id_tutor', $tutor->id_tutor)
            ->whereIn('status', ['Belum Dibayar', 'Diproses'])
            ->sum('total_honor');

        $honorDibayar = Honor::where('id_tutor', $tutor->id_tutor)
            ->where('status', 'Dibayar')
            ->sum('total_honor');

        $jadwalList = Jadwal::with('sekolah')
            ->where('id_tutor', $tutor->id_tutor)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        $riwayatAbsensi = Absensi::with('jadwal.sekolah')
            ->where('id_tutor', $tutor->id_tutor)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        $penilaianTerbaru = Penilaian::with('penilai')
            ->where('id_tutor', $tutor->id_tutor)
            ->orderBy('tanggal_penilaian', 'desc')
            ->first();

        return view('tutor-portal.dashboard', compact(
            'tutor',
            'totalJadwal',
            'absensiBulanIni',
            'honorPending',
            'honorDibayar',
            'jadwalList',
            'riwayatAbsensi',
            'penilaianTerbaru'
        ));
    }
}
