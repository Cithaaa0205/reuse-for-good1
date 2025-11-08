<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil user.
     * (Dipanggil oleh /profile/{username})
     */
    public function show(string $username)
    {
        // Cari user berdasarkan username, jika tidak ada, tampilkan 404
        $user = User::where('username', $username)->firstOrFail();
        
        // Ambil barang donasi milik user
        $barangDonasi = $user->barangDonasis()->latest()->get();
        
        // Ambil barang yang diterima user (logika ini bisa dikembangkan nanti)
        // $barangDiterima = $user->requestBarangs()->where('status', 'Selesai')->latest()->get();
        
        // Tampilkan view profile.show dan kirim data user
        return view('profile.show', [
            'user' => $user,
            'barangDonasi' => $barangDonasi,
            // 'barangDiterima' => $barangDiterima
        ]);
    }

    /**
     * Tampilkan form edit profil.
     * (Dipanggil oleh /profile/edit)
     */
    public function edit()
    {
        // Ambil user yang sedang login
        $user = Auth::user();
        // Tampilkan view profile.edit dan kirim data user
        return view('profile.edit', compact('user'));
    }

    /**
     * Update profil user.
     * (Dipanggil oleh /profile saat submit form edit)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('users')->ignore($user->id),
            ],
            'nomor_telepon' => 'required|string|max:15',
            'deskripsi' => 'nullable|string|max:500', // Validasi deskripsi
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // === LOGIKA UPLOAD FOTO PROFIL ===
        if ($request->hasFile('foto_profil')) {
            // 1. Tentukan path
            $path = public_path('uploads/avatars/');
            $file = $request->file('foto_profil');
            $nama_file = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // 2. Hapus foto lama jika ada
            if ($user->foto_profil) {
                File::delete($path . $user->foto_profil);
            }

            // 3. Pindahkan file baru
            $file->move($path, $nama_file);

            // 4. Simpan nama file baru ke user
            $user->foto_profil = $nama_file;
        }
        // === AKHIR LOGIKA UPLOAD ===

        $user->nama_lengkap = $request->nama_lengkap;
        $user->username = $request->username;
        $user->nomor_telepon = $request->nomor_telepon;
        $user->deskripsi = $request->deskripsi; // Simpan deskripsi

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.show', $user->username)->with('success', 'Profil berhasil diperbarui!');
    }
}