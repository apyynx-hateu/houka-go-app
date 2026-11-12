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
            'status' => 'nullable|in:Pending,Berhasil,Gagal',
            'keterangan' => 'nullable|string',
        ]);

        $validated['status'] = $validated['status'] ?? 'Pending';

        $pembayaran = PembayaranHonor::create($validated);

        // Sinkronisasi status honor
        $honor = Honor::find($validated['id_honor']);
        if ($honor) {
            $this->sinkronisasiStatusHonor($honor);
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

        // Sinkronisasi status honor terkait
        $this->sinkronisasiStatusHonor($pembayaranHonor->honor);

        return redirect()->route('pembayaran-honor.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(PembayaranHonor $pembayaranHonor)
    {
        $honor = $pembayaranHonor->honor;
        $pembayaranHonor->delete();

        if ($honor) {
            $this->sinkronisasiStatusHonor($honor);
        }

        return redirect()->route('pembayaran-honor.index')->with('success', 'Pembayaran berhasil dihapus.');
    }

    /**
     * Memastikan konsistensi status data honor terhadap riwayat transaksi pembayarannya.
     */
    protected function sinkronisasiStatusHonor(Honor $honor): void
    {
        $totalBerhasil = (float) $honor->pembayaranBerhasil()->sum('jumlah');
        $hasPending = $honor->pembayaran()->where('status', 'Pending')->exists();

        if ($totalBerhasil >= (float) $honor->total_honor && (float) $honor->total_honor > 0) {
            $honor->update(['status' => 'Dibayar']);
        } elseif ($totalBerhasil > 0 || $hasPending) {
            $honor->update(['status' => 'Diproses']);
        } else {
            $honor->update(['status' => 'Belum Dibayar']);
        }
    }
}
