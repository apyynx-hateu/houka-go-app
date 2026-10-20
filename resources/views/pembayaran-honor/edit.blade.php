@extends('layouts.app')

@section('title', 'Ubah Transaksi Pembayaran')
@section('page_description', 'Perbarui status atau rincian rekening transaksi pembayaran honor')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Edit Transaksi Pembayaran</h3>
            <p class="text-xs text-slate-500">Tutor: {{ $pembayaranHonor->honor->tutor->nama ?? 'Tutor' }} &bull; Periode: {{ \Carbon\Carbon::parse($pembayaranHonor->honor->periode_bulan)->isoFormat('MMMM Y') }}</p>
        </div>
        <a href="{{ route('pembayaran-honor.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('pembayaran-honor.update', $pembayaranHonor->id_pembayaran) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="tanggal_pembayaran" class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                    <input type="date" id="tanggal_pembayaran" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', $pembayaranHonor->tanggal_pembayaran) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div>
                    <label for="jumlah" class="block text-xs font-semibold text-slate-700 mb-1.5">Nominal Transfer (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah', $pembayaranHonor->jumlah) }}" min="1" step="100" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>
            </div>

            <div>
                <label for="nomor_rekening" class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Rekening / Bank Tujuan</label>
                <input type="text" id="nomor_rekening" name="nomor_rekening" value="{{ old('nomor_rekening', $pembayaranHonor->nomor_rekening) }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>

            <div>
                <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">Status Transaksi <span class="text-red-500">*</span></label>
                <select id="status" name="status" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="Pending" {{ old('status', $pembayaranHonor->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Berhasil" {{ old('status', $pembayaranHonor->status) == 'Berhasil' ? 'selected' : '' }}>Berhasil</option>
                    <option value="Gagal" {{ old('status', $pembayaranHonor->status) == 'Gagal' ? 'selected' : '' }}>Gagal</option>
                </select>
                <span class="text-[10px] text-slate-400 mt-1 block">Jika status diubah ke 'Berhasil' dan jumlah mencukupi, status honor otomatis lunas (Dibayar).</span>
            </div>

            <div>
                <label for="keterangan" class="block text-xs font-semibold text-slate-700 mb-1.5">Keterangan / Catatan Bukti</label>
                <textarea id="keterangan" name="keterangan" rows="3"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">{{ old('keterangan', $pembayaranHonor->keterangan) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('pembayaran-honor.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
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
