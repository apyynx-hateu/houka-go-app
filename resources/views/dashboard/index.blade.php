@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_description', 'Ringkasan operasional dan metrik kinerja Houka GO')

@section('content')
<div class="space-y-8">

    <!-- Welcome Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-brand-900 via-indigo-900 to-slate-900 p-6 sm:p-8 text-white shadow-xl shadow-brand-900/10">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-500/30 text-brand-200 border border-brand-400/30 mb-3">
                Selamat Datang, {{ Auth::user()->jabatan }}
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-2">
                Halo, {{ Auth::user()->nama }}! 👋
            </h1>
            <p class="text-slate-300 text-sm leading-relaxed">
                Berikut adalah rekapitulasi data operasional bimbel Houka GO hari ini. Kelola tutor, pantau kehadiran jadwal mengajar, dan verifikasi pencairan honor secara terintegrasi.
            </p>
        </div>
        <div class="absolute -right-8 -bottom-10 w-64 h-64 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Quick Stats Grid -->
    <div>
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Statistik Master & Operasional</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Stat 1: Tutor -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tutor Bimbel</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-slate-800">{{ $totalTutorAktif }} <span class="text-xs font-medium text-slate-400">Aktif</span></div>
                <div class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-slate-300"></span>
                    {{ $totalTutorTidakAktif }} tidak aktif
                </div>
            </div>

            <!-- Stat 2: Sekolah -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sekolah Mitra</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-slate-800">{{ $totalSekolah }}</div>
                <div class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    Semua sekolah aktif
                </div>
            </div>

            <!-- Stat 3: Jadwal -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sesi Mengajar</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-slate-800">{{ $totalJadwal }}</div>
                <div class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-purple-500"></span>
                    Jadwal aktif terdaftar
                </div>
            </div>

            <!-- Stat 4: Absensi Bulan Ini -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kehadiran (Bulan Ini)</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-emerald-600">{{ $totalAbsensiHadir }} <span class="text-xs font-medium text-slate-400">Hadir</span></div>
                <div class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-red-400"></span>
                    {{ $totalAbsensiTidakHadir }} tidak hadir
                </div>
            </div>
        </div>
    </div>

    <!-- Financial & Workflow Overview Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Card: Status Honor Tutor -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Ringkasan Honor Tutor</h3>
                    <p class="text-xs text-slate-400">Status akumulasi honor mengajar tutor</p>
                </div>
                <a href="{{ route('honor.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">Lihat Semua &rarr;</a>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/60">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                        <div>
                            <div class="text-xs font-semibold text-slate-700">Belum Dibayar</div>
                            <div class="text-[11px] text-slate-500">Menunggu pemrosesan</div>
                        </div>
                    </div>
                    <div class="text-sm font-bold text-amber-700">Rp {{ number_format($honorBelumDibayar, 0, ',', '.') }}</div>
                </div>

                <div class="flex items-center justify-between p-3.5 rounded-xl bg-blue-50/70 border border-blue-200/60">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        <div>
                            <div class="text-xs font-semibold text-slate-700">Sedang Diproses</div>
                            <div class="text-[11px] text-slate-500">Dalam antrean verifikasi transfer</div>
                        </div>
                    </div>
                    <div class="text-sm font-bold text-blue-700">Rp {{ number_format($honorDiproses, 0, ',', '.') }}</div>
                </div>

                <div class="flex items-center justify-between p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200/60">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                        <div>
                            <div class="text-xs font-semibold text-slate-700">Telah Dibayar (Lunas)</div>
                            <div class="text-[11px] text-slate-500">Terselesaikan secara penuh</div>
                        </div>
                    </div>
                    <div class="text-sm font-bold text-emerald-700">Rp {{ number_format($honorDibayar, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Card: Status Pembayaran & Permintaan Laporan -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Pembayaran & Permintaan Laporan</h3>
                    <p class="text-xs text-slate-400">Monitoring transaksi dan tiket laporan</p>
                </div>
                <a href="{{ route('permintaan-laporan.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">Kelola &rarr;</a>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60">
                    <div class="text-xs text-slate-500 mb-1">Pembayaran Berhasil</div>
                    <div class="text-lg font-bold text-emerald-600">Rp {{ number_format($pembayaranBerhasil, 0, ',', '.') }}</div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60">
                    <div class="text-xs text-slate-500 mb-1">Pembayaran Pending</div>
                    <div class="text-lg font-bold text-amber-600">Rp {{ number_format($pembayaranPending, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-indigo-50/60 border border-indigo-100 flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-indigo-900">Tiket Permintaan Laporan</div>
                    <div class="text-[11px] text-indigo-600 mt-0.5">
                        {{ $laporanMenunggu }} Menunggu &bull; {{ $laporanDiproses }} Diproses
                    </div>
                </div>
                <a href="{{ route('permintaan-laporan.index') }}" class="px-3 py-1.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-medium transition-colors">
                    Buka Tiket
                </a>
            </div>
        </div>

    </div>

    <!-- Quick Action Navigation -->
    <div>
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="{{ route('tutor.create') }}" class="p-4 rounded-xl bg-white border border-slate-200 hover:border-brand-500 hover:shadow-sm text-center transition-all group">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <span class="text-xs font-semibold text-slate-700 group-hover:text-brand-600 block">+ Tambah Tutor</span>
            </a>

            <a href="{{ route('jadwal.create') }}" class="p-4 rounded-xl bg-white border border-slate-200 hover:border-brand-500 hover:shadow-sm text-center transition-all group">
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-xs font-semibold text-slate-700 group-hover:text-brand-600 block">+ Buat Jadwal</span>
            </a>

            <a href="{{ route('absensi.create') }}" class="p-4 rounded-xl bg-white border border-slate-200 hover:border-brand-500 hover:shadow-sm text-center transition-all group">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <span class="text-xs font-semibold text-slate-700 group-hover:text-brand-600 block">+ Input Absensi</span>
            </a>

            <a href="{{ route('honor.create') }}" class="p-4 rounded-xl bg-white border border-slate-200 hover:border-brand-500 hover:shadow-sm text-center transition-all group">
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-semibold text-slate-700 group-hover:text-brand-600 block">+ Hitung Honor</span>
            </a>

            <a href="{{ route('penilaian.create') }}" class="p-4 rounded-xl bg-white border border-slate-200 hover:border-brand-500 hover:shadow-sm text-center transition-all group">
                <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <span class="text-xs font-semibold text-slate-700 group-hover:text-brand-600 block">+ Beri Penilaian</span>
            </a>

            <a href="{{ route('permintaan-laporan.create') }}" class="p-4 rounded-xl bg-white border border-slate-200 hover:border-brand-500 hover:shadow-sm text-center transition-all group">
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span class="text-xs font-semibold text-slate-700 group-hover:text-brand-600 block">+ Minta Laporan</span>
            </a>
        </div>
    </div>

</div>
@endsection
