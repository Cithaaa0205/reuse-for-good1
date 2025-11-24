<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\BarangDonasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class FavoriteController extends Controller
{
    /**
     * Toggle Favorite: Jika belum ada -> tambah, Jika sudah ada -> hapus.
     * Fungsi ini dipanggil oleh rute 'favorite.toggle'
     */
    public function toggle(Request $request, $barangId)
    {
        $userId = Auth::id();

        // Cek apakah barang ada
        $barang = BarangDonasi::find($barangId);
        if (!$barang) {
            return back()->with('error', 'Barang tidak ditemukan.');
        }

        // Cek apakah sudah difavoritkan
        $existingFavorite = Favorite::where('user_id', $userId)
                                    ->where('barang_donasi_id', $barangId)
                                    ->first();

        if ($existingFavorite) {
            // Jika sudah ada, hapus (Unfavorite)
            $existingFavorite->delete();
            // Kita kembalikan 'success' agar notifikasi muncul
            return back()->with('success', 'Barang dihapus dari favorit.');
        } else {
            // Jika belum ada, tambahkan (Favorite)
            Favorite::create([
                'user_id' => $userId,
                'barang_donasi_id' => $barangId,
            ]);
            return back()->with('success', 'Barang ditambahkan ke favorit!');
        }
    }
}