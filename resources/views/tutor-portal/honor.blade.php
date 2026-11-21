@extends('layouts.tutor')

@section('title', 'Honorarium Saya')
@section('page_description', 'Transparansi perhitungan honor mengajar dan status pencairan dana Anda')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Filters -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Rekapitulasi Honorarium</h3>
            <p class="text-xs text-slate-500">Daftar rekap honor per periode bulanan Anda</p>
        </div>

        <form method="GET" action="{{ route('tutor.honors') }}" class="flex items-center gap-2">
            <select name="status" class="px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 bg-white">
                <option value="">Semua Status</option>
                <option value="Belum Dibayar" {{ request('status') == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="Dibayar" {{ request('status') == 'Dibayar' ? 'selected' : '' }}>Dibayar</option>
            </select>

            <select name="tahun" class="px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 bg-white">
                <option value="">Semua Tahun</option>
                @foreach([date('Y'), date('Y') - 1, date('Y') - 2] as $thn)
                    <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['status', 'tahun']))
                <a href="{{ route('tutor.honors') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Informational Note about the formula -->
    {{-- <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200/80 text-xs text-teal-900 flex items-start gap-3">
        <svg class="w-5 h-5 text-teal-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <strong class="font-bold">Informasi Transparansi Rumus Honor Houka GO:</strong>
            <p class="mt-0.5 leading-relaxed text-teal-800">
                Honorarium dihitung berbasis sesi presensi: (Jumlah Sesi &times; Tarif Rp50.000) + Fee Check-in (Rp20.000/sesi) + Fee Transport (Rp30.000/sesi) + Penyesuaian.
            </p>
        </div>
    </div> --}}

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Periode Bulan</th>
                        <th class="px-5 py-3.5 text-center">Jumlah Sesi</th>
                        <th class="px-5 py-3.5">Tarif Sesi</th>
                        <th class="px-5 py-3.5">Fee Check-in</th>
                        <th class="px-5 py-3.5">Fee Transport</th>
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
                            <td class="px-5 py-4 text-center font-bold text-teal-600">
                                {{ $honor->jumlah_sesi }} Sesi
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                Rp {{ number_format($honor->tarif_per_sesi, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                Rp {{ number_format($honor->fee_check_in, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                Rp {{ number_format($honor->fee_transport, 0, ',', '.') }}
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
                            <td class="px-5 py-4 font-extrabold text-slate-900">
                                Rp {{ number_format($honor->total_honor, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4">
                                @if($honor->status === 'Dibayar')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Dibayar (Lunas)
                                    </span>
                                @elseif($honor->status === 'Diproses')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        Diproses
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Belum Dibayar
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('tutor.honors.show', $honor->id_honor) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-teal-50 hover:bg-teal-100 text-teal-700 text-xs font-semibold transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Slip Rincian
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-10 text-center text-slate-400">
                                Belum ada catatan honorarium yang tercatat.
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
