@extends('layouts.tutor')

@section('title', 'Profil Tutor')
@section('page_description', 'Kelola informasi kontak pribadi dan keamanan akun Anda')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <div class="flex items-center gap-4 pb-6 border-b border-slate-100 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-teal-600 to-emerald-500 text-white font-extrabold text-2xl flex items-center justify-center shadow-lg shadow-teal-500/20">
                {{ substr($tutor->nama ?? 'T', 0, 1) }}
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-800">{{ $tutor->nama }}</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-teal-50 text-teal-700 border border-teal-200">
                        {{ $tutor->username ? '@' . $tutor->username : 'Tutor Bimbel' }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Status: {{ $tutor->status_kerja }}
                    </span>
                </div>
            </div>
        </div>

        <form action="{{ route('tutor.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Data Pribadi yang Terkunci (Read-Only) -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 pb-2 border-b border-slate-100">Informasi Kepegawaian (Terkunci)</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Lengkap</label>
                        <input type="text" value="{{ $tutor->nama }}" disabled class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 text-xs cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Jenis Kelamin</label>
                        <input type="text" value="{{ $tutor->jenis_kelamin ?? '-' }}" disabled class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 text-xs cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Tanggal Bergabung</label>
                        <input type="text" value="{{ $tutor->tanggal_bergabung ? \Carbon\Carbon::parse($tutor->tanggal_bergabung)->isoFormat('D MMM Y') : '-' }}" disabled class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 text-xs cursor-not-allowed">
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 mt-2">Untuk perubahan nama resmi dan data kepegawaian, hubungi manajemen Houka GO.</p>
            </div>

            <!-- Kontak Pribadi (Boleh Diedit) -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 pb-2 border-b border-slate-100">Kontak & Pendidikan</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="no_telepon" class="block text-xs font-semibold text-slate-700 mb-1.5">No. Telepon / WhatsApp</label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $tutor->no_telepon) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500"
                            placeholder="Contoh: 081234567890">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $tutor->email) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500"
                            placeholder="Contoh: nama@domain.com">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="pendidikan_terakhir" class="block text-xs font-semibold text-slate-700 mb-1.5">Pendidikan Terakhir / Gelar</label>
                        <input type="text" id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $tutor->pendidikan_terakhir) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500"
                            placeholder="Contoh: S1 Pendidikan Fisika">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="alamat" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Tempat Tinggal</label>
                        <textarea id="alamat" name="alamat" rows="2"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500"
                            placeholder="Alamat lengkap domisili saat ini...">{{ old('alamat', $tutor->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Keamanan & Ganti Password -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 pb-2 border-b border-slate-100">Keamanan Login Akun</h4>
                <p class="text-xs text-slate-400 mb-3">Kosongkan kolom password di bawah ini jika tidak ingin mengubah password akun Anda.</p>

                <div class="space-y-3">
                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500"
                            placeholder="Wajib diisi jika hendak mengganti password">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password Baru</label>
                            <input type="password" id="password" name="password"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500"
                                placeholder="Minimal 6 karakter">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('tutor.dashboard') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold shadow-md shadow-teal-600/20 transition-all">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
