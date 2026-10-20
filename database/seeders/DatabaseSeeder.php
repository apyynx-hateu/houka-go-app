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
            ['nama' => 'Ahmad Fauzi', 'jenis_kelamin' => 'Laki-laki', 'no_telepon' => '081234567890', 'email' => 'ahmad@email.com', 'pendidikan_terakhir' => 'S1 Matematika', 'status_kerja' => 'Aktif', 'tanggal_bergabung' => '2024-01-15'],
            ['nama' => 'Siti Nurhaliza', 'jenis_kelamin' => 'Perempuan', 'no_telepon' => '081234567891', 'email' => 'siti@email.com', 'pendidikan_terakhir' => 'S1 Bahasa Inggris', 'status_kerja' => 'Aktif', 'tanggal_bergabung' => '2024-02-01'],
            ['nama' => 'Budi Santoso', 'jenis_kelamin' => 'Laki-laki', 'no_telepon' => '081234567892', 'email' => 'budi@email.com', 'pendidikan_terakhir' => 'S1 Fisika', 'status_kerja' => 'Aktif', 'tanggal_bergabung' => '2024-03-10'],
            ['nama' => 'Dewi Lestari', 'jenis_kelamin' => 'Perempuan', 'no_telepon' => '081234567893', 'email' => 'dewi@email.com', 'pendidikan_terakhir' => 'S1 Biologi', 'status_kerja' => 'Tidak Aktif', 'tanggal_bergabung' => '2023-06-01'],
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
    }
}
