@extends('layouts.app')

@section('title', 'Proses Tiket Permintaan Laporan')
@section('page_description', 'Perbarui status penyusunan dokumen laporan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Update Status Tiket #REQ-{{ str_pad($permintaanLaporan->id_permintaan, 4, '0', STR_PAD_LEFT) }}</h3>
            <p class="text-xs text-slate-500">Jenis: {{ $permintaanLaporan->jenis }} &bull; Peminta: {{ $permintaanLaporan->peminta->nama ?? 'Petinggi' }}</p>
        </div>
        <a href="{{ route('permintaan-laporan.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('permintaan-laporan.update', $permintaanLaporan->id_permintaan) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-xs space-y-1 mb-4">
                <span class="text-slate-400 block font-medium">Informasi Permohonan:</span>
                <div>Jenis: <strong class="text-slate-800">{{ $permintaanLaporan->jenis }}</strong></div>
                <div>Waktu Permintaan: <strong class="text-slate-700">{{ \Carbon\Carbon::parse($permintaanLaporan->waktu_permintaan)->isoFormat('D MMMM Y, HH:mm') }}</strong></div>
                <div>Periode: <strong class="text-slate-700">{{ $permintaanLaporan->periode_awal ?? '-' }} s/d {{ $permintaanLaporan->periode_akhir ?? '-' }}</strong></div>
            </div>

            <div>
                <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">Status Tiket Laporan <span class="text-red-500">*</span></label>
                <select id="status" name="status" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="Menunggu" {{ old('status', $permintaanLaporan->status) == 'Menunggu' ? 'selected' : '' }}>Menunggu (Dalam antrean)</option>
                    <option value="Diproses" {{ old('status', $permintaanLaporan->status) == 'Diproses' ? 'selected' : '' }}>Diproses (Sedang disusun)</option>
                    <option value="Selesai" {{ old('status', $permintaanLaporan->status) == 'Selesai' ? 'selected' : '' }}>Selesai (Dokumen siap)</option>
                </select>
            </div>

            <div>
                <label for="keterangan" class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Pemrosesan / Link Dokumen</label>
                <textarea id="keterangan" name="keterangan" rows="3"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">{{ old('keterangan', $permintaanLaporan->keterangan) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('permintaan-laporan.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-600/20 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
