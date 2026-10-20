@extends('layouts.app')

@section('title', 'Laporan Rekapitulasi Honor')
@section('page_description', 'Laporan keuangan pencairan honor tutor dan status pembayaran per periode')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Rekapitulasi Honor & Payroll Tutor</h3>
            <p class="text-xs text-slate-500">Monitoring anggaran pengeluaran honorarium dan realisasi pembayaran</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Tagihan Honor</div>
            <div class="text-xl font-extrabold text-slate-900">Rp {{ number_format($ringkasan['total_honor'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">Telah Dibayar (Lunas)</div>
            <div class="text-xl font-extrabold text-emerald-600">Rp {{ number_format($ringkasan['dibayar'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Sedang Diproses</div>
            <div class="text-xl font-extrabold text-blue-600">Rp {{ number_format($ringkasan['diproses'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">Belum Dibayar</div>
            <div class="text-xl font-extrabold text-amber-600">Rp {{ number_format($ringkasan['belum_dibayar'], 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm print:hidden">
        <form method="GET" action="{{ route('laporan.honor') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
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
                <input type="month" name="dari" value="{{ request('dari') }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" placeholder="Periode">
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['tutor', 'status', 'dari']))
                    <a href="{{ route('laporan.honor') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-colors">
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
                        <th class="px-5 py-3.5">Nama Tutor</th>
                        <th class="px-5 py-3.5">Tarif / Jam</th>
                        <th class="px-5 py-3.5">Total Jam</th>
                        <th class="px-5 py-3.5">Penyesuaian</th>
                        <th class="px-5 py-3.5">Total Honor</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($honors as $honor)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ \Carbon\Carbon::parse($honor->periode_bulan)->isoFormat('MMMM Y') }}
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ $honor->tutor->nama ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                Rp {{ number_format($honor->tarif_per_jam, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-slate-700 font-medium">
                                {{ $honor->total_jam_mengajar }} Jam
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                {{ $honor->penyesuaian_honor != 0 ? 'Rp ' . number_format($honor->penyesuaian_honor, 0, ',', '.') : '-' }}
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                                Belum ada data honor yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($honors->hasPages())
            <div class="p-4 border-t border-slate-200 print:hidden">
                {{ $honors->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
