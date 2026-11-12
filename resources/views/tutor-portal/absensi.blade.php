@extends('layouts.tutor')

@section('title', 'Presensi & Kehadiran Mengajar')
@section('page_description', 'Lakukan check-in kehadiran mengajar dan pantau riwayat presensi Anda')

@section('content')
<div class="space-y-6">

    <!-- Section 1: Check-in Presensi Form Card -->
    <div class="bg-gradient-to-br from-teal-50 via-white to-white rounded-2xl border border-teal-200/80 p-6 sm:p-8 shadow-sm">
        <div class="max-w-2xl">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center font-bold shadow-md shadow-teal-600/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Presensi Mandiri Tutor (Check-in)</h3>
                    <p class="text-xs text-slate-500">Pilih sesi penugasan Anda untuk mencatat kehadiran 'Hadir' mengajar</p>
                </div>
            </div>

            <form action="{{ route('tutor.attendance.checkin') }}" method="POST" class="mt-5 space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="id_jadwal" class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Jadwal Mengajar <span class="text-red-500">*</span></label>
                        <select id="id_jadwal" name="id_jadwal" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 bg-white">
                            <option value="">-- Pilih Sesi Mengajar Anda --</option>
                            @foreach($myJadwals as $j)
                                <option value="{{ $j->id_jadwal }}" {{ old('id_jadwal', request('jadwal')) == $j->id_jadwal ? 'selected' : '' }}>
                                    Hari {{ $j->hari }} ({{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }} WIB) &bull; {{ $j->sekolah->nama_sekolah ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-[10px] text-slate-400 mt-1 block">Hanya jadwal yang ditugaskan kepada akun Anda yang dapat dipilih.</span>
                    </div>

                    <div>
                        <label for="tanggal" class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Mengajar <span class="text-red-500">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 bg-white">
                    </div>

                    <div>
                        <label for="keterangan" class="block text-xs font-semibold text-slate-700 mb-1.5">Materi / Keterangan Pembelajaran</label>
                        <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan') }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 bg-white"
                            placeholder="Contoh: Mengajar Bab 2, latihan soal mandiri...">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-[11px] text-slate-400">
                        Waktu check-in akan otomatis tercatat berdasarkan jam submit presensi.
                    </span>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs shadow-md shadow-teal-600/20 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Presensi (Hadir)
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Section 2: Attendance History & Filters -->
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Riwayat Kehadiran Anda</h3>
                <p class="text-xs text-slate-500">Daftar presensi yang tercatat pada sistem Houka GO</p>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('tutor.attendance') }}" class="flex items-center gap-2">
                <input type="month" name="bulan" value="{{ request('bulan') }}" class="px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 bg-white">

                <select name="status" class="px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 bg-white">
                    <option value="">Semua Status</option>
                    <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="Tidak Hadir" {{ request('status') == 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                </select>

                <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['bulan', 'status']))
                    <a href="{{ route('tutor.attendance') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Sekolah / Sesi</th>
                            <th class="px-5 py-3.5">Waktu Check-in</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Materi / Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($absensis as $abs)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-4 font-bold text-slate-800">
                                    {{ \Carbon\Carbon::parse($abs->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    @if($abs->jadwal)
                                        <div class="font-semibold text-slate-800">{{ $abs->jadwal->sekolah->nama_sekolah ?? '-' }}</div>
                                        <div class="text-[11px] text-slate-400">Hari {{ $abs->jadwal->hari }} ({{ substr($abs->jadwal->jam_mulai, 0, 5) }} - {{ substr($abs->jadwal->jam_selesai, 0, 5) }} WIB)</div>
                                    @else
                                        <span class="text-slate-400 italic">Presensi di luar jadwal rutin</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-mono text-[11px] text-slate-600">
                                    {{ $abs->waktu_check_in ? substr($abs->waktu_check_in, 0, 5) . ' WIB' : '-' }}
                                </td>
                                <td class="px-5 py-4">
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
                                </td>
                                <td class="px-5 py-4 text-slate-600 max-w-sm">
                                    {{ $abs->keterangan ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                                    Belum ada catatan presensi yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($absensis->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $absensis->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
