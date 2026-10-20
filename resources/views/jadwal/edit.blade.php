@extends('layouts.app')

@section('title', 'Ubah Jadwal Mengajar')
@section('page_description', 'Perbarui waktu atau tutor pengampu sesi bimbingan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Edit Jadwal Mengajar</h3>
            <p class="text-xs text-slate-500">Perbarui informasi jadwal di bawah ini</p>
        </div>
        <a href="{{ route('jadwal.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('jadwal.update', $jadwal->id_jadwal) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="id_tutor" class="block text-xs font-semibold text-slate-700 mb-1.5">Tutor <span class="text-red-500">*</span></label>
                <select id="id_tutor" name="id_tutor" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    @foreach($tutors as $tutor)
                        <option value="{{ $tutor->id_tutor }}" {{ old('id_tutor', $jadwal->id_tutor) == $tutor->id_tutor ? 'selected' : '' }}>
                            {{ $tutor->nama }} ({{ $tutor->pendidikan_terakhir ?? 'Tutor' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="id_sekolah" class="block text-xs font-semibold text-slate-700 mb-1.5">Sekolah Mitra & Ruangan <span class="text-red-500">*</span></label>
                <select id="id_sekolah" name="id_sekolah" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    @foreach($sekolahs as $sekolah)
                        <option value="{{ $sekolah->id_sekolah }}" {{ old('id_sekolah', $jadwal->id_sekolah) == $sekolah->id_sekolah ? 'selected' : '' }}>
                            {{ $sekolah->nama_sekolah }} - {{ $sekolah->ruangan ?? 'Ruang kelas' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="hari" class="block text-xs font-semibold text-slate-700 mb-1.5">Hari <span class="text-red-500">*</span></label>
                    <select id="hari" name="hari" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                            <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="jam_mulai" class="block text-xs font-semibold text-slate-700 mb-1.5">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', substr($jadwal->jam_mulai, 0, 5)) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div>
                    <label for="jam_selesai" class="block text-xs font-semibold text-slate-700 mb-1.5">Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', substr($jadwal->jam_selesai, 0, 5)) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>
            </div>

            <div>
                <label for="tanggal" class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Khusus / Spesifik (Opsional)</label>
                <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $jadwal->tanggal) }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('jadwal.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
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
