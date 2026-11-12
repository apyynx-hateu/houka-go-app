@extends('layouts.app')

@section('title', 'Detail Catatan Absensi')
@section('page_description', 'Rincian kehadiran tutor dan informasi kelas')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('absensi.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            &larr; Kembali ke Daftar Absensi
        </a>
        <a href="{{ route('absensi.edit', $absensi->id_absensi) }}" class="px-3.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold shadow-sm transition-colors">
            Ubah Data
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <span class="text-xs text-slate-400 block mb-1">Tanggal Absensi</span>
                <h3 class="text-lg font-bold text-slate-800">{{ \Carbon\Carbon::parse($absensi->tanggal)->isoFormat('dddd, D MMMM Y') }}</h3>
            </div>
            <div>
                @if($absensi->status === 'Hadir')
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Hadir
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        Tidak Hadir
                    </span>
                @if($absensi->waktu_check_in)
                    <div class="text-[11px] text-slate-500 mt-1 font-mono">
                        Check-in: {{ substr($absensi->waktu_check_in, 0, 5) }} WIB
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-4 text-xs">
            <div>
                <span class="text-slate-400 block mb-0.5">Tutor:</span>
                <a href="{{ route('tutor.show', $absensi->id_tutor) }}" class="font-bold text-sm text-brand-600 hover:underline">
                    {{ $absensi->tutor->nama ?? '-' }}
                </a>
                <span class="text-slate-500 block">{{ $absensi->tutor->email ?? '-' }} &bull; {{ $absensi->tutor->no_telepon ?? '-' }}</span>
            </div>

            @if($absensi->jadwal)
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                    <span class="text-slate-400 block font-medium">Jadwal Sesi Tertaut:</span>
                    <div class="font-bold text-slate-800">{{ $absensi->jadwal->sekolah->nama_sekolah ?? '-' }}</div>
                    <div class="text-slate-500">Hari {{ $absensi->jadwal->hari }}, {{ substr($absensi->jadwal->jam_mulai, 0, 5) }} - {{ substr($absensi->jadwal->jam_selesai, 0, 5) }} WIB</div>
                    <div class="text-slate-400 text-[11px]">{{ $absensi->jadwal->sekolah->ruangan ?? 'Ruang kelas' }}</div>
                </div>
            @endif

            <div>
                <span class="text-slate-400 block mb-0.5">Keterangan / Materi:</span>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-700">
                    {{ $absensi->keterangan ?? 'Tidak ada keterangan tertulis.' }}
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 text-slate-400 text-[11px]">
                Dicatat sistem: {{ $absensi->created_at ? $absensi->created_at->isoFormat('D MMMM Y, HH:mm') : '-' }} WIB
            </div>
        </div>
    </div>

</div>
@endsection
