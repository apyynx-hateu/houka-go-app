@extends('layouts.app')

@section('title', 'Hitung Honor Baru')
@section('page_description', 'Kalkulasi otomatis honor tutor berdasarkan total jam kehadiran Hadir')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Formulir Perhitungan Honor</h3>
            <p class="text-xs text-slate-500">Sistem akan menghitung total durasi mengajar dari absensi 'Hadir' pada bulan yang dipilih</p>
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
                    <label for="tarif_per_jam" class="block text-xs font-semibold text-slate-700 mb-1.5">Tarif per Jam (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" id="tarif_per_jam" name="tarif_per_jam" value="{{ old('tarif_per_jam', 50000) }}" min="0" step="1000" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        placeholder="Contoh: 50000">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="jumlah_siswa" class="block text-xs font-semibold text-slate-700 mb-1.5">Estimasi Jumlah Siswa Diampu</label>
                    <input type="number" id="jumlah_siswa" name="jumlah_siswa" value="{{ old('jumlah_siswa', 0) }}" min="0"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        placeholder="Contoh: 15">
                </div>

                <div>
                    <label for="penyesuaian_honor" class="block text-xs font-semibold text-slate-700 mb-1.5">Penyesuaian / Bonus / Potongan (Rp)</label>
                    <input type="number" id="penyesuaian_honor" name="penyesuaian_honor" value="{{ old('penyesuaian_honor', 0) }}" step="1000"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        placeholder="Masukkan nilai minus (-) jika potongan">
                    <span class="text-[10px] text-slate-400 mt-1 block">Contoh bonus: 50000, potongan: -20000</span>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200/60 text-xs text-amber-800">
                <strong class="font-semibold block mb-0.5">Formula Perhitungan Otomatis:</strong>
                <code>Total Honor = (Tarif per Jam &times; Total Jam Mengajar dari Absensi Hadir) + Penyesuaian</code>
            </div>

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
