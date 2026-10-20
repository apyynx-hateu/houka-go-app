<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $query = Penilaian::with(['tutor', 'penilai']);

        if ($request->filled('tutor')) {
            $query->where('id_tutor', $request->tutor);
        }

        if ($request->filled('periode')) {
            $query->where('periode', 'like', '%' . $request->periode . '%');
        }

        $penilaians = $query->orderBy('tanggal_penilaian', 'desc')->paginate(15)->withQueryString();
        $tutors = Tutor::orderBy('nama')->get();

        return view('penilaian.index', compact('penilaians', 'tutors'));
    }

    public function create()
    {
        $tutors = Tutor::orderBy('nama')->get();
        return view('penilaian.create', compact('tutors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tutor' => 'required|exists:tutor,id_tutor',
            'tanggal_penilaian' => 'required|date',
            'periode' => 'nullable|string|max:255',
            'kualitas_mengajar' => 'nullable|string',
            'catatan_kinerja' => 'nullable|string',
        ]);

        $validated['id_petinggi'] = Auth::id();

        Penilaian::create($validated);

        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil ditambahkan.');
    }

    public function show(Penilaian $penilaian)
    {
        $penilaian->load(['tutor', 'penilai']);
        return view('penilaian.show', compact('penilaian'));
    }

    public function edit(Penilaian $penilaian)
    {
        $tutors = Tutor::orderBy('nama')->get();
        return view('penilaian.edit', compact('penilaian', 'tutors'));
    }

    public function update(Request $request, Penilaian $penilaian)
    {
        $validated = $request->validate([
            'id_tutor' => 'required|exists:tutor,id_tutor',
            'tanggal_penilaian' => 'required|date',
            'periode' => 'nullable|string|max:255',
            'kualitas_mengajar' => 'nullable|string',
            'catatan_kinerja' => 'nullable|string',
        ]);

        $penilaian->update($validated);

        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil diperbarui.');
    }

    public function destroy(Penilaian $penilaian)
    {
        $penilaian->delete();
        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil dihapus.');
    }
}
