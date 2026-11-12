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
            'jumlah_sesi' => 'nullable|integer|min:0',
            'tarif_per_sesi' => 'nullable|numeric|min:0',
            'fee_check_in' => 'nullable|numeric|min:0',
            'fee_transport' => 'nullable|numeric|min:0',
            'penyesuaian_honor' => 'nullable|numeric',
            'catatan' => 'nullable|string',
        ]);

        $data = $this->honorService->hitungHonor(
            $validated['id_tutor'],
            $validated['periode_bulan'] . '-01',
            isset($validated['jumlah_sesi']) && $validated['jumlah_sesi'] !== '' ? (int) $validated['jumlah_sesi'] : null,
            !empty($validated['tarif_per_sesi']) ? (float) $validated['tarif_per_sesi'] : null,
            (float) ($validated['penyesuaian_honor'] ?? 0),
            isset($validated['fee_check_in']) && $validated['fee_check_in'] !== '' ? (float) $validated['fee_check_in'] : null,
            isset($validated['fee_transport']) && $validated['fee_transport'] !== '' ? (float) $validated['fee_transport'] : null,
            $validated['catatan'] ?? null
        );

        $data['status'] = 'Belum Dibayar';
        Honor::create($data);

        return redirect()->route('honor.index')->with('success', 'Honor berhasil dihitung dan dicatat sesuai rumus berbasis sesi.');
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
            'jumlah_sesi' => 'required|integer|min:0',
            'tarif_per_sesi' => 'required|numeric|min:0',
            'fee_check_in' => 'required|numeric|min:0',
            'fee_transport' => 'required|numeric|min:0',
            'penyesuaian_honor' => 'nullable|numeric',
            'status' => 'required|in:Belum Dibayar,Diproses,Dibayar',
            'catatan' => 'nullable|string',
        ]);

        $validated['penyesuaian_honor'] = $validated['penyesuaian_honor'] ?? 0;
        $honorSesi = $validated['jumlah_sesi'] * $validated['tarif_per_sesi'];
        $validated['total_honor'] = max(0, $honorSesi + $validated['fee_check_in'] + $validated['fee_transport'] + $validated['penyesuaian_honor']);

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
