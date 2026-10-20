<?php

namespace App\Services;

use App\Models\Honor;
use App\Models\Tutor;
use App\Models\Absensi;
use App\Models\Jadwal;
use Carbon\Carbon;

class HonorCalculationService
{
    /**
     * Menghitung total honor tutor untuk periode tertentu.
     * Formula: total_honor = tarif_per_jam × total_jam_mengajar + penyesuaian_honor
     */
    public function hitungHonor(
        int $idTutor,
        string $periodeBulan,
        float $tarifPerJam,
        float $penyesuaianHonor = 0,
        ?int $jumlahSiswa = 0
    ): array {
        $periode = Carbon::parse($periodeBulan);
        $awalBulan = $periode->copy()->startOfMonth();
        $akhirBulan = $periode->copy()->endOfMonth();

        // Hitung total jam dari absensi Hadir pada periode tersebut
        $totalJam = max(0, $this->hitungTotalJam($idTutor, $awalBulan, $akhirBulan));

        // Hitung total honor
        $totalHonor = max(0, ($tarifPerJam * $totalJam) + $penyesuaianHonor);

        return [
            'id_tutor' => $idTutor,
            'periode_bulan' => $awalBulan->format('Y-m-d'),
            'tarif_per_jam' => $tarifPerJam,
            'jumlah_siswa' => $jumlahSiswa ?? 0,
            'total_jam_mengajar' => round($totalJam, 2),
            'penyesuaian_honor' => $penyesuaianHonor,
            'total_honor' => round($totalHonor, 2),
        ];
    }

    /**
     * Menghitung total jam mengajar berdasarkan absensi hadir.
     */
    public function hitungTotalJam(int $idTutor, Carbon $dari, Carbon $sampai): float
    {
        $absensiHadir = Absensi::where('id_tutor', $idTutor)
            ->where('status', 'Hadir')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->whereNotNull('id_jadwal')
            ->with('jadwal')
            ->get();

        $totalMenit = 0;
        foreach ($absensiHadir as $absensi) {
            if ($absensi->jadwal) {
                $mulai = Carbon::parse($absensi->jadwal->jam_mulai);
                $selesai = Carbon::parse($absensi->jadwal->jam_selesai);
                $totalMenit += abs($selesai->diffInMinutes($mulai));
            }
        }

        return max(0, $totalMenit / 60);
    }
}
