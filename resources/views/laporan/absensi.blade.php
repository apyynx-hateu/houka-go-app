@extends('layouts.app')

@section('title', 'Laporan Rekapitulasi Absensi')
@section('page_description', 'Laporan kehadiran tutor, rasio kehadiran, dan ringkasan aktivitas mengajar')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Rekapitulasi Kehadiran Mengajar</h3>
            <p class="text-xs text-slate-500">Monitoring kedisiplinan dan total sesi hadir di seluruh sekolah</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Absensi Tercatat</div>
            <div class="text-2xl font-extrabold text-slate-800">{{ $ringkasan['total'] }} Sesi</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">Kehadiran (Hadir)</div>
            <div class="text-2xl font-extrabold text-emerald-600">
                {{ $ringkasan['hadir'] }} Sesi
                <span class="text-xs font-medium text-slate-400">
                    ({{ $ringkasan['total'] > 0 ? round(($ringkasan['hadir'] / $ringkasan['total']) * 100, 1) : 0 }}%)
                </span>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-red-500 uppercase tracking-wider mb-1">Ketidakhadiran (Absen)</div>
            <div class="text-2xl font-extrabold text-red-600">
                {{ $ringkasan['tidak_hadir'] }} Sesi
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm print:hidden">
        <form method="GET" action="{{ route('laporan.absensi') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-4">
                <select name="tutor" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">Semua Tutor</option>
                    @foreach($tutors as $t)
                        <option value="{{ $t->id_tutor }}" {{ request('tutor') == $t->id_tutor ? 'selected' : '' }}>{{ $t->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="status" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">Semua Status</option>
                    <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="Tidak Hadir" {{ request('status') == 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <input type="date" name="dari" value="{{ request('dari') }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" placeholder="Dari">
            </div>

            <div class="sm:col-span-2">
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" placeholder="Sampai">
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['tutor', 'status', 'dari', 'sampai']))
                    <a href="{{ route('laporan.absensi') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Nama Tutor</th>
                        <th class="px-5 py-3.5">Sekolah Mitra & Jam</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($absensis as $abs)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-semibold text-slate-800">
                                {{ \Carbon\Carbon::parse($abs->tanggal)->isoFormat('dddd, D MMM Y') }}
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ $abs->tutor->nama ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                @if($abs->jadwal)
                                    <div>{{ $abs->jadwal->sekolah->nama_sekolah ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ substr($abs->jadwal->jam_mulai, 0, 5) }} - {{ substr($abs->jadwal->jam_selesai, 0, 5) }} WIB</div>
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($abs->status === 'Hadir')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Hadir
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                        Tidak Hadir
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-600 max-w-xs truncate">
                                {{ $abs->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                                Belum ada data absensi yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($absensis->hasPages())
            <div class="p-4 border-t border-slate-200 print:hidden">
                {{ $absensis->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
