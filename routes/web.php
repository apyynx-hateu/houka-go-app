<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\HonorController;
use App\Http\Controllers\PembayaranHonorController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AdminProfileController;

// Tutor Portal Controllers
use App\Http\Controllers\Tutor\DashboardController as TutorDashboardController;
use App\Http\Controllers\Tutor\ProfileController as TutorProfileController;
use App\Http\Controllers\Tutor\JadwalController as TutorJadwalController;
use App\Http\Controllers\Tutor\AbsensiController as TutorAbsensiController;
use App\Http\Controllers\Tutor\HonorController as TutorHonorController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Root redirection
Route::get('/', function () {
    if (Auth::guard('petinggi')->check()) {
        return redirect()->route('admin.dashboard');
    }
    if (Auth::guard('tutor')->check()) {
        return redirect()->route('tutor.dashboard');
    }
    return redirect()->route('login');
});

// Common /dashboard route redirection
Route::get('/dashboard', function () {
    if (Auth::guard('petinggi')->check()) {
        return redirect()->route('admin.dashboard');
    }
    if (Auth::guard('tutor')->check()) {
        return redirect()->route('tutor.dashboard');
    }
    return redirect()->route('login');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin (Petinggi) Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['role:ADMIN'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Admin Profile
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('admin.profile');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');

    // Master Data & Operasional
    Route::resource('tutors', TutorController::class)
        ->names('tutor')
        ->parameters(['tutors' => 'tutor:id_tutor']);

    Route::resource('schools', SekolahController::class)
        ->names('sekolah')
        ->parameters(['schools' => 'sekolah:id_sekolah']);

    Route::resource('schedules', JadwalController::class)
        ->names('jadwal')
        ->parameters(['schedules' => 'jadwal:id_jadwal']);

    Route::resource('attendance', AbsensiController::class)
        ->names('absensi')
        ->parameters(['attendance' => 'absensi:id_absensi']);

    Route::resource('honors', HonorController::class)
        ->names('honor')
        ->parameters(['honors' => 'honor:id_honor']);

    Route::resource('payments', PembayaranHonorController::class)
        ->names('pembayaran-honor')
        ->parameters(['payments' => 'pembayaranHonor:id_pembayaran']);

    Route::resource('evaluations', PenilaianController::class)
        ->names('penilaian')
        ->parameters(['evaluations' => 'penilaian:id_penilaian']);

    // Rekap Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/tutor', [LaporanController::class, 'tutor'])->name('tutor');
        Route::get('/absensi', [LaporanController::class, 'absensi'])->name('absensi');
        Route::get('/jadwal', [LaporanController::class, 'jadwal'])->name('jadwal');
        Route::get('/honor', [LaporanController::class, 'honor'])->name('honor');
        Route::get('/operasional', [LaporanController::class, 'operasional'])->name('operasional');
    });
});

/*
|--------------------------------------------------------------------------
| Tutor (Pengajar) Portal Routes
|--------------------------------------------------------------------------
*/
Route::prefix('tutor')->name('tutor.')->middleware(['role:TUTOR'])->group(function () {

    // Dashboard Tutor
    Route::get('/dashboard', [TutorDashboardController::class, 'index'])->name('dashboard');

    // Profil Tutor
    Route::get('/profile', [TutorProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [TutorProfileController::class, 'update'])->name('profile.update');

    // Jadwal Tutor
    Route::get('/schedules', [TutorJadwalController::class, 'index'])->name('schedules');

    // Absensi & Check-in
    Route::get('/attendance', [TutorAbsensiController::class, 'index'])->name('attendance');
    Route::post('/attendance/check-in', [TutorAbsensiController::class, 'checkIn'])->name('attendance.checkin');

    // Honor Tutor
    Route::get('/honors', [TutorHonorController::class, 'index'])->name('honors');
    Route::get('/honors/{honor}', [TutorHonorController::class, 'show'])->name('honors.show');
});
