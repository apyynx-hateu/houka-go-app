<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $tutor = Auth::guard('tutor')->user();
        return view('tutor-portal.profile', compact('tutor'));
    }

    public function update(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();

        $validated = $request->validate([
            'no_telepon' => 'nullable|string|max:20',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('tutor', 'email')->ignore($tutor->id_tutor, 'id_tutor'),
            ],
            'alamat' => 'nullable|string|max:1000',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validated['password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $tutor->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
            }
            $tutor->password = Hash::make($validated['password']);
        }

        $tutor->no_telepon = $validated['no_telepon'];
        $tutor->email = $validated['email'];
        $tutor->alamat = $validated['alamat'];
        $tutor->pendidikan_terakhir = $validated['pendidikan_terakhir'];
        $tutor->save();

        return redirect()->route('tutor.profile')->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
