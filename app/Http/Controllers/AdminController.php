<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BarangDonasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // =========================
    // Manajemen Pengguna
    // =========================
    public function indexUsers()
    {
        // Ambil semua pengguna (boleh nanti diubah ke paginate kalau mau)
        $users = User::all();

        // View: resources/views/admin/user/index.blade.php
        return view('admin.user.index', compact('users'));
    }

    public function editUser(User $user)
    {
        // View: resources/views/admin/user/edit.blade.php
        return view('admin.user.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'username'       => 'required|string|max:255|alpha_dash|unique:users,username,' . $user->id,
            'nomor_telepon'  => 'nullable|string|max:15',
            'role'           => 'required|in:donatur,admin',
        ]);

        $user->update([
            'nama_lengkap'  => $request->nama_lengkap,
            'username'      => $request->username,
            'nomor_telepon' => $request->nomor_telepon,
            'role'          => $request->role,
        ]);

        // ⬇️ perbaikan: pakai nama route yang benar (admin.users.index)
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroyUser(User $user)
    {
        // Jangan izinkan admin menghapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus!');
    }

    // =========================
    // Manajemen Barang Donasi
    // =========================
    public function indexBarang()
    {
        $barang = BarangDonasi::with('kategori', 'donatur')->latest()->get();

        // View: resources/views/admin/barang/index.blade.php
        return view('admin.barang.index', compact('barang'));
    }

    public function destroyBarang(BarangDonasi $barang)
    {
        $barang->delete();

        return back()->with('success', 'Barang berhasil dihapus!');
    }
}
