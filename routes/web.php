<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\HonorController;
use App\Http\Controllers\PembayaranHonorController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PermintaanLaporanController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tutor
    Route::resource('tutor', TutorController::class)->parameters(['tutor' => 'tutor:id_tutor']);

    // Sekolah
    Route::resource('sekolah', SekolahController::class)->parameters(['sekolah' => 'sekolah:id_sekolah']);

    // Jadwal
    Route::resource('jadwal', JadwalController::class)->parameters(['jadwal' => 'jadwal:id_jadwal']);

    // Absensi
    Route::resource('absensi', AbsensiController::class)->parameters(['absensi' => 'absensi:id_absensi']);

    // Honor
    Route::resource('honor', HonorController::class)->parameters(['honor' => 'honor:id_honor']);

    // Pembayaran Honor
    Route::resource('pembayaran-honor', PembayaranHonorController::class)->parameters(['pembayaran-honor' => 'pembayaranHonor:id_pembayaran']);

    // Penilaian
    Route::resource('penilaian', PenilaianController::class)->parameters(['penilaian' => 'penilaian:id_penilaian']);

    // Permintaan Laporan
    Route::resource('permintaan-laporan', PermintaanLaporanController::class)->parameters(['permintaan-laporan' => 'permintaanLaporan:id_permintaan']);

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/tutor', [LaporanController::class, 'tutor'])->name('tutor');
        Route::get('/absensi', [LaporanController::class, 'absensi'])->name('absensi');
        Route::get('/jadwal', [LaporanController::class, 'jadwal'])->name('jadwal');
        Route::get('/honor', [LaporanController::class, 'honor'])->name('honor');
        Route::get('/operasional', [LaporanController::class, 'operasional'])->name('operasional');
    });
});
