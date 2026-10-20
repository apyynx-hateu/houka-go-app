@extends('layouts.app')

@section('title', 'Detail Sekolah: ' . $sekolah->nama_sekolah)
@section('page_description', 'Informasi fasilitas ruangan dan jadwal mengajar yang dialokasikan')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <a href="{{ route('sekolah.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            &larr; Kembali ke Daftar Sekolah
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('sekolah.edit', $sekolah->id_sekolah) }}" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Ubah Data
            </a>
            <a href="{{ route('jadwal.create') }}?sekolah={{ $sekolah->id_sekolah }}" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                + Jadwalkan di Sekolah Ini
            </a>
        </div>
    </div>

    <!-- Overview Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="text-xl font-bold text-slate-800">{{ $sekolah->nama_sekolah }}</h3>
                    @if($sekolah->status_aktif === 'Aktif')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Aktif
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                            Tidak Aktif
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500">{{ $sekolah->alamat ?? 'Alamat belum diatur' }}</p>
            </div>
            <div class="text-left sm:text-right text-xs text-slate-500">
                <div>Ruangan: <strong class="text-slate-700">{{ $sekolah->ruangan ?? '-' }}</strong></div>
                <div>Kapasitas: <strong class="text-slate-700">{{ $sekolah->kapasitas ? $sekolah->kapasitas . ' Siswa' : '-' }}</strong></div>
            </div>
        </div>
    </div>

    <!-- Jadwal Mengajar di Sekolah ini -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Jadwal Mengajar di Lokasi Ini ({{ $sekolah->jadwal->count() }})
            </h4>
        </div>

        @if($sekolah->jadwal->isEmpty())
            <p class="text-xs text-slate-400 py-6 text-center">Belum ada jadwal mengajar di sekolah ini.</p>
        @else
            <div class="divide-y divide-slate-100 text-xs">
                @foreach($sekolah->jadwal as $jdw)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-800">{{ $jdw->hari }}</span>
                            <span class="text-slate-500 ml-1">({{ substr($jdw->jam_mulai, 0, 5) }} - {{ substr($jdw->jam_selesai, 0, 5) }})</span>
                            <div class="text-[11px] text-brand-600 font-medium mt-0.5">Tutor: {{ $jdw->tutor->nama ?? '-' }}</div>
                        </div>
                        <a href="{{ route('jadwal.show', $jdw->id_jadwal) }}" class="text-slate-400 hover:text-brand-600 text-[11px] font-medium">Detail &rarr;</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
