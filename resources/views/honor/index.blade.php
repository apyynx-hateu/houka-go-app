@extends('layouts.app')

@section('title', 'Honor Tutor')
@section('page_description', 'Perhitungan honor mengajar tutor berdasarkan jam kehadiran dan tarif per jam')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Daftar Rekapitulasi Honor</h3>
            <p class="text-xs text-slate-500">Total data: {{ $honors->total() }} catatan perhitungan honor</p>
        </div>
        <a href="{{ route('honor.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-md shadow-brand-600/20 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Hitung Honor Baru
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('honor.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-4">
                <select name="tutor" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">Semua Tutor</option>
                    @foreach($tutors as $t)
                        <option value="{{ $t->id_tutor }}" {{ request('tutor') == $t->id_tutor ? 'selected' : '' }}>{{ $t->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <select name="status" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">Semua Status</option>
                    <option value="Belum Dibayar" {{ request('status') == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Dibayar" {{ request('status') == 'Dibayar' ? 'selected' : '' }}>Dibayar</option>
                </select>
            </div>

            <div class="sm:col-span-3">
                <input type="month" name="periode" value="{{ request('periode') }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['tutor', 'status', 'periode']))
                    <a href="{{ route('honor.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-colors">
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
                        <th class="px-5 py-3.5">Periode</th>
                        <th class="px-5 py-3.5">Tutor</th>
                        <th class="px-5 py-3.5">Tarif / Jam</th>
                        <th class="px-5 py-3.5">Total Jam</th>
                        <th class="px-5 py-3.5">Penyesuaian</th>
                        <th class="px-5 py-3.5">Total Honor</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($honors as $honor)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ \Carbon\Carbon::parse($honor->periode_bulan)->isoFormat('MMMM Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('tutor.show', $honor->id_tutor) }}" class="font-bold text-slate-800 hover:text-brand-600 transition-colors">
                                    {{ $honor->tutor->nama ?? 'Tutor tidak ditemukan' }}
                                </a>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                Rp {{ number_format($honor->tarif_per_jam, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-slate-700 font-medium">
                                {{ $honor->total_jam_mengajar }} Jam
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                @if($honor->penyesuaian_honor != 0)
                                    <span class="{{ $honor->penyesuaian_honor > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $honor->penyesuaian_honor > 0 ? '+' : '' }}Rp {{ number_format($honor->penyesuaian_honor, 0, ',', '.') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-5 py-4 font-extrabold text-slate-900 text-sm">
                                Rp {{ number_format($honor->total_honor, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4">
                                @if($honor->status === 'Dibayar')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Dibayar
                                    </span>
                                @elseif($honor->status === 'Diproses')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        Diproses
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Belum Dibayar
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('honor.show', $honor->id_honor) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-brand-600 hover:bg-brand-50 transition-colors" title="Lihat Detail & Slip">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    @if($honor->status !== 'Dibayar')
                                        <a href="{{ route('honor.edit', $honor->id_honor) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Ubah Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('honor.destroy', $honor->id_honor) }}" method="POST" class="inline" onsubmit="return confirm('Hapus perhitungan honor ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-slate-400">
                                Belum ada data perhitungan honor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($honors->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $honors->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
