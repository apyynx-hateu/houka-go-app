<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\Sekolah;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Honor;
use App\Models\PembayaranHonor;
use App\Models\PermintaanLaporan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalTutorAktif' => Tutor::aktif()->count(),
            'totalTutorTidakAktif' => Tutor::tidakAktif()->count(),
            'totalSekolah' => Sekolah::aktif()->count(),
            'totalJadwal' => Jadwal::count(),
            'totalAbsensiHadir' => Absensi::hadir()->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count(),
            'totalAbsensiTidakHadir' => Absensi::tidakHadir()->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count(),
            'honorBelumDibayar' => Honor::where('status', 'Belum Dibayar')->sum('total_honor'),
            'honorDiproses' => Honor::where('status', 'Diproses')->sum('total_honor'),
            'honorDibayar' => Honor::where('status', 'Dibayar')->sum('total_honor'),
            'pembayaranBerhasil' => PembayaranHonor::where('status', 'Berhasil')->sum('jumlah'),
            'pembayaranPending' => PembayaranHonor::where('status', 'Pending')->sum('jumlah'),
            'laporanMenunggu' => PermintaanLaporan::where('status', 'Menunggu')->count(),
            'laporanDiproses' => PermintaanLaporan::where('status', 'Diproses')->count(),
        ];

        return view('dashboard.index', $data);
    }
}
