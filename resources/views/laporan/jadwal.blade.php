@extends('layouts.app')

@section('title', 'Laporan Rekapitulasi Jadwal')
@section('page_description', 'Laporan alokasi hari, jam mengajar, dan distribusi kelas di seluruh sekolah mitra')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Master Distribusi Jadwal Mengajar</h3>
            <p class="text-xs text-slate-500">Rekapitulasi jadwal mingguan yang aktif berjalan</p>
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
        <form method="GET" action="{{ route('laporan.jadwal') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-4">
                <select name="tutor" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">Semua Tutor</option>
                    @foreach($tutors as $t)
                        <option value="{{ $t->id_tutor }}" {{ request('tutor') == $t->id_tutor ? 'selected' : '' }}>{{ $t->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-4">
                <select name="sekolah" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">Semua Sekolah</option>
                    @foreach($sekolahs as $s)
                        <option value="{{ $s->id_sekolah }}" {{ request('sekolah') == $s->id_sekolah ? 'selected' : '' }}>{{ $s->nama_sekolah }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="hari" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">Semua Hari</option>
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                        <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['tutor', 'sekolah', 'hari']))
                    <a href="{{ route('laporan.jadwal') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-colors">
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
                        <th class="px-5 py-3.5">Hari & Jam</th>
                        <th class="px-5 py-3.5">Tutor Pengampu</th>
                        <th class="px-5 py-3.5">Sekolah Mitra & Ruangan</th>
                        <th class="px-5 py-3.5">Kapasitas</th>
                        <th class="px-5 py-3.5">Jadwal Spesifik / Rutin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($jadwals as $jdw)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-800 block">{{ $jdw->hari }}</span>
                                <span class="text-slate-500 text-[11px]">{{ substr($jdw->jam_mulai, 0, 5) }} - {{ substr($jdw->jam_selesai, 0, 5) }} WIB</span>
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ $jdw->tutor->nama ?? '-' }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-800">{{ $jdw->sekolah->nama_sekolah ?? '-' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $jdw->sekolah->ruangan ?? 'Ruang kelas' }}</div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                {{ $jdw->sekolah->kapasitas ? $jdw->sekolah->kapasitas . ' Siswa' : '-' }}
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                {{ $jdw->tanggal ? \Carbon\Carbon::parse($jdw->tanggal)->isoFormat('D MMM Y') : 'Rutin Tiap Minggu' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                                Belum ada data jadwal mengajar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jadwals->hasPages())
            <div class="p-4 border-t border-slate-200 print:hidden">
                {{ $jadwals->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
