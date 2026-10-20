@extends('layouts.app')

@section('title', 'Detail Permintaan Laporan')
@section('page_description', 'Rincian tiket permohonan data dan riwayat status penyusunan')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('permintaan-laporan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            &larr; Kembali ke Daftar Permintaan
        </a>
        <a href="{{ route('permintaan-laporan.edit', $permintaanLaporan->id_permintaan) }}" class="px-3.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold shadow-sm transition-colors">
            Ubah Status
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <span class="text-xs text-slate-400 block mb-1">Tiket #REQ-{{ str_pad($permintaanLaporan->id_permintaan, 4, '0', STR_PAD_LEFT) }}</span>
                <h3 class="text-lg font-bold text-slate-800">{{ $permintaanLaporan->jenis }}</h3>
            </div>
            <div>
                @if($permintaanLaporan->status === 'Selesai')
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Selesai
                    </span>
                @elseif($permintaanLaporan->status === 'Diproses')
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        Diproses
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Menunggu
                    </span>
                @endif
            </div>
        </div>

        <div class="space-y-4 text-xs">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-slate-400 block mb-0.5">Pemohon:</span>
                    <strong class="text-slate-800 text-sm">{{ $permintaanLaporan->peminta->nama ?? 'Petinggi' }}</strong>
                    <span class="text-slate-500 block text-[11px]">{{ $permintaanLaporan->peminta->jabatan ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Waktu Pengajuan:</span>
                    <strong class="text-slate-800">{{ \Carbon\Carbon::parse($permintaanLaporan->waktu_permintaan)->isoFormat('D MMMM Y, HH:mm') }} WIB</strong>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-slate-400 block mb-1">Rentang Tanggal yang Diminta:</span>
                @if($permintaanLaporan->periode_awal && $permintaanLaporan->periode_akhir)
                    <strong class="text-slate-800 text-sm">
                        {{ \Carbon\Carbon::parse($permintaanLaporan->periode_awal)->isoFormat('D MMMM Y') }} — {{ \Carbon\Carbon::parse($permintaanLaporan->periode_akhir)->isoFormat('D MMMM Y') }}
                    </strong>
                @else
                    <span class="text-slate-500 italic">Keseluruhan periode data yang tersedia</span>
                @endif
            </div>

            <div>
                <span class="text-slate-400 block mb-0.5">Keterangan / Kebutuhan:</span>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-700 leading-relaxed">
                    {{ $permintaanLaporan->keterangan ?? 'Tidak ada catatan keterangan khusus.' }}
                </div>
            </div>

            <!-- Quick link to the relevant report -->
            <div class="pt-4 border-t border-slate-100">
                <span class="text-slate-400 block mb-2 font-medium">Buka Data Terkait:</span>
                @if($permintaanLaporan->jenis === 'Rekap Tutor')
                    <a href="{{ route('laporan.tutor') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-brand-600 font-semibold hover:bg-indigo-100 transition-colors">
                        Buka Laporan Tutor &rarr;
                    </a>
                @elseif($permintaanLaporan->jenis === 'Rekap Absensi')
                    <a href="{{ route('laporan.absensi') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-brand-600 font-semibold hover:bg-indigo-100 transition-colors">
                        Buka Laporan Absensi &rarr;
                    </a>
                @elseif($permintaanLaporan->jenis === 'Rekap Jadwal')
                    <a href="{{ route('laporan.jadwal') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-brand-600 font-semibold hover:bg-indigo-100 transition-colors">
                        Buka Laporan Jadwal &rarr;
                    </a>
                @elseif($permintaanLaporan->jenis === 'Rekap Honor')
                    <a href="{{ route('laporan.honor') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-brand-600 font-semibold hover:bg-indigo-100 transition-colors">
                        Buka Laporan Honor &rarr;
                    </a>
                @else
                    <a href="{{ route('laporan.operasional') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-brand-600 font-semibold hover:bg-indigo-100 transition-colors">
                        Buka Ringkasan Operasional &rarr;
                    </a>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
