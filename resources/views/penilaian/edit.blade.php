@extends('layouts.app')

@section('title', 'Ubah Catatan Penilaian')
@section('page_description', 'Koreksi evaluasi kualitas mengajar atau catatan kinerja tutor')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Edit Evaluasi Tutor</h3>
            <p class="text-xs text-slate-500">Tutor: {{ $penilaian->tutor->nama ?? '-' }} &bull; Periode: {{ $penilaian->periode ?? '-' }}</p>
        </div>
        <a href="{{ route('penilaian.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('penilaian.update', $penilaian->id_penilaian) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="id_tutor" class="block text-xs font-semibold text-slate-700 mb-1.5">Tutor yang Dievaluasi <span class="text-red-500">*</span></label>
                <select id="id_tutor" name="id_tutor" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 bg-white">
                    @foreach($tutors as $tutor)
                        <option value="{{ $tutor->id_tutor }}" {{ old('id_tutor', $penilaian->id_tutor) == $tutor->id_tutor ? 'selected' : '' }}>
                            {{ $tutor->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="tanggal_penilaian" class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Penilaian <span class="text-red-500">*</span></label>
                    <input type="date" id="tanggal_penilaian" name="tanggal_penilaian" value="{{ old('tanggal_penilaian', $penilaian->tanggal_penilaian) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div>
                    <label for="periode" class="block text-xs font-semibold text-slate-700 mb-1.5">Periode Evaluasi</label>
                    <input type="text" id="periode" name="periode" value="{{ old('periode', $penilaian->periode) }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>
            </div>

            <div>
                <label for="kualitas_mengajar" class="block text-xs font-semibold text-slate-700 mb-1.5">Kualitas Mengajar & Penguasaan Materi</label>
                <textarea id="kualitas_mengajar" name="kualitas_mengajar" rows="3"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">{{ old('kualitas_mengajar', $penilaian->kualitas_mengajar) }}</textarea>
            </div>

            <div>
                <label for="catatan_kinerja" class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Performa, Kedisiplinan & Sikap</label>
                <textarea id="catatan_kinerja" name="catatan_kinerja" rows="3"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">{{ old('catatan_kinerja', $penilaian->catatan_kinerja) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('penilaian.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
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
