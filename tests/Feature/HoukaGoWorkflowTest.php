<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Petinggi;
use App\Models\Tutor;
use App\Models\Sekolah;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Honor;
use App\Models\PembayaranHonor;
use App\Models\Penilaian;
use App\Models\PermintaanLaporan;
use Illuminate\Support\Facades\Hash;

class HoukaGoWorkflowTest extends TestCase
{
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Petinggi::firstOrCreate(
            ['username' => 'test_admin'],
            [
                'nama' => 'Test Admin Petinggi',
                'password' => Hash::make('password123'),
                'jabatan' => 'CO Founder',
                'status_aktif' => 'Aktif',
            ]
        );
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Houka');
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => 'test_admin',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_dashboard_access_and_metrics(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Test Admin Petinggi');
        $response->assertSee('Statistik Master');
    }

    public function test_tutor_crud(): void
    {
        // Create
        $response = $this->actingAs($this->admin)->post('/tutor', [
            'nama' => 'Tutor Uji Coba',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'tutoruji@example.com',
            'no_telepon' => '089912345678',
            'pendidikan_terakhir' => 'S1 Komputer',
            'status_kerja' => 'Aktif',
            'tanggal_bergabung' => '2024-01-01',
        ]);
        $response->assertRedirect('/tutor');

        $tutor = Tutor::where('email', 'tutoruji@example.com')->first();
        $this->assertNotNull($tutor);

        // Read Index & Show
        $indexResp = $this->actingAs($this->admin)->get('/tutor');
        $indexResp->assertStatus(200);
        $indexResp->assertSee('Tutor Uji Coba');

        $showResp = $this->actingAs($this->admin)->get("/tutor/{$tutor->id_tutor}");
        $showResp->assertStatus(200);
        $showResp->assertSee('Tutor Uji Coba');

        // Update
        $updateResp = $this->actingAs($this->admin)->put("/tutor/{$tutor->id_tutor}", [
            'nama' => 'Tutor Uji Coba Updated',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'tutoruji@example.com',
            'status_kerja' => 'Aktif',
        ]);
        $updateResp->assertRedirect('/tutor');
        $this->assertEquals('Tutor Uji Coba Updated', $tutor->fresh()->nama);
    }

    public function test_sekolah_crud(): void
    {
        $response = $this->actingAs($this->admin)->post('/sekolah', [
            'nama_sekolah' => 'SMA Test Labs',
            'alamat' => 'Jl. Pengujian No. 1',
            'ruangan' => 'Lab 1',
            'kapasitas' => 25,
            'status_aktif' => 'Aktif',
        ]);
        $response->assertRedirect('/sekolah');

        $sekolah = Sekolah::where('nama_sekolah', 'SMA Test Labs')->first();
        $this->assertNotNull($sekolah);

        $showResp = $this->actingAs($this->admin)->get("/sekolah/{$sekolah->id_sekolah}");
        $showResp->assertStatus(200);
        $showResp->assertSee('SMA Test Labs');
    }

    public function test_jadwal_crud_and_conflict_detection(): void
    {
        $tutor = Tutor::first();
        $sekolah = Sekolah::first();

        // Create jadwal
        $response = $this->actingAs($this->admin)->post('/jadwal', [
            'id_tutor' => $tutor->id_tutor,
            'id_sekolah' => $sekolah->id_sekolah,
            'hari' => 'Senin',
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
        ]);
        $response->assertRedirect('/jadwal');

        $jadwal = Jadwal::where('id_tutor', $tutor->id_tutor)->where('hari', 'Senin')->first();
        $this->assertNotNull($jadwal);

        // Attempt overlapping jadwal for same tutor on same day
        $conflictResp = $this->actingAs($this->admin)->post('/jadwal', [
            'id_tutor' => $tutor->id_tutor,
            'id_sekolah' => $sekolah->id_sekolah,
            'hari' => 'Senin',
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
        ]);
        $conflictResp->assertSessionHasErrors('jam_mulai');
    }

    public function test_absensi_flow_and_duplicate_prevention(): void
    {
        $tutor = Tutor::first();
        $jadwal = Jadwal::where('id_tutor', $tutor->id_tutor)->first();

        $tanggal = '2025-05-10';

        $response = $this->actingAs($this->admin)->post('/absensi', [
            'id_tutor' => $tutor->id_tutor,
            'id_jadwal' => $jadwal ? $jadwal->id_jadwal : null,
            'tanggal' => $tanggal,
            'status' => 'Hadir',
            'keterangan' => 'Hadir mengajar materi bab 1',
        ]);
        $response->assertRedirect('/absensi');

        // Prevent duplicate attendance
        $dupResponse = $this->actingAs($this->admin)->post('/absensi', [
            'id_tutor' => $tutor->id_tutor,
            'id_jadwal' => $jadwal ? $jadwal->id_jadwal : null,
            'tanggal' => $tanggal,
            'status' => 'Hadir',
        ]);
        $dupResponse->assertSessionHasErrors('tanggal');
    }

