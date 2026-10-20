@extends('layouts.app')

@section('title', 'Rapor Evaluasi Kinerja')
@section('page_description', 'Detail penilaian kualitas mengajar dan catatan evaluasi tutor')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('penilaian.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            &larr; Kembali ke Daftar Penilaian
        </a>
        <a href="{{ route('penilaian.edit', $penilaian->id_penilaian) }}" class="px-3.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold shadow-sm transition-colors">
            Ubah Data
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <span class="text-xs text-slate-400 block mb-1">Periode Evaluasi</span>
                <h3 class="text-lg font-bold text-slate-800">{{ $penilaian->periode ?? 'Umum' }}</h3>
                <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($penilaian->tanggal_penilaian)->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
        </div>

        <div class="space-y-5 text-xs mt-6">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                <div>
                    <span class="text-slate-400 block mb-0.5">Tutor yang Dinilai</span>
                    <a href="{{ route('tutor.show', $penilaian->id_tutor) }}" class="font-bold text-sm text-slate-800 hover:text-brand-600">
                        {{ $penilaian->tutor->nama ?? '-' }}
                    </a>
                    <span class="text-slate-500 block text-[11px]">{{ $penilaian->tutor->email ?? '-' }} &bull; {{ $penilaian->tutor->pendidikan_terakhir ?? '-' }}</span>
                </div>
                <div class="text-right">
                    <span class="text-slate-400 block text-[10px]">Status:</span>
                    <span class="font-semibold text-emerald-600">{{ $penilaian->tutor->status_kerja ?? '-' }}</span>
                </div>
            </div>

            <div>
                <span class="font-bold text-slate-700 block mb-1">Kualitas Mengajar & Pemahaman Siswa:</span>
                <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200 text-slate-700 leading-relaxed">
                    {{ $penilaian->kualitas_mengajar ?? 'Tidak ada catatan khusus.' }}
                </div>
            </div>

            <div>
                <span class="font-bold text-slate-700 block mb-1">Catatan Kinerja & Sikap Kerja:</span>
                <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200 text-slate-700 leading-relaxed">
                    {{ $penilaian->catatan_kinerja ?? 'Tidak ada catatan.' }}
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <span>Penilai: <strong class="text-slate-700">{{ $penilaian->penilai->nama ?? 'Petinggi' }}</strong> ({{ $penilaian->penilai->jabatan ?? 'CO Founder' }})</span>
                <span>Tercatat: {{ $penilaian->created_at ? $penilaian->created_at->isoFormat('D MMM Y, HH:mm') : '-' }}</span>
            </div>
        </div>
    </div>

</div>
@endsection
