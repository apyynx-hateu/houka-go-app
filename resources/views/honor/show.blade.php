@extends('layouts.app')

@section('title', 'Slip Rincian Honor')
@section('page_description', 'Detail perhitungan honor dan riwayat transaksi pencairan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <a href="{{ route('honor.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            &larr; Kembali ke Daftar Honor
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Slip
            </button>
            @if($honor->status !== 'Dibayar')
                <a href="{{ route('pembayaran-honor.create') }}?honor={{ $honor->id_honor }}" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Catat Pembayaran
                </a>
            @endif
        </div>
    </div>

    <!-- Slip Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
        <!-- Slip Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-200 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Houka GO &bull; Slip Honorarium</span>
                <h3 class="text-xl font-bold text-slate-800 mt-0.5">Periode: {{ \Carbon\Carbon::parse($honor->periode_bulan)->isoFormat('MMMM Y') }}</h3>
                <p class="text-xs text-slate-400">ID Honor: #HN-{{ str_pad($honor->id_honor, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="sm:text-right">
                @if($honor->status === 'Dibayar')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        LUNAS / DIBAYAR
                    </span>
                @elseif($honor->status === 'Diproses')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        SEDANG DIPROSES
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        BELUM DIBAYAR
                    </span>
                @endif
            </div>
        </div>

        <!-- Tutor Profile Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-4 text-xs">
            <div>
                <span class="text-slate-400 block mb-0.5">Nama Tutor</span>
                <strong class="text-slate-800 text-sm">{{ $honor->tutor->nama ?? '-' }}</strong>
                <span class="text-slate-500 block">{{ $honor->tutor->email ?? '-' }} &bull; {{ $honor->tutor->no_telepon ?? '-' }}</span>
            </div>
            <div class="sm:text-right">
                <span class="text-slate-400 block mb-0.5">Pendidikan / Status</span>
                <strong class="text-slate-700">{{ $honor->tutor->pendidikan_terakhir ?? '-' }}</strong>
                <span class="text-slate-500 block">Status: {{ $honor->tutor->status_kerja ?? '-' }}</span>
            </div>
        </div>

        <!-- Breakdown Table -->
        <div class="border-t border-b border-slate-200 py-4 my-2">
            <h4 class="text-xs font-bold text-slate-700 uppercase mb-3">Rincian Komponen Honor</h4>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1 text-slate-600">
                    <span>Tarif per Jam Mengajar</span>
                    <span class="font-medium">Rp {{ number_format($honor->tarif_per_jam, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1 text-slate-600">
                    <span>Total Jam Mengajar (Absensi Hadir)</span>
                    <span class="font-medium">{{ $honor->total_jam_mengajar }} Jam</span>
                </div>
                <div class="flex justify-between py-1 text-slate-600">
                    <span>Subtotal Dasar (Tarif &times; Jam)</span>
                    <span class="font-medium">Rp {{ number_format($honor->tarif_per_jam * $honor->total_jam_mengajar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1 text-slate-600">
                    <span>Penyesuaian / Bonus / Potongan</span>
                    <span class="font-medium {{ $honor->penyesuaian_honor >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ $honor->penyesuaian_honor > 0 ? '+' : '' }}Rp {{ number_format($honor->penyesuaian_honor, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between py-2 border-t border-slate-200 text-slate-900 font-extrabold text-sm">
                    <span>Total Honor Bersih</span>
                    <span class="text-brand-600">Rp {{ number_format($honor->total_honor, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="mt-6">
            <h4 class="text-xs font-bold text-slate-700 uppercase mb-3">Riwayat Pembayaran Terkait ({{ $honor->pembayaran->count() }})</h4>
            @if($honor->pembayaran->isEmpty())
                <p class="text-xs text-slate-400 py-3 text-center bg-slate-50 rounded-xl">Belum ada transaksi pembayaran untuk honor ini.</p>
            @else
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($honor->pembayaran as $pemb)
                        <div class="py-2.5 flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($pemb->tanggal_pembayaran)->isoFormat('D MMM Y') }}</span>
                                <span class="text-slate-400 ml-2">Rek: {{ $pemb->nomor_rekening ?? '-' }}</span>
                                @if($pemb->keterangan)
                                    <div class="text-[11px] text-slate-400">{{ $pemb->keterangan }}</div>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-slate-800 block">Rp {{ number_format($pemb->jumlah, 0, ',', '.') }}</span>
                                @if($pemb->status === 'Berhasil')
                                    <span class="text-[10px] font-semibold text-emerald-600">Berhasil</span>
                                @elseif($pemb->status === 'Pending')
                                    <span class="text-[10px] font-semibold text-amber-600">Pending</span>
                                @else
                                    <span class="text-[10px] font-semibold text-red-500">Gagal</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
