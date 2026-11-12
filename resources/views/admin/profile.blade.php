@extends('layouts.app')

@section('title', 'Profil Petinggi')
@section('page_description', 'Kelola informasi akun administrator dan keamanan login')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <div class="flex items-center gap-4 pb-6 border-b border-slate-100 mb-6">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-500 text-white font-extrabold text-xl flex items-center justify-center shadow-lg shadow-brand-500/20">
                {{ substr($admin->nama ?? 'A', 0, 1) }}
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">{{ $admin->nama }}</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                        {{ $admin->jabatan }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $admin->status_aktif }}
                    </span>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $admin->nama) }}" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="username" class="block text-xs font-semibold text-slate-700 mb-1.5">Username Login <span class="text-red-500">*</span></label>
                    <input type="text" id="username" name="username" value="{{ old('username', $admin->username) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jabatan / Peran</label>
                    <input type="text" value="{{ $admin->jabatan }}" disabled
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 text-xs cursor-not-allowed">
                    <span class="text-[10px] text-slate-400 mt-1 block">Jabatan administratif hanya dapat diatur oleh sistem master.</span>
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Ubah Password Akun</h4>
                <p class="text-xs text-slate-400 mb-4">Kosongkan jika Anda tidak ingin mengubah password akun Anda saat ini.</p>

                <div class="space-y-3">
                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                            placeholder="Wajib diisi jika mengganti password">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password Baru</label>
                            <input type="password" id="password" name="password"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                                placeholder="Minimal 6 karakter">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-600/20 transition-all">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
