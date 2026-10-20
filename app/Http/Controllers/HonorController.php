<?php

namespace App\Http\Controllers;

use App\Models\Honor;
use App\Models\Tutor;
use App\Services\HonorCalculationService;
use Illuminate\Http\Request;

class HonorController extends Controller
{
    protected $honorService;

    public function __construct(HonorCalculationService $honorService)
    {
        $this->honorService = $honorService;
    }

    public function index(Request $request)
    {
        $query = Honor::with('tutor');

        if ($request->filled('tutor')) {
            $query->where('id_tutor', $request->tutor);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('periode')) {
            $query->whereYear('periode_bulan', substr($request->periode, 0, 4))
                  ->whereMonth('periode_bulan', substr($request->periode, 5, 2));
        }

        $honors = $query->orderBy('periode_bulan', 'desc')->paginate(15)->withQueryString();
        $tutors = Tutor::aktif()->orderBy('nama')->get();

        return view('honor.index', compact('honors', 'tutors'));
    }

    public function create()
    {
        $tutors = Tutor::aktif()->orderBy('nama')->get();
        return view('honor.create', compact('tutors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tutor' => 'required|exists:tutor,id_tutor',
            'periode_bulan' => 'required|date_format:Y-m',
            'tarif_per_jam' => 'required|numeric|min:0',
            'jumlah_siswa' => 'nullable|integer|min:0',
            'penyesuaian_honor' => 'nullable|numeric',
        ]);

        $data = $this->honorService->hitungHonor(
            $validated['id_tutor'],
            $validated['periode_bulan'] . '-01',
            (float) $validated['tarif_per_jam'],
            (float) ($validated['penyesuaian_honor'] ?? 0),
            (int) ($validated['jumlah_siswa'] ?? 0)
        );

        $data['status'] = 'Belum Dibayar';
        Honor::create($data);

        return redirect()->route('honor.index')->with('success', 'Honor berhasil dibuat. Total jam dihitung dari absensi Hadir.');
    }

    public function show(Honor $honor)
    {
        $honor->load(['tutor', 'pembayaran']);
        return view('honor.show', compact('honor'));
    }

    public function edit(Honor $honor)
    {
        if ($honor->status === 'Dibayar') {
            return redirect()->route('honor.index')->with('error', 'Honor yang sudah dibayar tidak dapat diubah.');
        }

        $tutors = Tutor::aktif()->orderBy('nama')->get();
        return view('honor.edit', compact('honor', 'tutors'));
    }

    public function update(Request $request, Honor $honor)
    {
        if ($honor->status === 'Dibayar') {
            return redirect()->route('honor.index')->with('error', 'Honor yang sudah dibayar tidak dapat diubah.');
        }

        $validated = $request->validate([
            'tarif_per_jam' => 'required|numeric|min:0',
            'jumlah_siswa' => 'nullable|integer|min:0',
            'total_jam_mengajar' => 'required|numeric|min:0',
            'penyesuaian_honor' => 'nullable|numeric',
            'status' => 'required|in:Belum Dibayar,Diproses,Dibayar',
        ]);

        $validated['penyesuaian_honor'] = $validated['penyesuaian_honor'] ?? 0;
        $validated['total_honor'] = ($validated['tarif_per_jam'] * $validated['total_jam_mengajar']) + $validated['penyesuaian_honor'];

        $honor->update($validated);

        return redirect()->route('honor.index')->with('success', 'Honor berhasil diperbarui.');
    }

    public function destroy(Honor $honor)
    {
        if ($honor->pembayaran()->exists()) {
            return redirect()->route('honor.index')->with('error', 'Honor tidak dapat dihapus karena memiliki catatan pembayaran.');
        }

        $honor->delete();
        return redirect()->route('honor.index')->with('success', 'Honor berhasil dihapus.');
    }
}
