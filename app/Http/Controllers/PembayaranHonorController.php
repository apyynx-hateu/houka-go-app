<?php

namespace App\Http\Controllers;

use App\Models\PembayaranHonor;
use App\Models\Honor;
use Illuminate\Http\Request;

class PembayaranHonorController extends Controller
{
    public function index(Request $request)
    {
        $query = PembayaranHonor::with(['honor.tutor']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_pembayaran', [$request->dari, $request->sampai]);
        }

        $pembayarans = $query->orderBy('tanggal_pembayaran', 'desc')->paginate(15)->withQueryString();

        return view('pembayaran-honor.index', compact('pembayarans'));
    }

    public function create(Request $request)
    {
        $honors = Honor::with('tutor')
                    ->whereIn('status', ['Belum Dibayar', 'Diproses'])
                    ->orderBy('periode_bulan', 'desc')
                    ->get();

        $selectedHonor = $request->honor ? Honor::with('tutor')->find($request->honor) : null;

        return view('pembayaran-honor.create', compact('honors', 'selectedHonor'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_honor' => 'required|exists:honor,id_honor',
            'tanggal_pembayaran' => 'required|date',
            'jumlah' => 'required|numeric|min:0.01',
            'nomor_rekening' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $validated['status'] = 'Pending';

        PembayaranHonor::create($validated);

        // Update status honor menjadi Diproses
        $honor = Honor::find($validated['id_honor']);
        if ($honor && $honor->status === 'Belum Dibayar') {
            $honor->update(['status' => 'Diproses']);
        }

        return redirect()->route('pembayaran-honor.index')->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function show(PembayaranHonor $pembayaranHonor)
    {
        $pembayaranHonor->load(['honor.tutor']);
        return view('pembayaran-honor.show', compact('pembayaranHonor'));
    }

    public function edit(PembayaranHonor $pembayaranHonor)
    {
        $honors = Honor::with('tutor')->orderBy('periode_bulan', 'desc')->get();
        return view('pembayaran-honor.edit', compact('pembayaranHonor', 'honors'));
    }

    public function update(Request $request, PembayaranHonor $pembayaranHonor)
    {
        $validated = $request->validate([
            'tanggal_pembayaran' => 'required|date',
            'jumlah' => 'required|numeric|min:0.01',
            'nomor_rekening' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,Berhasil,Gagal',
            'keterangan' => 'nullable|string',
        ]);

        $pembayaranHonor->update($validated);

        // Jika berhasil, cek apakah total pembayaran berhasil >= total honor
        if ($validated['status'] === 'Berhasil') {
            $honor = $pembayaranHonor->honor;
            $totalDibayar = $honor->pembayaranBerhasil()->sum('jumlah');
            if ($totalDibayar >= $honor->total_honor) {
                $honor->update(['status' => 'Dibayar']);
            } else {
                $honor->update(['status' => 'Diproses']);
            }
        }

        return redirect()->route('pembayaran-honor.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(PembayaranHonor $pembayaranHonor)
    {
        $pembayaranHonor->delete();
        return redirect()->route('pembayaran-honor.index')->with('success', 'Pembayaran berhasil dihapus.');
    }
}
