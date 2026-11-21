@extends('layouts.app')

@section('title', 'Hitung Honor Baru')
@section('page_description', 'Kalkulasi otomatis honor tutor berdasarkan jumlah sesi kehadiran Hadir')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Formulir Perhitungan Honor</h3>
            <p class="text-xs text-slate-500">Sistem akan menghitung jumlah sesi dari absensi 'Hadir' pada bulan yang dipilih</p>
        </div>
        <a href="{{ route('honor.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('honor.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="id_tutor" class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Tutor <span class="text-red-500">*</span></label>
                <select id="id_tutor" name="id_tutor" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">-- Pilih Tutor --</option>
                    @foreach($tutors as $tutor)
                        <option value="{{ $tutor->id_tutor }}" {{ old('id_tutor', request('tutor')) == $tutor->id_tutor ? 'selected' : '' }}>
                            {{ $tutor->nama }} ({{ $tutor->pendidikan_terakhir ?? 'Tutor' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="periode_bulan" class="block text-xs font-semibold text-slate-700 mb-1.5">Periode Bulan <span class="text-red-500">*</span></label>
                    <input type="month" id="periode_bulan" name="periode_bulan" value="{{ old('periode_bulan', date('Y-m')) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div>
                    <label for="jumlah_sesi" class="block text-xs font-semibold text-slate-700 mb-1.5">Jumlah Sesi (Opsional)</label>
                    <input type="number" id="jumlah_sesi" name="jumlah_sesi" value="{{ old('jumlah_sesi') }}" min="0"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        placeholder="Kosongkan untuk hitung otomatis dari absensi">
                    <span class="text-[10px] text-slate-400 mt-1 block">Jika kosong, diambil dari total absensi Hadir.</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="tarif_per_sesi" class="block text-xs font-semibold text-slate-700 mb-1.5">Tarif per Sesi (Rp)</label>
                    <input disabled type="number" id="tarif_per_sesi" name="tarif_per_sesi" value="{{ old('tarif_per_sesi', 50000) }}" min="0" step="1000"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div>
                    <label for="fee_check_in" class="block text-xs font-semibold text-slate-700 mb-1.5">Fee Check-in Total (Rp)</label>
                    <input disabled type="number" id="fee_check_in" name="fee_check_in" value="{{ old('fee_check_in') }}" min="0" step="1000"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        placeholder="20.000">
                </div>

                <div>
                    <label for="fee_transport" class="block text-xs font-semibold text-slate-700 mb-1.5">Fee Transport Total (Rp)</label>
                    <input disabled type="number" id="fee_transport" name="fee_transport" value="{{ old('fee_transport') }}" min="0" step="1000"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        placeholder="30.000">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="penyesuaian_honor" class="block text-xs font-semibold text-slate-700 mb-1.5">Penyesuaian / Bonus / Potongan (Rp)</label>
                    <input type="number" id="penyesuaian_honor" name="penyesuaian_honor" value="{{ old('penyesuaian_honor', 0) }}" step="1000"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        placeholder="Masukkan nilai minus (-) jika potongan">
                </div>

                <div>
                    <label for="catatan" class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Tambahan</label>
                    <input type="text" id="catatan" name="catatan" value="{{ old('catatan') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        placeholder="Keterangan opsional">
                </div>
            </div>

            {{-- <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200/60 text-xs text-amber-800">
                <strong class="font-semibold block mb-0.5">Rumus Perhitungan Honor Sesi:</strong>
                <code>Total = (Jumlah Sesi &times; Tarif Rp50.000) + Fee Check-in + Fee Transport + Penyesuaian</code>
            </div> --}}

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('honor.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-600/20 transition-all">
                    Kalkulasi & Simpan Honor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
