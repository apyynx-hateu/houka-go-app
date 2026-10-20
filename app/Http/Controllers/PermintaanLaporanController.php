<?php

namespace App\Http\Controllers;

use App\Models\PermintaanLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermintaanLaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = PermintaanLaporan::with('peminta');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $permintaans = $query->orderBy('waktu_permintaan', 'desc')->paginate(15)->withQueryString();

        return view('permintaan-laporan.index', compact('permintaans'));
    }

    public function create()
    {
        return view('permintaan-laporan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:Rekap Tutor,Rekap Absensi,Rekap Jadwal,Rekap Honor,Informasi Operasional',
            'periode_awal' => 'nullable|date',
            'periode_akhir' => 'nullable|date|after_or_equal:periode_awal',
            'keterangan' => 'nullable|string',
        ]);

        $validated['id_petinggi'] = Auth::id();
        $validated['waktu_permintaan'] = now();
        $validated['status'] = 'Menunggu';

        PermintaanLaporan::create($validated);

        return redirect()->route('permintaan-laporan.index')->with('success', 'Permintaan laporan berhasil diajukan.');
    }

    public function show(PermintaanLaporan $permintaanLaporan)
    {
        $permintaanLaporan->load('peminta');
        return view('permintaan-laporan.show', compact('permintaanLaporan'));
    }

    public function edit(PermintaanLaporan $permintaanLaporan)
    {
        return view('permintaan-laporan.edit', compact('permintaanLaporan'));
    }

    public function update(Request $request, PermintaanLaporan $permintaanLaporan)
    {
        $validated = $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai',
            'keterangan' => 'nullable|string',
        ]);

        $permintaanLaporan->update($validated);

        return redirect()->route('permintaan-laporan.index')->with('success', 'Status permintaan berhasil diperbarui.');
    }

    public function destroy(PermintaanLaporan $permintaanLaporan)
    {
        $permintaanLaporan->delete();
        return redirect()->route('permintaan-laporan.index')->with('success', 'Permintaan laporan berhasil dihapus.');
    }
}
