<?php

namespace App\Http\Controllers;

// ... (use statements)
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
// ... (use statements lainnya)
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil user.
     */
    // app/Http/Controllers/ProfileController.php

public function show(string $username)
{
    $user = User::where('username', $username)->firstOrFail();

    // Barang yang dia donasikan (publik)
    $barangDonasi = $user->barangDonasis()->latest()->get();

    // Default kosong
    $barangDiterima = collect();
    $favorites      = collect();

    // Hanya kalau lagi lihat profil sendiri
    if (Auth::check() && Auth::id() === $user->id) {
        $barangDiterima = $user->barangDiterima()->latest()->get();

        // Ambil barang yang difavoritkan
        $favorites = $user->favorites()
            ->latest('favorites.created_at') // urut dari yang paling baru difavoritkan
            ->get();
    }

    return view('profile.show', [
        'user'           => $user,
        'barangDonasi'   => $barangDonasi,
        'barangDiterima' => $barangDiterima,
        'favorites'      => $favorites,
    ]);
}


    /**
     * Tampilkan form edit profil.
     */
// ... (fungsi edit tidak berubah)
// ...
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update profil user.
     */
// ... (fungsi update tidak berubah)
// ...
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
            'deskripsi' => 'nullable|string|max:500',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->hasFile('foto_profil')) {
            $path = public_path('uploads/avatars/');
            $file = $request->file('foto_profil');
            $nama_file = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            if ($user->foto_profil) {
                File::delete($path . $user->foto_profil);
            }
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