<?php

namespace App\Services;

use App\Models\Honor;
use App\Models\Tutor;
use App\Models\Absensi;
use Carbon\Carbon;

class HonorCalculationService
{
    public const TARIF_PER_SESI_DEFAULT = 50000;
    public const FEE_CHECK_IN_PER_SESI = 20000;
    public const FEE_TRANSPORT_PER_SESI = 30000;

    /**
     * Menghitung total honor tutor untuk periode tertentu berdasarkan sesi.
     * Formula:
     * Honor Sesi = Jumlah Sesi × Tarif Per Sesi (default Rp50.000)
     * Fee Check-in = Jumlah Sesi × Rp20.000
     * Fee Transport = Jumlah Sesi × Rp30.000
     * Total Honor = Honor Sesi + Fee Check-in + Fee Transport + Penyesuaian
     */
    public function hitungHonor(
        int $idTutor,
        string $periodeBulan,
        ?int $jumlahSesi = null,
        ?float $tarifPerSesi = null,
        float $penyesuaianHonor = 0,
        ?float $feeCheckIn = null,
        ?float $feeTransport = null,
        ?string $catatan = null
    ): array {
        $periode = Carbon::parse($periodeBulan);
        $awalBulan = $periode->copy()->startOfMonth();
        $akhirBulan = $periode->copy()->endOfMonth();

        // Jika jumlah sesi tidak diisi, hitung otomatis dari absensi status Hadir
        if ($jumlahSesi === null) {
            $jumlahSesi = $this->hitungJumlahSesi($idTutor, $awalBulan, $akhirBulan);
        }

        $tarifPerSesi = $tarifPerSesi ?? self::TARIF_PER_SESI_DEFAULT;
        $feeCheckIn = $feeCheckIn ?? ($jumlahSesi * self::FEE_CHECK_IN_PER_SESI);
        $feeTransport = $feeTransport ?? ($jumlahSesi * self::FEE_TRANSPORT_PER_SESI);

        $honorSesi = $jumlahSesi * $tarifPerSesi;
        $totalHonor = max(0, $honorSesi + $feeCheckIn + $feeTransport + $penyesuaianHonor);

        return [
            'id_tutor' => $idTutor,
            'periode_bulan' => $awalBulan->format('Y-m-d'),
            'jumlah_sesi' => $jumlahSesi,
            'tarif_per_sesi' => $tarifPerSesi,
            'fee_check_in' => $feeCheckIn,
            'fee_transport' => $feeTransport,
            'penyesuaian_honor' => $penyesuaianHonor,
            'total_honor' => round($totalHonor, 2),
            'catatan' => $catatan,
        ];
    }

    /**
     * Menghitung jumlah sesi mengajar berdasarkan absensi Hadir pada periode tertentu.
     */
    public function hitungJumlahSesi(int $idTutor, Carbon $dari, Carbon $sampai): int
    {
        return Absensi::where('id_tutor', $idTutor)
            ->where('status', 'Hadir')
            ->whereBetween('tanggal', [$dari->format('Y-m-d'), $sampai->format('Y-m-d')])
            ->count();
    }
}
