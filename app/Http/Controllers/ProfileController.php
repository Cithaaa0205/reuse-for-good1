<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function show(string $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        // 1. Barang Donasi (Publik - Siapa saja bisa lihat)
        $barangDonasi = $user->barangDonasis()->latest()->get();
        
        $barangDiterima = collect();
        $favorites = collect();

        // 2. Cek apakah user melihat profilnya sendiri
        if (Auth::check() && Auth::id() === $user->id) {
            // Barang Diterima (Privat - Panggil relasi baru)
            $barangDiterima = $user->barangDiterima()->latest()->get();
            
            // Favorit (Privat - Panggil relasi baru)
            $favorites = $user->favorites()->latest()->get();
        }
        
        return view('profile.show', [
            'user' => $user,
            'barangDonasi' => $barangDonasi,
            'barangDiterima' => $barangDiterima,
            'favorites' => $favorites
        ]);
    }

    // ... (Fungsi edit dan update tidak perlu diubah, biarkan seperti sebelumnya)
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'nomor_telepon' => 'required|string|max:15',
            'deskripsi' => 'nullable|string|max:500',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->hasFile('foto_profil')) {
            $path = public_path('uploads/avatars/');
            $file = $request->file('foto_profil');
            $nama_file = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            if ($user->foto_profil) File::delete($path . $user->foto_profil);
            $file->move($path, $nama_file);
            $user->foto_profil = $nama_file;
        }

        $user->nama_lengkap = $request->nama_lengkap;
        $user->username = $request->username;
        $user->nomor_telepon = $request->nomor_telepon;
        $user->deskripsi = $request->deskripsi;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.show', $user->username)->with('success', 'Profil berhasil diperbarui!');
    }
}