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

        // ============================
        // 1. VALIDASI DATA PROFIL
        // ============================
        $validated = $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'username'       => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'nomor_telepon'  => 'required|string|max:15',
            'deskripsi'      => 'nullable|string|max:500',
            'foto_profil'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Lokasi: opsional
            'provinsi'       => 'nullable|string|max:100',
            'kabupaten'      => 'nullable|string|max:100',
            
            // NOTE: Validasi password dipisah di bawah agar lebih aman
        ]);

        // ============================
        // 2. Upload foto profil
        // ============================
        if ($request->hasFile('foto_profil')) {
            $path = public_path('uploads/avatars/');
            $file = $request->file('foto_profil');

            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }

            $nama_file = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            if ($user->foto_profil) {
                File::delete($path . $user->foto_profil);
            }

            $file->move($path, $nama_file);
            $user->foto_profil = $nama_file;
        }

        // ============================
        // 3. Update Data Dasar
        // ============================
        $user->nama_lengkap  = $validated['nama_lengkap'];
        $user->username      = $validated['username'];
        $user->nomor_telepon = $validated['nomor_telepon'];
        $user->deskripsi     = $validated['deskripsi'] ?? $user->deskripsi;

        // Lokasi (update hanya jika diisi)
        if (!empty($validated['provinsi'])) {
            $user->provinsi = $validated['provinsi'];
        }

        if (!empty($validated['kabupaten'])) {
            $user->kabupaten = $validated['kabupaten'];
        }

        // ============================
        // 4. LOGIKA GANTI PASSWORD
        // ============================
        // Cek jika user mengisi salah satu field password
        if ($request->filled('current_password') || $request->filled('password')) {
            
            // A. Wajibkan mengisi password lama
            $request->validate([
                'current_password' => 'required',
            ], [
                'current_password.required' => 'Untuk mengganti password, harap masukkan password lama Anda.'
            ]);

            // B. Cek kesesuaian Password Lama dengan Database
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama yang Anda masukkan salah.']);
            }

            // C. Validasi Password Baru
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ], [
                'password.min'       => 'Password baru minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            ]);

            // D. Simpan Password Baru (di-hash)
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('profile.show', $user->username)
            ->with('success', 'Profil berhasil diperbarui!');
    }
}