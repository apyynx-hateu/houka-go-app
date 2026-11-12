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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HoukaGoWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;
    protected $tutor1;
    protected $tutor2;

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

        $this->tutor1 = Tutor::firstOrCreate(
            ['username' => 'test_tutor1'],
            [
                'nama' => 'Test Tutor Satu',
                'password' => Hash::make('password123'),
                'email' => 'tutor1@test.com',
                'status_kerja' => 'Aktif',
            ]
        );

        $this->tutor2 = Tutor::firstOrCreate(
            ['username' => 'test_tutor2'],
            [
                'nama' => 'Test Tutor Dua',
                'password' => Hash::make('password123'),
                'email' => 'tutor2@test.com',
                'status_kerja' => 'Aktif',
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
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');

        $tutorResp = $this->get('/tutor/dashboard');
        $tutorResp->assertRedirect('/login');
    }

    public function test_login_with_valid_admin_credentials_redirects_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'username' => 'test_admin',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(Auth::guard('petinggi')->check());
    }

    public function test_login_with_valid_tutor_credentials_redirects_to_tutor_dashboard(): void
    {
        $response = $this->post('/login', [
            'username' => 'test_tutor1',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('tutor.dashboard'));
        $this->assertTrue(Auth::guard('tutor')->check());
    }

    public function test_invalid_credentials_rejected(): void
    {
        $response = $this->post('/login', [
            'username' => 'test_admin',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertFalse(Auth::guard('petinggi')->check());
        $this->assertFalse(Auth::guard('tutor')->check());
    }

    public function test_inactive_accounts_cannot_login(): void
    {
        $inactiveTutor = Tutor::create([
            'nama' => 'Tutor Nonaktif',
            'username' => 'inactive_tutor',
            'password' => Hash::make('password123'),
            'status_kerja' => 'Tidak Aktif',
        ]);

        $response = $this->post('/login', [
            'username' => 'inactive_tutor',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertFalse(Auth::guard('tutor')->check());
    }

    public function test_tutor_cannot_access_admin_routes(): void
    {
        $response = $this->actingAs($this->tutor1, 'tutor')->get('/admin/dashboard');
        $response->assertStatus(403);

        $responseTutors = $this->actingAs($this->tutor1, 'tutor')->get('/admin/tutors');
        $responseTutors->assertStatus(403);
    }

    public function test_tutor_cannot_access_another_tutors_honor(): void
    {
        // Buat honor milik tutor2
        $honorTutor2 = Honor::create([
            'id_tutor' => $this->tutor2->id_tutor,
            'periode_bulan' => '2026-05-01',
            'jumlah_sesi' => 4,
            'tarif_per_sesi' => 50000,
            'fee_check_in' => 80000,
            'fee_transport' => 120000,
            'total_honor' => 400000,
            'status' => 'Belum Dibayar',
        ]);

        // Tutor 1 mencoba membuka detail honor milik Tutor 2
        $response = $this->actingAs($this->tutor1, 'tutor')->get("/tutor/honors/{$honorTutor2->id_honor}");
        $response->assertStatus(403);
    }

    public function test_tutor_crud_by_admin(): void
    {
        // Create
        $response = $this->actingAs($this->admin, 'petinggi')->post(route('tutor.store'), [
            'nama' => 'Tutor Baru Diuji',
            'username' => 'tutor_baru_test',
            'password' => 'secret123',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'tutorbaru@example.com',
            'no_telepon' => '089912345678',
            'pendidikan_terakhir' => 'S1 Komputer',
            'status_kerja' => 'Aktif',
            'tanggal_bergabung' => '2024-01-01',
        ]);
        $response->assertRedirect(route('tutor.index'));

        $tutor = Tutor::where('username', 'tutor_baru_test')->first();
        $this->assertNotNull($tutor);
        $this->assertTrue(Hash::check('secret123', $tutor->password));

        // Read Index & Show
        $indexResp = $this->actingAs($this->admin, 'petinggi')->get(route('tutor.index'));
        $indexResp->assertStatus(200);
        $indexResp->assertSee('Tutor Baru Diuji');

        $showResp = $this->actingAs($this->admin, 'petinggi')->get(route('tutor.show', $tutor->id_tutor));
        $showResp->assertStatus(200);
        $showResp->assertSee('Tutor Baru Diuji');

        // Update
        $updateResp = $this->actingAs($this->admin, 'petinggi')->put(route('tutor.update', $tutor->id_tutor), [
            'nama' => 'Tutor Baru Diuji Update',
            'status_kerja' => 'Aktif',
        ]);
        $updateResp->assertRedirect(route('tutor.index'));
        $this->assertEquals('Tutor Baru Diuji Update', $tutor->fresh()->nama);
    }

    public function test_sekolah_crud_by_admin(): void
    {
        $response = $this->actingAs($this->admin, 'petinggi')->post(route('sekolah.store'), [
            'nama_sekolah' => 'SMA Test Labs Modern',
            'alamat' => 'Jl. Pengujian No. 1',
            'ruangan' => 'Lab 1',
            'kapasitas' => 25,
            'status_aktif' => 'Aktif',
        ]);
        $response->assertRedirect(route('sekolah.index'));

        $sekolah = Sekolah::where('nama_sekolah', 'SMA Test Labs Modern')->first();
        $this->assertNotNull($sekolah);

        $showResp = $this->actingAs($this->admin, 'petinggi')->get(route('sekolah.show', $sekolah->id_sekolah));
        $showResp->assertStatus(200);
        $showResp->assertSee('SMA Test Labs Modern');
    }

    public function test_jadwal_crud_and_conflict_detection(): void
    {
        $sekolah = Sekolah::first() ?? Sekolah::create([
            'nama_sekolah' => 'SMA Houka Pusat',
            'ruangan' => 'R101',
            'kapasitas' => 20,
        ]);

        // Create jadwal
        $response = $this->actingAs($this->admin, 'petinggi')->post(route('jadwal.store'), [
            'id_tutor' => $this->tutor1->id_tutor,
            'id_sekolah' => $sekolah->id_sekolah,
            'hari' => 'Kamis',
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
        ]);
        $response->assertRedirect(route('jadwal.index'));

        $jadwal = Jadwal::where('id_tutor', $this->tutor1->id_tutor)->where('hari', 'Kamis')->first();
        $this->assertNotNull($jadwal);

        // Attempt overlapping jadwal for same tutor on same day
        $conflictResp = $this->actingAs($this->admin, 'petinggi')->post(route('jadwal.store'), [
            'id_tutor' => $this->tutor1->id_tutor,
            'id_sekolah' => $sekolah->id_sekolah,
            'hari' => 'Kamis',
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
        ]);
        $conflictResp->assertSessionHasErrors('jam_mulai');
    }

    public function test_tutor_self_check_in_workflow(): void
    {
        $sekolah = Sekolah::first();
        $jadwal = Jadwal::create([
            'id_tutor' => $this->tutor1->id_tutor,
            'id_sekolah' => $sekolah->id_sekolah,
            'hari' => 'Jumat',
            'jam_mulai' => '14:00',
            'jam_selesai' => '16:00',
        ]);

        $tanggal = '2026-10-30';

        // Tutor 1 self check-in
        $response = $this->actingAs($this->tutor1, 'tutor')->post(route('tutor.attendance.checkin'), [
            'id_jadwal' => $jadwal->id_jadwal,
            'tanggal' => $tanggal,
            'keterangan' => 'Hadir mengajar Matematika',
        ]);
        $response->assertRedirect(route('tutor.attendance'));

        $absensi = Absensi::where('id_tutor', $this->tutor1->id_tutor)
            ->where('id_jadwal', $jadwal->id_jadwal)
            ->where('tanggal', $tanggal)
            ->first();

        $this->assertNotNull($absensi);
        $this->assertEquals('Hadir', $absensi->status);
        $this->assertNotNull($absensi->waktu_check_in);

        // Prevent duplicate check-in
        $dupResp = $this->actingAs($this->tutor1, 'tutor')->post(route('tutor.attendance.checkin'), [
            'id_jadwal' => $jadwal->id_jadwal,
            'tanggal' => $tanggal,
        ]);
        $dupResp->assertSessionHasErrors('tanggal');
    }

    public function test_session_based_honor_calculation_and_pembayaran_workflow(): void
    {
        // Buat data honor berbasis sesi: 8 sesi
        // Sesi: 8 x 50.000 = 400.000
        // Fee check-in: 8 x 20.000 = 160.000
        // Fee transport: 8 x 30.000 = 240.000
        // Total = 800.000
        $response = $this->actingAs($this->admin, 'petinggi')->post(route('honor.store'), [
            'id_tutor' => $this->tutor1->id_tutor,
            'periode_bulan' => '2026-06',
            'jumlah_sesi' => 8,
            'tarif_per_sesi' => 50000,
            'penyesuaian_honor' => 0,
        ]);
        $response->assertRedirect(route('honor.index'));

        $honor = Honor::where('id_tutor', $this->tutor1->id_tutor)
            ->whereMonth('periode_bulan', 6)
            ->whereYear('periode_bulan', 2026)
            ->first();

        $this->assertNotNull($honor);
        $this->assertEquals(8, $honor->jumlah_sesi);
        $this->assertEquals(800000, (float) $honor->total_honor);
        $this->assertEquals('Belum Dibayar', $honor->status);

        // Catat pembayaran pending
        $pembResp = $this->actingAs($this->admin, 'petinggi')->post(route('pembayaran-honor.store'), [
            'id_honor' => $honor->id_honor,
            'tanggal_pembayaran' => '2026-06-30',
            'jumlah' => $honor->total_honor,
            'nomor_rekening' => 'BCA 1234567890',
            'status' => 'Pending',
            'keterangan' => 'Transfer honor Juni 8 sesi',
        ]);
        $pembResp->assertRedirect(route('pembayaran-honor.index'));

        $this->assertEquals('Diproses', $honor->fresh()->status);

        $pembayaran = PembayaranHonor::where('id_honor', $honor->id_honor)->first();
        $this->assertNotNull($pembayaran);

        // Update status pembayaran menjadi Berhasil -> Honor menjadi Dibayar
        $this->actingAs($this->admin, 'petinggi')->put(route('pembayaran-honor.update', $pembayaran->id_pembayaran), [
            'tanggal_pembayaran' => '2026-06-30',
            'jumlah' => $honor->total_honor,
            'nomor_rekening' => 'BCA 1234567890',
            'status' => 'Berhasil',
        ]);

        $this->assertEquals('Dibayar', $honor->fresh()->status);
    }

    public function test_failed_payment_does_not_mark_honor_as_dibayar(): void
    {
        $honor = Honor::create([
            'id_tutor' => $this->tutor1->id_tutor,
            'periode_bulan' => '2026-07-01',
            'jumlah_sesi' => 2,
            'tarif_per_sesi' => 50000,
            'fee_check_in' => 40000,
            'fee_transport' => 60000,
            'total_honor' => 200000,
            'status' => 'Belum Dibayar',
        ]);

        $pembayaran = PembayaranHonor::create([
            'id_honor' => $honor->id_honor,
            'tanggal_pembayaran' => '2026-07-31',
            'jumlah' => 200000,
            'status' => 'Gagal',
        ]);

        // Simulasikan update status transaksi gagal
        $this->actingAs($this->admin, 'petinggi')->put(route('pembayaran-honor.update', $pembayaran->id_pembayaran), [
            'tanggal_pembayaran' => '2026-07-31',
            'jumlah' => 200000,
            'status' => 'Gagal',
        ]);

        $this->assertNotEquals('Dibayar', $honor->fresh()->status);
    }

    public function test_penilaian_crud_by_admin(): void
    {
        $response = $this->actingAs($this->admin, 'petinggi')->post(route('penilaian.store'), [
            'id_tutor' => $this->tutor1->id_tutor,
            'tanggal_penilaian' => date('Y-m-d'),
            'periode' => 'Semester 1 2026',
            'kualitas_mengajar' => 'Sangat komunikatif dan interaktif',
            'catatan_kinerja' => 'Disiplin dan ramah',
        ]);
        $response->assertRedirect(route('penilaian.index'));

        $penilaian = Penilaian::where('id_tutor', $this->tutor1->id_tutor)->latest('id_penilaian')->first();
        $this->assertNotNull($penilaian);
        $this->assertEquals($this->admin->id_petinggi, $penilaian->id_petinggi);
    }

    public function test_admin_profile_update(): void
    {
        $response = $this->actingAs($this->admin, 'petinggi')->put(route('admin.profile.update'), [
            'nama' => 'Administrator Utama Updated',
            'username' => 'test_admin_updated',
        ]);
        $response->assertRedirect(route('admin.profile'));
        $this->assertEquals('Administrator Utama Updated', $this->admin->fresh()->nama);
    }

    public function test_tutor_profile_update(): void
    {
        $response = $this->actingAs($this->tutor1, 'tutor')->put(route('tutor.profile.update'), [
            'no_telepon' => '081299998888',
            'email' => 'tutor1_updated@test.com',
            'alamat' => 'Alamat baru tutor',
            'pendidikan_terakhir' => 'S2 Pendidikan',
        ]);
        $response->assertRedirect(route('tutor.profile'));
        $this->assertEquals('081299998888', $this->tutor1->fresh()->no_telepon);
        $this->assertEquals('tutor1_updated@test.com', $this->tutor1->fresh()->email);
    }

    public function test_all_laporan_endpoints_accessible_by_admin(): void
    {
        $endpoints = [
            route('laporan.tutor'),
            route('laporan.absensi'),
            route('laporan.jadwal'),
            route('laporan.honor'),
            route('laporan.operasional'),
        ];

        foreach ($endpoints as $url) {
            $resp = $this->actingAs($this->admin, 'petinggi')->get($url);
            $resp->assertStatus(200);
        }
    }
}
