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

        // Barang Donasi (Publik)
        $barangDonasi   = $user->barangDonasis()->latest()->get();
        $barangDiterima = collect();
        $favorites      = collect();

        // Jika melihat profil sendiri, tampilkan tab privat
        if (Auth::check() && Auth::id() === $user->id) {
            $barangDiterima = $user->barangDiterima()->latest()->get();
            $favorites      = $user->favorites()->latest()->get();
        }

        return view('profile.show', [
            'user'           => $user,
            'barangDonasi'   => $barangDonasi,
            'barangDiterima' => $barangDiterima,
            'favorites'      => $favorites,
        ]);
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // VALIDASI
        // Lokasi dibuat OPSIONAL di halaman ini agar kamu bisa upload foto saja.
        $validated = $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'username'       => [
                'required',
                'string',
                'max:255',
                // HAPUS alpha_dash supaya boleh mengandung @ dan .
                Rule::unique('users')->ignore($user->id),
            ],
            'nomor_telepon'  => 'required|string|max:15',
            'deskripsi'      => 'nullable|string|max:500',
            'foto_profil'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password'       => 'nullable|string|min:8|confirmed',

            // Lokasi: opsional di edit profil
            'provinsi'       => 'nullable|string|max:100',
            'kabupaten'      => 'nullable|string|max:100',
        ]);

        // ============================
        // Upload foto profil (jika ada)
        // ============================
        if ($request->hasFile('foto_profil')) {
            $path = public_path('uploads/avatars/');
            $file = $request->file('foto_profil');

            // Pastikan folder ada
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }

            $nama_file = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                File::delete($path . $user->foto_profil);
            }

            // Simpan yang baru
            $file->move($path, $nama_file);
            $user->foto_profil = $nama_file;
        }

        // ============================
        // Data dasar
        // ============================
        $user->nama_lengkap  = $validated['nama_lengkap'];
        $user->username      = $validated['username'];
        $user->nomor_telepon = $validated['nomor_telepon'];
        $user->deskripsi     = $validated['deskripsi'] ?? $user->deskripsi;

        // ============================
        // Lokasi (opsional)
        // hanya di-update kalau ada isi
        // ============================
        if (!empty($validated['provinsi'])) {
            $user->provinsi = $validated['provinsi'];
        }

        if (!empty($validated['kabupaten'])) {
            $user->kabupaten = $validated['kabupaten'];
        }

        // ============================
        // Password (opsional)
        // ============================
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('profile.show', $user->username)
            ->with('success', 'Profil berhasil diperbarui!');
    }
}
