<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Honor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HonorController extends Controller
{
    public function index(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();

        $query = Honor::with('pembayaran')
            ->where('id_tutor', $tutor->id_tutor);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('periode_bulan', $request->tahun);
        }

        $honors = $query->orderBy('periode_bulan', 'desc')->paginate(15)->withQueryString();

        return view('tutor-portal.honor', compact('honors'));
    }

    public function show(Honor $honor)
    {
        $tutor = Auth::guard('tutor')->user();

        // Security check: Tutor can only view their own honor
        if ($honor->id_tutor !== $tutor->id_tutor) {
            abort(403, 'Akses ditolak: Anda tidak memiliki akses ke rincian honor tutor lain.');
        }

        $honor->load(['pembayaran']);

        return view('tutor-portal.honor-show', compact('honor'));
    }
}
