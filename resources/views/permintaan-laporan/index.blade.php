@extends('layouts.app')

@section('title', 'Permintaan Laporan')
@section('page_description', 'Tiket permohonan pembuatan rekapitulasi data dan laporan eksekutif')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Tiket Permintaan Laporan</h3>
            <p class="text-xs text-slate-500">Total data: {{ $permintaans->total() }} permohonan terdaftar</p>
        </div>
        <a href="{{ route('permintaan-laporan.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-md shadow-brand-600/20 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajukan Permintaan Baru
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('permintaan-laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-5">
                <select name="jenis" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">Semua Jenis Laporan</option>
                    @foreach(['Rekap Tutor', 'Rekap Absensi', 'Rekap Jadwal', 'Rekap Honor', 'Informasi Operasional'] as $jns)
                        <option value="{{ $jns }}" {{ request('jenis') == $jns ? 'selected' : '' }}>{{ $jns }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-4">
                <select name="status" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">Semua Status</option>
                    <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="sm:col-span-3 flex gap-2">
                <button type="submit" class="flex-1 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['jenis', 'status']))
                    <a href="{{ route('permintaan-laporan.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-colors">
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
                        <th class="px-5 py-3.5">Waktu Permintaan</th>
                        <th class="px-5 py-3.5">Jenis Laporan</th>
                        <th class="px-5 py-3.5">Rentang Periode</th>
                        <th class="px-5 py-3.5">Peminta (Petinggi)</th>
                        <th class="px-5 py-3.5">Status Tiket</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($permintaans as $req)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-semibold text-slate-800">
                                {{ \Carbon\Carbon::parse($req->waktu_permintaan)->isoFormat('D MMM Y, HH:mm') }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-800">{{ $req->jenis }}</span>
                                @if($req->keterangan)
                                    <div class="text-[11px] text-slate-400 max-w-xs truncate">{{ $req->keterangan }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                @if($req->periode_awal && $req->periode_akhir)
                                    {{ \Carbon\Carbon::parse($req->periode_awal)->isoFormat('D MMM Y') }} s/d {{ \Carbon\Carbon::parse($req->periode_akhir)->isoFormat('D MMM Y') }}
                                @else
                                    <span class="text-slate-400 italic">Semua periode</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-700">{{ $req->peminta->nama ?? 'Petinggi' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $req->peminta->jabatan ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                @if($req->status === 'Selesai')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Selesai
                                    </span>
                                @elseif($req->status === 'Diproses')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Diproses
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('permintaan-laporan.show', $req->id_permintaan) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-brand-600 hover:bg-brand-50 transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('permintaan-laporan.edit', $req->id_permintaan) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Ubah Status">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('permintaan-laporan.destroy', $req->id_permintaan) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permintaan laporan ini?')">
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
                                Belum ada riwayat permohonan laporan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($permintaans->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $permintaans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
