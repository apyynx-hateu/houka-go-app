@extends('layouts.tutor')

@section('title', 'Dashboard Tutor')
@section('page_description', 'Selamat datang di ruang kerja pengajar bimbel Houka GO')

@section('content')
<div class="space-y-8">

    <!-- Welcome Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-brand-900 p-6 sm:p-8 text-white">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-500/30 text-brand-200 border border-brand-400/30 mb-3">
                Pengajar Aktif Houka GO
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-2">
                Halo, {{ $tutor->nama }}!
            </h1>
            <p class="text-slate-300 text-sm leading-relaxed">
                Semangat mengajar hari ini, terus berikan yang terbaik!
            </p>
        </div>
        <div class="absolute -right-8 -bottom-10 w-64 h-64 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Quick Stats Grid -->
    <div>
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Statistik Mengajar & Honor</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Stat 1: Jadwal Mengajar -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jadwal Ditugaskan</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-slate-800">{{ $totalJadwal }} <span class="text-xs font-medium text-slate-400">Sesi Rutin</span></div>
                <div class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-purple-500"></span>
                    Kelas mingguan aktif
                </div>
            </div>

            <!-- Stat 2: Hadir Bulan Ini -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Presensi Hadir (Bulan Ini)</span>
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-brand-800">{{ $absensiBulanIni }} <span class="text-xs font-medium text-slate-400">Sesi</span></div>
                <div class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    Tervalidasi untuk honor
                </div>
            </div>

            <!-- Stat 3: Honor Pending -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Honor Dalam Proses</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-xl font-extrabold text-amber-700">Rp {{ number_format($honorPending, 0, ',', '.') }}</div>
                <div class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-amber-400"></span>
                    Menunggu verifikasi admin
                </div>
            </div>

            <!-- Stat 4: Honor Lunas -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Honor Telah Dibayar</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>
                <div class="text-xl font-extrabold text-emerald-600">Rp {{ number_format($honorDibayar, 0, ',', '.') }}</div>
                <div class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    Total pencairan sukses
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid: Jadwal Mengajar & Presensi Mandiri -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Jadwal Mengajar Saya -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Penugasan Jadwal Mengajar</h3>
                        <p class="text-xs text-slate-400">Daftar kelas dan sekolah mitra yang ditugaskan kepada Anda</p>
                    </div>
                    <a href="{{ route('tutor.schedules') }}" class="text-xs font-semibold text-brand-800 hover:text-brand-700">Lihat Semua &rarr;</a>
                </div>

                @if($jadwalList->isEmpty())
                    <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-slate-400 text-xs">
                        Belum ada penugasan jadwal aktif untuk akun Anda. Hubungi bagian operasional atau admin.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($jadwalList as $jdw)
                            <div class="p-4 rounded-xl border border-slate-100 hover:border-brand-200 hover:bg-brand-50/20 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-800 font-bold flex flex-col items-center justify-center flex-shrink-0 text-center leading-none">
                                        <span class="text-[10px] font-semibold uppercase text-brand-800">Hari</span>
                                        <span class="text-sm font-extrabold mt-0.5">{{ $jdw->hari }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800 text-sm">{{ $jdw->sekolah->nama_sekolah ?? 'Sekolah Mitra' }}</h4>
                                        <p class="text-xs text-slate-500 flex items-center gap-2 mt-0.5">
                                            <span>Ruang: {{ $jdw->sekolah->ruangan ?? '-' }}</span>
                                            <span>&bull;</span>
                                            <span>Kapasitas: {{ $jdw->sekolah->kapasitas ?? '-' }} siswa</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="sm:text-right flex items-center sm:flex-col justify-between">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                                        {{ substr($jdw->jam_mulai, 0, 5) }} - {{ substr($jdw->jam_selesai, 0, 5) }} WIB
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Riwayat Absensi Terakhir -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Riwayat Presensi Terbaru</h3>
                        <p class="text-xs text-slate-400">Catatan kehadiran mengajar Anda yang telah tersimpan</p>
                    </div>
                    <a href="{{ route('tutor.attendance') }}" class="text-xs font-semibold text-brand-800 hover:text-brand-700">Buka Presensi &rarr;</a>
                </div>

                @if($riwayatAbsensi->isEmpty())
                    <p class="p-6 text-center text-xs text-slate-400 bg-slate-50 rounded-xl">Belum ada riwayat absensi tercatat.</p>
                @else
                    <div class="divide-y divide-slate-100 text-xs">
                        @foreach($riwayatAbsensi as $abs)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <span class="font-bold text-slate-800 block">{{ \Carbon\Carbon::parse($abs->tanggal)->isoFormat('dddd, D MMMM Y') }}</span>
                                    <span class="text-slate-500 text-[11px]">
                                        {{ $abs->jadwal->sekolah->nama_sekolah ?? 'Tanpa jadwal' }}
                                        @if($abs->waktu_check_in)
                                            &bull; Check-in pukul {{ substr($abs->waktu_check_in, 0, 5) }} WIB
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    @if($abs->status === 'Hadir')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Hadir
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            Tidak Hadir
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right 1 Col: Quick Check-in & Evaluation -->
        <div class="space-y-6">

            <!-- Check-in Cepat Form -->
            <div class="bg-gradient-to-br from-brand-50 to-white rounded-2xl border border-brand-200 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-brand-800 text-white flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Presensi Check-in Cepat</h4>
                        <p class="text-[11px] text-slate-500">Catat kehadiran kelas hari ini</p>
                    </div>
                </div>

                <form action="{{ route('tutor.attendance.checkin') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label for="checkin_jadwal" class="block text-xs font-semibold text-slate-700 mb-1">Pilih Sesi Jadwal</label>
                        <select id="checkin_jadwal" name="id_jadwal" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                            <option value="">-- Pilih Jadwal Anda --</option>
                            @foreach($jadwalList as $j)
                                <option value="{{ $j->id_jadwal }}">
                                    {{ $j->hari }} ({{ substr($j->jam_mulai, 0, 5) }}-{{ substr($j->jam_selesai, 0, 5) }}) - {{ $j->sekolah->nama_sekolah ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="checkin_tanggal" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Presensi</label>
                        <input type="date" id="checkin_tanggal" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    </div>

                    <div>
                        <label for="checkin_keterangan" class="block text-xs font-semibold text-slate-700 mb-1">Materi / Catatan</label>
                        <input type="text" id="checkin_keterangan" name="keterangan" placeholder="Contoh: Mengajar materi Bab 1..." class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    </div>

                    <button type="submit" class="w-full mt-2 py-2.5 px-4 rounded-xl bg-brand-800 hover:bg-brand-700 text-white font-semibold text-xs shadow-md shadow-brand-600/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Konfirmasi Check-in Sekarang
                    </button>
                </form>
            </div>

            <!-- Penilaian Kinerja dari Petinggi -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Evaluasi Kinerja</h4>
                        <p class="text-[11px] text-slate-400">Penilaian dari tim manajemen</p>
                    </div>
                </div>

                @if($penilaianTerbaru)
                    <div class="space-y-3 text-xs">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 block text-[10px] uppercase font-semibold">Periode: {{ $penilaianTerbaru->periode ?? '-' }}</span>
                            <div class="font-bold text-slate-800 mt-1">Penilai: {{ $penilaianTerbaru->penilai->nama ?? 'Admin' }}</div>
                            <div class="text-[11px] text-slate-500">{{ \Carbon\Carbon::parse($penilaianTerbaru->tanggal_penilaian)->isoFormat('D MMMM Y') }}</div>
                        </div>

                        <div>
                            <span class="text-slate-400 block font-semibold mb-0.5">Kualitas Mengajar:</span>
                            <p class="text-slate-700 bg-brand-50/40 p-2.5 rounded-lg border border-brand-100/60 leading-relaxed">
                                {{ $penilaianTerbaru->kualitas_mengajar ?? 'Belum ada catatan' }}
                            </p>
                        </div>

                        @if($penilaianTerbaru->catatan_kinerja)
                            <div>
                                <span class="text-slate-400 block font-semibold mb-0.5">Catatan Disiplin / Kinerja:</span>
                                <p class="text-slate-700 bg-slate-50 p-2.5 rounded-lg border border-slate-100 leading-relaxed">
                                    {{ $penilaianTerbaru->catatan_kinerja }}
                                </p>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-xs text-slate-400 py-4 text-center bg-slate-50 rounded-xl">Belum ada catatan evaluasi kinerja untuk Anda.</p>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