    public function test_honor_calculation_and_pembayaran_workflow(): void
    {
        $tutor = Tutor::first();

        // Buat honor
        $response = $this->actingAs($this->admin)->post('/honor', [
            'id_tutor' => $tutor->id_tutor,
            'periode_bulan' => '2025-05',
            'tarif_per_jam' => 50000,
            'jumlah_siswa' => 10,
            'penyesuaian_honor' => 20000,
        ]);
        $response->assertRedirect('/honor');

        $honor = Honor::where('id_tutor', $tutor->id_tutor)->orderByDesc('id_honor')->first();
        $this->assertNotNull($honor);
        $this->assertEquals('Belum Dibayar', $honor->status);

        // Catat pembayaran pending
        $pembResp = $this->actingAs($this->admin)->post('/pembayaran-honor', [
            'id_honor' => $honor->id_honor,
            'tanggal_pembayaran' => '2025-05-30',
            'jumlah' => $honor->total_honor,
            'nomor_rekening' => 'BCA 1234567890',
            'keterangan' => 'Transfer honor Mei',
        ]);
        $pembResp->assertRedirect('/pembayaran-honor');

        $this->assertEquals('Diproses', $honor->fresh()->status);

        $pembayaran = PembayaranHonor::where('id_honor', $honor->id_honor)->first();
        $this->assertNotNull($pembayaran);

        // Konfirmasi pembayaran berhasil -> honor lunas (Dibayar)
        $this->actingAs($this->admin)->put("/pembayaran-honor/{$pembayaran->id_pembayaran}", [
            'tanggal_pembayaran' => '2025-05-30',
            'jumlah' => $honor->total_honor,
            'nomor_rekening' => 'BCA 1234567890',
            'status' => 'Berhasil',
        ]);

        $this->assertEquals('Dibayar', $honor->fresh()->status);
    }

    public function test_penilaian_crud(): void
    {
        $tutor = Tutor::first();

        $response = $this->actingAs($this->admin)->post('/penilaian', [
            'id_tutor' => $tutor->id_tutor,
            'tanggal_penilaian' => date('Y-m-d'),
            'periode' => 'Semester Genap 2024',
            'kualitas_mengajar' => 'Sangat komunikatif dan menguasai bahan',
            'catatan_kinerja' => 'Disiplin dan selalu hadir tepat waktu',
        ]);
        $response->assertRedirect('/penilaian');

        $penilaian = Penilaian::where('id_tutor', $tutor->id_tutor)->first();
        $this->assertNotNull($penilaian);
        $this->assertEquals($this->admin->id_petinggi, $penilaian->id_petinggi);
    }

    public function test_permintaan_laporan_workflow(): void
    {
        $response = $this->actingAs($this->admin)->post('/permintaan-laporan', [
            'jenis' => 'Rekap Tutor',
            'periode_awal' => '2024-01-01',
            'periode_akhir' => '2024-12-31',
            'keterangan' => 'Permintaan untuk evaluasi akhir tahun',
        ]);
        $response->assertRedirect('/permintaan-laporan');

        $permintaan = PermintaanLaporan::where('id_petinggi', $this->admin->id_petinggi)->first();
        $this->assertNotNull($permintaan);
        $this->assertEquals('Menunggu', $permintaan->status);

        // Update to Selesai
        $this->actingAs($this->admin)->put("/permintaan-laporan/{$permintaan->id_permintaan}", [
            'status' => 'Selesai',
            'keterangan' => 'Dokumen rekap tutor sudah siap diunduh',
        ]);
        $this->assertEquals('Selesai', $permintaan->fresh()->status);
    }

    public function test_all_laporan_endpoints(): void
    {
        $endpoints = [
            '/laporan/tutor',
            '/laporan/absensi',
            '/laporan/jadwal',
            '/laporan/honor',
            '/laporan/operasional',
        ];

        foreach ($endpoints as $url) {
            $resp = $this->actingAs($this->admin)->get($url);
            $resp->assertStatus(200);
        }
    }
}
