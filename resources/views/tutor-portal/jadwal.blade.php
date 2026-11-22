@extends('layouts.tutor')

@section('title', 'Jadwal Mengajar Saya')
@section('page_description', 'Penugasan kelas dan jadwal pembelajaran mingguan Anda')

@section('content')
<div class="space-y-6">

    <!-- Filters & Summary Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Daftar Jadwal Mengajar</h3>
            <p class="text-xs text-slate-500">Total: {{ $jadwals->count() }} sesi kelas yang ditugaskan</p>
        </div>

        <form method="GET" action="{{ route('tutor.schedules') }}" class="flex items-center gap-2">
            <select name="hari" class="px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                <option value="">Semua Hari</option>
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                    <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition-colors">
                Filter
            </button>
            @if(request('hari'))
                <a href="{{ route('tutor.schedules') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Schedule Grid -->
    @if($jadwals->isEmpty())
        <div class="p-12 text-center bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-800 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h4 class="font-bold text-slate-700 text-sm">Tidak ada jadwal ditemukan</h4>
            <p class="text-xs text-slate-400 mt-1">Anda belum memiliki penugasan jadwal untuk filter yang dipilih.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($jadwals as $jadwal)
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md hover:border-brand-300 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-800 border border-brand-900">
                                {{ $jadwal->hari }}
                            </span>
                            <span class="text-xs font-mono font-semibold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">
                                {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }} WIB
                            </span>
                        </div>

                        <h4 class="font-bold text-base text-slate-800 leading-snug mb-1">
                            {{ $jadwal->sekolah->nama_sekolah ?? 'Sekolah Mitra' }}
                        </h4>
                        <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                            {{ $jadwal->sekolah->alamat ?? '-' }}
                        </p>

                        <div class="space-y-1.5 pt-3 border-t border-slate-100 text-xs text-slate-600">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Ruangan:</span>
                                <span class="font-semibold text-slate-700">{{ $jadwal->sekolah->ruangan ?? 'Kelas Reguler' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Kapasitas:</span>
                                <span class="font-semibold text-slate-700">{{ $jadwal->sekolah->kapasitas ?? '-' }} siswa</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-t border-slate-100">
                        <a href="{{ route('tutor.attendance') }}?jadwal={{ $jadwal->id_jadwal }}" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-brand-900 hover:bg-brand-500 hover:text-brand-900 text-brand-50 text-xs font-semibold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Check-in Presensi Sesi Ini
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
