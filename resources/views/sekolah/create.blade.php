@extends('layouts.app')

@section('title', 'Tambah Sekolah Mitra')
@section('page_description', 'Form pendaftaran sekolah dan lokasi ruangan belajar')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Tambah Sekolah Baru</h3>
            <p class="text-xs text-slate-500">Masukkan detail sekolah dan fasilitas ruangan</p>
        </div>
        <a href="{{ route('sekolah.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('sekolah.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="nama_sekolah" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Sekolah <span class="text-red-500">*</span></label>
                <input type="text" id="nama_sekolah" name="nama_sekolah" value="{{ old('nama_sekolah') }}" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                    placeholder="Contoh: SMA Negeri 1 Jakarta">
            </div>

            <div>
                <label for="alamat" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Lengkap</label>
                <textarea id="alamat" name="alamat" rows="2"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                    placeholder="Jl. Budi Utomo No. 7...">{{ old('alamat') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="ruangan" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama / Kode Ruangan</label>
                    <input type="text" id="ruangan" name="ruangan" value="{{ old('ruangan') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        placeholder="Contoh: Lab Komputer 2, Ruang 101">
                </div>

                <div>
                    <label for="kapasitas" class="block text-xs font-semibold text-slate-700 mb-1.5">Kapasitas Maksimal Siswa</label>
                    <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas') }}" min="1"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        placeholder="Contoh: 30">
                </div>
            </div>

            <div>
                <label for="status_aktif" class="block text-xs font-semibold text-slate-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                <select id="status_aktif" name="status_aktif" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="Aktif" {{ old('status_aktif', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ old('status_aktif') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('sekolah.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-600/20 transition-all">
                    Simpan Sekolah
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
