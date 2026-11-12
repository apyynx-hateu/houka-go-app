<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Petinggi;
use App\Models\Tutor;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Petinggi (password di-hash)
        Petinggi::create([
            'nama' => 'Admin Houka',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'jabatan' => 'CO Founder',
            'status_aktif' => 'Aktif',
        ]);

        Petinggi::create([
            'nama' => 'Staff Operasional',
            'username' => 'operasional',
            'password' => Hash::make('password'),
            'jabatan' => 'Operasional',
            'status_aktif' => 'Aktif',
        ]);

        Petinggi::create([
            'nama' => 'Staff HR',
            'username' => 'hr',
            'password' => Hash::make('password'),
            'jabatan' => 'Human Resource',
            'status_aktif' => 'Aktif',
        ]);

        // Tutor contoh
        $tutors = [
            ['nama' => 'Ahmad Fauzi', 'username' => 'ahmad', 'password' => Hash::make('password'), 'jenis_kelamin' => 'Laki-laki', 'no_telepon' => '081234567890', 'email' => 'ahmad@email.com', 'pendidikan_terakhir' => 'S1 Matematika', 'status_kerja' => 'Aktif', 'tanggal_bergabung' => '2024-01-15'],
            ['nama' => 'Siti Nurhaliza', 'username' => 'siti', 'password' => Hash::make('password'), 'jenis_kelamin' => 'Perempuan', 'no_telepon' => '081234567891', 'email' => 'siti@email.com', 'pendidikan_terakhir' => 'S1 Bahasa Inggris', 'status_kerja' => 'Aktif', 'tanggal_bergabung' => '2024-02-01'],
            ['nama' => 'Budi Santoso', 'username' => 'budi', 'password' => Hash::make('password'), 'jenis_kelamin' => 'Laki-laki', 'no_telepon' => '081234567892', 'email' => 'budi@email.com', 'pendidikan_terakhir' => 'S1 Fisika', 'status_kerja' => 'Aktif', 'tanggal_bergabung' => '2024-03-10'],
            ['nama' => 'Dewi Lestari', 'username' => 'dewi', 'password' => Hash::make('password'), 'jenis_kelamin' => 'Perempuan', 'no_telepon' => '081234567893', 'email' => 'dewi@email.com', 'pendidikan_terakhir' => 'S1 Biologi', 'status_kerja' => 'Tidak Aktif', 'tanggal_bergabung' => '2023-06-01'],
        ];

        foreach ($tutors as $tutor) {
            Tutor::create($tutor);
        }

        // Sekolah contoh
        $sekolahs = [
            ['nama_sekolah' => 'SMA Negeri 1 Jakarta', 'alamat' => 'Jl. Budi Utomo No. 7, Jakarta Pusat', 'ruangan' => 'Ruang 101', 'kapasitas' => 30],
            ['nama_sekolah' => 'SMP Harapan Bangsa', 'alamat' => 'Jl. Merdeka No. 10, Bandung', 'ruangan' => 'Ruang A1', 'kapasitas' => 25],
            ['nama_sekolah' => 'SD Islam Terpadu', 'alamat' => 'Jl. Kebangsaan No. 5, Surabaya', 'ruangan' => 'Ruang B2', 'kapasitas' => 20],
        ];

        foreach ($sekolahs as $sekolah) {
            Sekolah::create($sekolah);
        }

        // Jadwal contoh
        $ahmad = Tutor::where('username', 'ahmad')->first();
        $siti = Tutor::where('username', 'siti')->first();
        $sma1 = Sekolah::first();
        $smpHb = Sekolah::skip(1)->first();

        if ($ahmad && $sma1) {
            \App\Models\Jadwal::create([
                'id_tutor' => $ahmad->id_tutor,
                'id_sekolah' => $sma1->id_sekolah,
                'hari' => 'Senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:00',
            ]);

            \App\Models\Jadwal::create([
                'id_tutor' => $ahmad->id_tutor,
                'id_sekolah' => $sma1->id_sekolah,
                'hari' => 'Rabu',
                'jam_mulai' => '10:00',
                'jam_selesai' => '12:00',
            ]);
        }

        if ($siti && $smpHb) {
            \App\Models\Jadwal::create([
                'id_tutor' => $siti->id_tutor,
                'id_sekolah' => $smpHb->id_sekolah,
                'hari' => 'Selasa',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:00',
            ]);
        }

        // Absensi contoh
        $jadwalAhmad = \App\Models\Jadwal::where('id_tutor', $ahmad->id_tutor)->first();
        if ($jadwalAhmad) {
            \App\Models\Absensi::create([
                'id_tutor' => $ahmad->id_tutor,
                'id_jadwal' => $jadwalAhmad->id_jadwal,
                'tanggal' => now()->subDays(7)->format('Y-m-d'),
                'status' => 'Hadir',
                'waktu_check_in' => '07:55:00',
                'keterangan' => 'Hadir tepat waktu mengajar Matematika Bab 1',
            ]);

            \App\Models\Absensi::create([
                'id_tutor' => $ahmad->id_tutor,
                'id_jadwal' => $jadwalAhmad->id_jadwal,
                'tanggal' => now()->subDays(5)->format('Y-m-d'),
                'status' => 'Hadir',
                'waktu_check_in' => '07:58:00',
                'keterangan' => 'Hadir sesi latihan soal Matematika',
            ]);
        }

        // Honor contoh untuk Ahmad (2 sesi: tarif 50k, fee check in 20k, fee transport 30k = 100k/sesi -> 200k)
        if ($ahmad) {
            $honorAhmad = \App\Models\Honor::create([
                'id_tutor' => $ahmad->id_tutor,
                'periode_bulan' => now()->startOfMonth()->format('Y-m-d'),
                'jumlah_sesi' => 2,
                'tarif_per_sesi' => 50000,
                'fee_check_in' => 40000,
                'fee_transport' => 60000,
                'penyesuaian_honor' => 0,
                'total_honor' => 200000,
                'status' => 'Diproses',
                'catatan' => 'Honor periode berjalan 2 sesi kehadiran',
            ]);

            \App\Models\PembayaranHonor::create([
                'id_honor' => $honorAhmad->id_honor,
                'tanggal_pembayaran' => now()->format('Y-m-d'),
                'jumlah' => 200000,
                'nomor_rekening' => 'BCA 5210987654',
                'status' => 'Pending',
                'keterangan' => 'Menunggu verifikasi transfer finance',
            ]);
        }

        // Penilaian contoh
        $admin = Petinggi::first();
        if ($admin && $ahmad) {
            \App\Models\Penilaian::create([
                'id_tutor' => $ahmad->id_tutor,
                'id_petinggi' => $admin->id_petinggi,
                'tanggal_penilaian' => now()->subDays(2)->format('Y-m-d'),
                'periode' => now()->format('F Y'),
                'kualitas_mengajar' => 'Sangat baik, komunikasi jelas dan materi mudah dipahami siswa.',
                'catatan_kinerja' => 'Disiplin dan selalu hadir 10 menit sebelum jadwal dimulai.',
            ]);
        }
    }
}
