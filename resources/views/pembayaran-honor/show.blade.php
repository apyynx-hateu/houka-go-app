@extends('layouts.app')

@section('title', 'Detail Transaksi Pembayaran')
@section('page_description', 'Rincian data transfer honor dan status rekonsiliasi keuangan')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('pembayaran-honor.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            &larr; Kembali ke Riwayat Pembayaran
        </a>
        <a href="{{ route('pembayaran-honor.edit', $pembayaranHonor->id_pembayaran) }}" class="px-3.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold shadow-sm transition-colors">
            Ubah Status
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <span class="text-xs text-slate-400 block mb-1">Bukti Transaksi #TRX-{{ str_pad($pembayaranHonor->id_pembayaran, 5, '0', STR_PAD_LEFT) }}</span>
                <h3 class="text-xl font-extrabold text-slate-900">Rp {{ number_format($pembayaranHonor->jumlah, 0, ',', '.') }}</h3>
            </div>
            <div>
                @if($pembayaranHonor->status === 'Berhasil')
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Berhasil
                    </span>
                @elseif($pembayaranHonor->status === 'Pending')
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Pending
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        Gagal
                    </span>
                @endif
            </div>
        </div>

        <div class="space-y-4 text-xs">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-slate-400 block mb-0.5">Tanggal Transfer</span>
                    <strong class="text-slate-800">{{ \Carbon\Carbon::parse($pembayaranHonor->tanggal_pembayaran)->isoFormat('D MMMM Y') }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Nomor Rekening / Bank</span>
                    <strong class="text-slate-800 font-mono">{{ $pembayaranHonor->nomor_rekening ?? '-' }}</strong>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="text-slate-400 block font-medium">Informasi Honor Tertaut:</span>
                <div class="flex items-center justify-between">
                    <div>
                        <a href="{{ route('honor.show', $pembayaranHonor->id_honor) }}" class="font-bold text-slate-800 hover:text-brand-600">
                            {{ $pembayaranHonor->honor->tutor->nama ?? 'Tutor' }}
                        </a>
                        <div class="text-[11px] text-slate-500">
                            Periode: {{ $pembayaranHonor->honor ? \Carbon\Carbon::parse($pembayaranHonor->honor->periode_bulan)->isoFormat('MMMM Y') : '-' }}
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400 block">Total Tagihan Honor:</span>
                        <strong class="text-slate-800 font-bold">Rp {{ number_format($pembayaranHonor->honor->total_honor ?? 0, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <div>
                <span class="text-slate-400 block mb-0.5">Catatan / Keterangan:</span>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-700">
                    {{ $pembayaranHonor->keterangan ?? 'Tidak ada catatan.' }}
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 text-slate-400 text-[11px]">
                Tercatat pada: {{ $pembayaranHonor->created_at ? $pembayaranHonor->created_at->isoFormat('D MMMM Y, HH:mm') : '-' }} WIB
            </div>
        </div>
    </div>

</div>
@endsection
