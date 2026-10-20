@extends('layouts.app')

@section('title', 'Input Absensi Tutor')
@section('page_description', 'Form pencatatan kehadiran tutor pada sesi bimbingan belajar')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Catat Kehadiran Tutor</h3>
            <p class="text-xs text-slate-500">Pilih tutor, sesi jadwal (jika ada), dan status kehadiran</p>
        </div>
        <a href="{{ route('absensi.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('absensi.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="id_tutor" class="block text-xs font-semibold text-slate-700 mb-1.5">Tutor <span class="text-red-500">*</span></label>
                <select id="id_tutor" name="id_tutor" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">-- Pilih Tutor --</option>
                    @foreach($tutors as $tutor)
                        <option value="{{ $tutor->id_tutor }}" {{ old('id_tutor', request('tutor')) == $tutor->id_tutor ? 'selected' : '' }}>
                            {{ $tutor->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="id_jadwal" class="block text-xs font-semibold text-slate-700 mb-1.5">Sesi Jadwal Terkait (Opsional)</label>
                <select id="id_jadwal" name="id_jadwal"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    <option value="">-- Tanpa Jadwal Spesifik --</option>
                    @foreach($jadwals as $jadwal)
                        <option value="{{ $jadwal->id_jadwal }}" data-tutor="{{ $jadwal->id_tutor }}" {{ old('id_jadwal', request('jadwal')) == $jadwal->id_jadwal ? 'selected' : '' }}>
                            [{{ $jadwal->tutor->nama ?? 'Tutor' }}] {{ $jadwal->hari }} ({{ substr($jadwal->jam_mulai, 0, 5) }}-{{ substr($jadwal->jam_selesai, 0, 5) }}) - {{ $jadwal->sekolah->nama_sekolah ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="tanggal" class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">Status Kehadiran <span class="text-red-500">*</span></label>
                    <select id="status" name="status" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                        <option value="Hadir" {{ old('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Tidak Hadir" {{ old('status') == 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-xs font-semibold text-slate-700 mb-1.5">Keterangan / Materi Ajar</label>
                <textarea id="keterangan" name="keterangan" rows="3"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                    placeholder="Contoh: Mengajar Matematika Bab 3, hadir tepat waktu...">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('absensi.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-600/20 transition-all">
                    Simpan Absensi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
