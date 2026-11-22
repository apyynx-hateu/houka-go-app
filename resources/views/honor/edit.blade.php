@extends('layouts.app')

@section('title', 'Ubah Perhitungan Honor')
@section('page_description', 'Koreksi sesi, fee, atau status pembayaran honor')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Edit Honor: {{ $honor->tutor->nama ?? 'Tutor' }}</h3>
                <p class="text-xs text-slate-500">Periode:
                    {{ \Carbon\Carbon::parse($honor->periode_bulan)->isoFormat('MMMM Y') }}</p>
            </div>
            <a href="{{ route('honor.index') }}"
                class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
            <form action="{{ route('honor.update', $honor->id_honor) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="jumlah_sesi" class="block text-xs font-semibold text-slate-700 mb-1.5">Jumlah Sesi
                            Mengajar <span class="text-red-500">*</span></label>
                        <input type="number" id="jumlah_sesi" name="jumlah_sesi"
                            value="{{ old('jumlah_sesi', $honor->jumlah_sesi) }}" min="0"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div>
                        <label for="tarif_per_sesi" class="block text-xs font-semibold text-slate-700 mb-1.5">Tarif per Sesi
                            (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" id="tarif_per_sesi" name="tarif_per_sesi"
                            value="{{ old('tarif_per_sesi', $honor->tarif_per_sesi) }}" min="0" step="1000"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="fee_check_in" class="block text-xs font-semibold text-slate-700 mb-1.5">Fee Check-in
                            Total (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" id="fee_check_in" name="fee_check_in"
                            value="{{ old('fee_check_in', $honor->fee_check_in) }}" min="0" step="1000"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div>
                        <label for="fee_transport" class="block text-xs font-semibold text-slate-700 mb-1.5">Fee Transport
                            Total (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" id="fee_transport" name="fee_transport"
                            value="{{ old('fee_transport', $honor->fee_transport) }}" min="0" step="1000"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="penyesuaian_honor" class="block text-xs font-semibold text-slate-700 mb-1.5">Penyesuaian
                            Honor (Rp)</label>
                        <input type="number" id="penyesuaian_honor" name="penyesuaian_honor"
                            value="{{ old('penyesuaian_honor', $honor->penyesuaian_honor) }}" step="1000"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">Status Pembayaran
                            <span class="text-red-500">*</span></label>
                        <select id="status" name="status" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                            <option value="Belum Dibayar"
                                {{ old('status', $honor->status) == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar
                            </option>
                            <option value="Diproses" {{ old('status', $honor->status) == 'Diproses' ? 'selected' : '' }}>
                                Diproses</option>
                            <option value="Dibayar" {{ old('status', $honor->status) == 'Dibayar' ? 'selected' : '' }}>
                                Dibayar</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="catatan" class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Tambahan</label>
                    <input autocomplete="off" type="text" id="catatan" name="catatan"
                        value="{{ old('catatan', $honor->catatan) }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('honor.index') }}"
                        class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-600/20 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
