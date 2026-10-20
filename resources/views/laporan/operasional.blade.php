@extends('layouts.app')

@section('title', 'Laporan Ringkasan Operasional')
@section('page_description', 'Laporan eksekutif performa institusional, aset kemitraan, dan perputaran operasional')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Laporan Eksekutif Operasional</h3>
            <p class="text-xs text-slate-500">Kompilasi menyeluruh seluruh modul sistem bimbingan belajar Houka GO</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Laporan Lengkap
            </button>
        </div>
    </div>

    <!-- Executive Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Tutor -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">SDM & Tutor</span>
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
            </div>
            <div class="text-3xl font-extrabold text-slate-800">{{ $data['totalTutor'] }}</div>
            <div class="text-xs text-slate-500 mt-2">
                <strong class="text-emerald-600 font-semibold">{{ $data['tutorAktif'] }} Aktif</strong> &bull; {{ $data['totalTutor'] - $data['tutorAktif'] }} Tidak Aktif
            </div>
        </div>

        <!-- Sekolah -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kemitraan Sekolah</span>
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
            </div>
            <div class="text-3xl font-extrabold text-slate-800">{{ $data['totalSekolah'] }}</div>
            <div class="text-xs text-slate-500 mt-2">
                <strong class="text-emerald-600 font-semibold">{{ $data['sekolahAktif'] }} Aktif</strong> &bull; Seluruh lokasi berjalan
            </div>
        </div>

        <!-- Jadwal -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Sesi Mengajar</span>
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
            </div>
            <div class="text-3xl font-extrabold text-slate-800">{{ $data['totalJadwal'] }}</div>
            <div class="text-xs text-slate-500 mt-2">
                Slot jadwal teralokasikan dalam sepekan
            </div>
        </div>

        <!-- Absensi -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Absensi</span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            </div>
            <div class="text-3xl font-extrabold text-slate-800">{{ $data['totalAbsensi'] }}</div>
            <div class="text-xs text-slate-500 mt-2">
                Log rekam jejak kehadiran mengajar
            </div>
        </div>
    </div>

    <!-- Financial Performance Overview -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
        <h4 class="text-base font-bold text-slate-800 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Metrik Finansial & Arus Kas Honorarium
        </h4>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/70">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">Total Akumulasi Tagihan Honor</span>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-2">
                    Rp {{ number_format($data['totalHonor'], 0, ',', '.') }}
                </div>
                <p class="text-xs text-slate-500">Total nilai seluruh beban kerja honorarium tutor yang dihitung sistem.</p>
            </div>

            <div class="p-6 rounded-2xl bg-emerald-50/70 border border-emerald-200/70">
                <span class="text-xs font-semibold text-emerald-800 uppercase tracking-wider block mb-1">Total Realisasi Transfer Berhasil</span>
                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-700 mb-2">
                    Rp {{ number_format($data['totalPembayaran'], 0, ',', '.') }}
                </div>
                <p class="text-xs text-emerald-600">Total dana yang telah berhasil dicairkan dan diverifikasi transfernya.</p>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-400 gap-2">
            <div>Sistem Operasional: <strong>Houka GO v1.0 (Laravel 11)</strong></div>
            <div>Dicetak secara otomatis pada: {{ now()->isoFormat('D MMMM Y, HH:mm') }} WIB</div>
        </div>
    </div>

</div>
@endsection
