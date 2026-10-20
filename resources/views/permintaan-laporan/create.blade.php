@extends('layouts.app')

@section('title', 'Ajukan Permintaan Laporan')
@section('page_description', 'Kirim tiket permohonan penyusunan dokumen laporan operasional atau keuangan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Tiket Permohonan Laporan</h3>
            <p class="text-xs text-slate-500">Pilih jenis data rekapitulasi dan rentang waktu yang diperlukan</p>
        </div>
        <a href="{{ route('permintaan-laporan.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('permintaan-laporan.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="jenis" class="block text-xs font-semibold text-slate-700 mb-1.5">Jenis Laporan yang Diminta <span class="text-red-500">*</span></label>
                <select id="jenis" name="jenis" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">-- Pilih Jenis Rekap Laporan --</option>
                    @foreach(['Rekap Tutor', 'Rekap Absensi', 'Rekap Jadwal', 'Rekap Honor', 'Informasi Operasional'] as $jenis)
                        <option value="{{ $jenis }}" {{ old('jenis') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="periode_awal" class="block text-xs font-semibold text-slate-700 mb-1.5">Periode Mulai (Opsional)</label>
                    <input type="date" id="periode_awal" name="periode_awal" value="{{ old('periode_awal') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div>
                    <label for="periode_akhir" class="block text-xs font-semibold text-slate-700 mb-1.5">Periode Selesai (Opsional)</label>
                    <input type="date" id="periode_akhir" name="periode_akhir" value="{{ old('periode_akhir') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-xs font-semibold text-slate-700 mb-1.5">Tujuan & Kebutuhan Spesifik Laporan</label>
                <textarea id="keterangan" name="keterangan" rows="3"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                    placeholder="Contoh: Dibutuhkan untuk audit internal akhir semester dan evaluasi serapan anggaran honor...">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('permintaan-laporan.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-600/20 transition-all">
                    Kirim Permintaan Tiket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
