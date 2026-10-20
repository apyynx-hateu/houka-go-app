@extends('layouts.app')

@section('title', 'Penilaian Kinerja Tutor')
@section('page_description', 'Evaluasi kualitas mengajar dan rekap catatan performa kerja tutor')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Daftar Penilaian Kinerja</h3>
            <p class="text-xs text-slate-500">Total data: {{ $penilaians->total() }} evaluasi tercatat</p>
        </div>
        <a href="{{ route('penilaian.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-md shadow-brand-600/20 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Beri Penilaian Baru
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('penilaian.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-6">
                <select name="tutor" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">Semua Tutor</option>
                    @foreach($tutors as $t)
                        <option value="{{ $t->id_tutor }}" {{ request('tutor') == $t->id_tutor ? 'selected' : '' }}>{{ $t->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-4">
                <input type="text" name="periode" value="{{ request('periode') }}" placeholder="Cari periode (cth: Semester Ganjil 2024)..."
                    class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['tutor', 'periode']))
                    <a href="{{ route('penilaian.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-colors">
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
                        <th class="px-5 py-3.5">Tanggal & Periode</th>
                        <th class="px-5 py-3.5">Tutor Dinilai</th>
                        <th class="px-5 py-3.5">Kualitas Mengajar</th>
                        <th class="px-5 py-3.5">Catatan Evaluasi</th>
                        <th class="px-5 py-3.5">Penilai (Petinggi)</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($penilaians as $pen)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($pen->tanggal_penilaian)->isoFormat('D MMM Y') }}</div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600 mt-0.5">
                                    {{ $pen->periode ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('tutor.show', $pen->id_tutor) }}" class="font-bold text-slate-800 hover:text-brand-600 transition-colors">
                                    {{ $pen->tutor->nama ?? 'Tutor tidak ditemukan' }}
                                </a>
                            </td>
                            <td class="px-5 py-4 text-slate-700 max-w-xs truncate font-medium">
                                {{ $pen->kualitas_mengajar ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-slate-500 max-w-xs truncate">
                                {{ $pen->catatan_kinerja ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                <div class="font-semibold text-slate-700">{{ $pen->penilai->nama ?? 'Petinggi' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $pen->penilai->jabatan ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('penilaian.show', $pen->id_penilaian) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-brand-600 hover:bg-brand-50 transition-colors" title="Lihat Rapor">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('penilaian.edit', $pen->id_penilaian) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Ubah Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('penilaian.destroy', $pen->id_penilaian) }}" method="POST" class="inline" onsubmit="return confirm('Hapus penilaian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                                Belum ada catatan penilaian kinerja.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($penilaians->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $penilaians->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
