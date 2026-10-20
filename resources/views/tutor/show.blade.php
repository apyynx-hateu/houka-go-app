@extends('layouts.app')

@section('title', 'Profil Tutor: ' . $tutor->nama)
@section('page_description', 'Detail biodata, jadwal mengajar, riwayat kehadiran, dan evaluasi kinerja')

@section('content')
<div class="space-y-6">

    <!-- Top Action Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <a href="{{ route('tutor.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            &larr; Kembali ke Daftar Tutor
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('tutor.edit', $tutor->id_tutor) }}" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Ubah Data
            </a>
            <a href="{{ route('honor.create') }}?tutor={{ $tutor->id_tutor }}" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Hitung Honor
            </a>
        </div>
    </div>

    <!-- Header Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-500 text-white font-bold text-2xl flex items-center justify-center shadow-md shadow-brand-500/20">
                {{ substr($tutor->nama, 0, 1) }}
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h3 class="text-xl font-bold text-slate-800">{{ $tutor->nama }}</h3>
                    @if($tutor->status_kerja === 'Aktif')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Aktif
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                            Tidak Aktif
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500">Pendidikan: {{ $tutor->pendidikan_terakhir ?? 'Belum ditentukan' }}</p>
            </div>
            <div class="text-left sm:text-right text-xs text-slate-500 space-y-0.5">
                <div>Bergabung: <strong class="text-slate-700">{{ $tutor->tanggal_bergabung ? \Carbon\Carbon::parse($tutor->tanggal_bergabung)->isoFormat('D MMMM Y') : '-' }}</strong></div>
                <div>ID Tutor: <span class="font-mono text-slate-600">#{{ str_pad($tutor->id_tutor, 4, '0', STR_PAD_LEFT) }}</span></div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 block mb-0.5">Email</span>
                <span class="font-medium text-slate-800">{{ $tutor->email ?? '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Telepon / No. HP</span>
                <span class="font-medium text-slate-800">{{ $tutor->no_telepon ?? '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Jenis Kelamin / Lahir</span>
                <span class="font-medium text-slate-800">
                    {{ $tutor->jenis_kelamin ?? '-' }}
                    @if($tutor->tanggal_lahir)
                        ({{ \Carbon\Carbon::parse($tutor->tanggal_lahir)->age }} thn)
                    @endif
                </span>
            </div>
            <div class="sm:col-span-3">
                <span class="text-slate-400 block mb-0.5">Alamat</span>
                <span class="font-medium text-slate-800">{{ $tutor->alamat ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Related Data Tabs / Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Jadwal Mengajar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Jadwal Mengajar Terjadwal ({{ $tutor->jadwal->count() }})
                </h4>
                <a href="{{ route('jadwal.create') }}?tutor={{ $tutor->id_tutor }}" class="text-[11px] font-semibold text-brand-600 hover:text-brand-700">+ Jadwal</a>
            </div>

            @if($tutor->jadwal->isEmpty())
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada jadwal mengajar yang diatur untuk tutor ini.</p>
            @else
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($tutor->jadwal as $jdw)
                        <div class="py-2.5 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-800">{{ $jdw->hari }}</span>
                                <span class="text-slate-400 ml-1">({{ substr($jdw->jam_mulai, 0, 5) }} - {{ substr($jdw->jam_selesai, 0, 5) }})</span>
                                <div class="text-[11px] text-slate-500">{{ $jdw->sekolah->nama_sekolah ?? '-' }} &bull; {{ $jdw->sekolah->ruangan ?? 'Ruang kelas' }}</div>
                            </div>
                            <a href="{{ route('jadwal.show', $jdw->id_jadwal) }}" class="text-slate-400 hover:text-brand-600 text-[11px] font-medium">Detail &rarr;</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Riwayat Absensi -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Riwayat Kehadiran Terakhir
                </h4>
                <a href="{{ route('absensi.create') }}?tutor={{ $tutor->id_tutor }}" class="text-[11px] font-semibold text-brand-600 hover:text-brand-700">+ Absen</a>
            </div>

            @if($tutor->absensi->isEmpty())
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada riwayat absensi.</p>
            @else
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($tutor->absensi->sortByDesc('tanggal')->take(5) as $abs)
                        <div class="py-2.5 flex items-center justify-between">
                            <div>
                                <span class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($abs->tanggal)->isoFormat('D MMM Y') }}</span>
                                @if($abs->keterangan)
                                    <div class="text-[11px] text-slate-400">{{ $abs->keterangan }}</div>
                                @endif
                            </div>
                            <div>
                                @if($abs->status === 'Hadir')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Hadir</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-red-700 border border-red-200">Tidak Hadir</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Riwayat Penilaian Kinerja -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm sm:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Evaluasi & Catatan Kinerja
                </h4>
                <a href="{{ route('penilaian.create') }}?tutor={{ $tutor->id_tutor }}" class="text-[11px] font-semibold text-brand-600 hover:text-brand-700">+ Beri Penilaian</a>
            </div>

            @if($tutor->penilaian->isEmpty())
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada evaluasi kinerja untuk tutor ini.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($tutor->penilaian as $pen)
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 text-xs space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800">Periode: {{ $pen->periode ?? '-' }}</span>
                                <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($pen->tanggal_penilaian)->isoFormat('D MMM Y') }}</span>
                            </div>
                            <div class="text-slate-600">
                                <strong class="text-slate-700 block">Kualitas Mengajar:</strong>
                                {{ $pen->kualitas_mengajar ?? 'Tidak ada catatan khusus.' }}
                            </div>
                            <div class="text-slate-600">
                                <strong class="text-slate-700 block">Catatan Kinerja:</strong>
                                {{ $pen->catatan_kinerja ?? 'Tidak ada catatan.' }}
                            </div>
                            <div class="text-[10px] text-slate-400 pt-1 border-t border-slate-200">
                                Dinilai oleh: {{ $pen->penilai->nama ?? 'Petinggi' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
