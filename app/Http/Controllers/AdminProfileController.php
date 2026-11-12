<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    public function show()
    {
        $admin = Auth::guard('petinggi')->user();
        return view('admin.profile', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('petinggi')->user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('petinggi', 'username')->ignore($admin->id_petinggi, 'id_petinggi'),
            ],
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validated['password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $admin->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
            $admin->password = Hash::make($validated['password']);
        }

        $admin->nama = $validated['nama'];
        $admin->username = $validated['username'];
        $admin->save();

        return redirect()->route('admin.profile')->with('success', 'Profil Administrator berhasil diperbarui.');
    }
}
