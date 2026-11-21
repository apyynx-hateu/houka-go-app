@extends('layouts.app')

@section('title', 'Catat Pembayaran Honor')
@section('page_description', 'Pencatatan bukti dan jumlah transfer pencairan honor tutor')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Form Pembayaran Honor</h3>
                <p class="text-xs text-slate-500">Pilih honor yang akan dicairkan dan masukkan nominal pembayaran</p>
            </div>
            <a href="{{ route('pembayaran-honor.index') }}"
                class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
            <form action="{{ route('pembayaran-honor.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="id_honor" class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Catatan Honor
                        <span class="text-red-500">*</span></label>
                    <select id="id_honor" name="id_honor" required onchange="updateJumlah(this)"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                        <option value="">-- Pilih Honor Tutor --</option>
                        @foreach ($honors as $h)
                            <option value="{{ $h->id_honor }}" data-total="{{ $h->total_honor }}"
                                {{ old('id_honor', request('honor')) == $h->id_honor ? 'selected' : '' }}>
                                [{{ $h->tutor->nama ?? 'Tutor' }}] Periode:
                                {{ \Carbon\Carbon::parse($h->periode_bulan)->isoFormat('MMMM Y') }} - Rp
                                {{ number_format($h->total_honor, 0, ',', '.') }} (Status: {{ $h->status }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_pembayaran" class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal
                            Pembayaran <span class="text-red-500">*</span></label>
                        <input type="date" id="tanggal_pembayaran" name="tanggal_pembayaran"
                            value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div>
                        <label for="jumlah" class="block text-xs font-semibold text-slate-700 mb-1.5">Nominal Transfer
                            (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" id="jumlah" name="jumlah"
                            value="{{ old('jumlah', $selectedHonor ? $selectedHonor->total_honor : '') }}" min="1" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                </div>

                <div>
                    <label for="nomor_rekening" class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Rekening /
                        Bank Tujuan</label>
                    <input type="text" id="nomor_rekening" name="nomor_rekening" value="{{ old('nomor_rekening') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div>
                    <label for="keterangan" class="block text-xs font-semibold text-slate-700 mb-1.5">Keterangan / Nomor
                        Referensi Transaksi</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">{{ old('keterangan') }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('pembayaran-honor.index') }}"
                        class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-600/20 transition-all">
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateJumlah(select) {
            const option = select.options[select.selectedIndex];
            if (option && option.dataset.total) {
                document.getElementById('jumlah').value = option.dataset.total;
            }
        }
    </script>
@endsection
