@extends('layouts.app')

@section('title', 'Laporan Rekapitulasi Tutor')
@section('page_description', 'Laporan komprehensif data tutor, beban jadwal, keaktifan absensi, dan riwayat honor')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Rekapitulasi SDM Tutor</h3>
            <p class="text-xs text-slate-500">Menampilkan statistik aktivitas tiap tutor di seluruh sekolah mitra</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm print:hidden">
        <form method="GET" action="{{ route('laporan.tutor') }}" class="flex items-center gap-3">
            <div class="w-64">
                <select name="status" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">Semua Status Kepegawaian</option>
                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition-colors">
                Terapkan Filter
            </button>
            @if(request('status'))
                <a href="{{ route('laporan.tutor') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Nama Tutor</th>
                        <th class="px-5 py-3.5">Kontak</th>
                        <th class="px-5 py-3.5">Pendidikan Terakhir</th>
                        <th class="px-5 py-3.5 text-center">Beban Jadwal</th>
                        <th class="px-5 py-3.5 text-center">Total Absensi</th>
                        <th class="px-5 py-3.5 text-center">Perhitungan Honor</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tutors as $tutor)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ $tutor->nama }}
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                <div>{{ $tutor->email ?? '-' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $tutor->no_telepon ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $tutor->pendidikan_terakhir ?? '-' }}</td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-brand-700 font-semibold">
                                    {{ $tutor->jadwal_count }} Sesi
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-semibold">
                                    {{ $tutor->absensi_count }} Hadir/Absen
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">
                                <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 font-semibold">
                                    {{ $tutor->honor_count }} Periode
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if($tutor->status_kerja === 'Aktif')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                                Tidak ada data tutor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tutors->hasPages())
            <div class="p-4 border-t border-slate-200 print:hidden">
                {{ $tutors->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
