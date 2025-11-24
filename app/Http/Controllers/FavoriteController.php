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
     * Tambah barang ke favorit
     */
    public function store(Request $request, $barangId)
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
                                    ->exists();

        if ($existingFavorite) {
            return back()->with('info', 'Barang ini sudah ada di favorit Anda.');
        }

        // Tambahkan ke favorit
        Favorite::create([
            'user_id' => $userId,
            'barang_donasi_id' => $barangId,
        ]);

        return back()->with('success', 'Barang ditambahkan ke favorit!');
    }

    /**
     * Hapus barang dari favorit
     */
    public function destroy($barangId)
    {
        $userId = Auth::id();
        
        $favorite = Favorite::where('user_id', $userId)
                            ->where('barang_donasi_id', $barangId)
                            ->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Barang dihapus dari favorit.');
        }

        return back()->with('error', 'Barang tidak ditemukan di favorit Anda.');
    }
}