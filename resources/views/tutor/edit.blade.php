@extends('layouts.app')

@section('title', 'Ubah Data Tutor')
@section('page_description', 'Perbarui informasi profil atau status kepegawaian tutor')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Edit Data Tutor: {{ $tutor->nama }}</h3>
            <p class="text-xs text-slate-500">Perbarui data yang diperlukan di bawah ini</p>
        </div>
        <a href="{{ route('tutor.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('tutor.update', $tutor->id_tutor) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section: Data Diri -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 pb-2 border-b border-slate-100">Informasi Pribadi</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $tutor->nama) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="block text-xs font-semibold text-slate-700 mb-1.5">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $tutor->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $tutor->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $tutor->tanggal_lahir) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $tutor->email) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div>
                        <label for="no_telepon" class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $tutor->no_telepon) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="alamat" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Tempat Tinggal</label>
                        <textarea id="alamat" name="alamat" rows="2"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">{{ old('alamat', $tutor->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section: Data Kepegawaian -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 pb-2 border-b border-slate-100">Kualifikasi & Status</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="pendidikan_terakhir" class="block text-xs font-semibold text-slate-700 mb-1.5">Pendidikan Terakhir</label>
                        <input type="text" id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $tutor->pendidikan_terakhir) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div>
                        <label for="status_kerja" class="block text-xs font-semibold text-slate-700 mb-1.5">Status Kerja <span class="text-red-500">*</span></label>
                        <select id="status_kerja" name="status_kerja" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                            <option value="Aktif" {{ old('status_kerja', $tutor->status_kerja) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ old('status_kerja', $tutor->status_kerja) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div>
                        <label for="tanggal_bergabung" class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Bergabung</label>
                        <input type="date" id="tanggal_bergabung" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', $tutor->tanggal_bergabung) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('tutor.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
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
