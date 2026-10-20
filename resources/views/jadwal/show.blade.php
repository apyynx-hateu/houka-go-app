@extends('layouts.app')

@section('title', 'Detail Jadwal Mengajar')
@section('page_description', 'Rincian alokasi sesi mengajar dan riwayat absensi')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <a href="{{ route('jadwal.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            &larr; Kembali ke Daftar Jadwal
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('jadwal.edit', $jadwal->id_jadwal) }}" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Ubah Jadwal
            </a>
            <a href="{{ route('absensi.create') }}?tutor={{ $jadwal->id_tutor }}&jadwal={{ $jadwal->id_jadwal }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Catat Absensi Sesi Ini
            </a>
        </div>
    </div>

    <!-- Overview Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-brand-600 flex items-center justify-center font-extrabold text-base">
                    {{ substr($jadwal->hari, 0, 3) }}
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800">{{ $jadwal->hari }}, {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }} WIB</h3>
                    <p class="text-xs text-slate-500">
                        {{ $jadwal->tanggal ? 'Tanggal: ' . \Carbon\Carbon::parse($jadwal->tanggal)->isoFormat('D MMMM Y') : 'Jadwal Rutin Mingguan' }}
                    </p>
                </div>
            </div>
            <div class="text-right text-xs text-slate-400">
                ID Jadwal: <span class="font-mono text-slate-600">#{{ str_pad($jadwal->id_jadwal, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-6 border-t border-slate-100 text-xs">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-slate-400 block mb-1">Tutor Pengampu:</span>
                <a href="{{ route('tutor.show', $jadwal->id_tutor) }}" class="font-bold text-slate-800 hover:text-brand-600 text-sm">
                    {{ $jadwal->tutor->nama ?? '-' }}
                </a>
                <div class="text-[11px] text-slate-500 mt-0.5">Kontak: {{ $jadwal->tutor->no_telepon ?? '-' }} | {{ $jadwal->tutor->email ?? '-' }}</div>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-slate-400 block mb-1">Lokasi Sekolah:</span>
                <a href="{{ route('sekolah.show', $jadwal->id_sekolah) }}" class="font-bold text-slate-800 hover:text-brand-600 text-sm">
                    {{ $jadwal->sekolah->nama_sekolah ?? '-' }}
                </a>
                <div class="text-[11px] text-slate-500 mt-0.5">{{ $jadwal->sekolah->ruangan ?? 'Ruang kelas' }} (Kapasitas: {{ $jadwal->sekolah->kapasitas ?? '-' }} siswa)</div>
            </div>
        </div>
    </div>

    <!-- Riwayat Absensi Jadwal Ini -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
        <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Catatan Kehadiran Pada Jadwal Ini ({{ $jadwal->absensi->count() }})
        </h4>

        @if($jadwal->absensi->isEmpty())
            <p class="text-xs text-slate-400 py-6 text-center">Belum ada catatan absensi untuk jadwal ini.</p>
        @else
            <div class="divide-y divide-slate-100 text-xs">
                @foreach($jadwal->absensi->sortByDesc('tanggal') as $abs)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($abs->tanggal)->isoFormat('dddd, D MMMM Y') }}</span>
                            @if($abs->keterangan)
                                <div class="text-[11px] text-slate-400">{{ $abs->keterangan }}</div>
                            @endif
                        </div>
                        <div>
                            @if($abs->status === 'Hadir')
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Hadir</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">Tidak Hadir</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
