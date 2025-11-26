<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\BarangDonasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    /**
     * Toggle Favorite:
     * - Jika belum ada -> buat record baru
     * - Jika sudah ada -> hapus
     */
    public function toggle($barangId)
    {
        $userId = Auth::id();

        if (! $userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Pastikan barang valid
        $barang = BarangDonasi::findOrFail($barangId);

        // Logging debugging
        Log::info('Favorite toggle clicked', ['user_id' => $userId, 'barang_id' => $barang->id]);

        // Cek apakah sudah jadi favorit
        $existingFavorite = Favorite::where('user_id', $userId)
            ->where('barang_donasi_id', $barang->id)
            ->first();

        if ($existingFavorite) {
            // Unfavorite
            $existingFavorite->delete();
            Log::info('Favorite removed', ['user_id' => $userId, 'barang_id' => $barang->id]);
            return back()->with('success', 'Barang dihapus dari favorit.');
        }

        // Simpan favorit baru
        Favorite::create([
            'user_id' => $userId,
            'barang_donasi_id' => $barang->id
        ]);

        Log::info('Favorite added', ['user_id' => $userId, 'barang_id' => $barang->id]);

        return back()->with('success', 'Barang ditambahkan ke favorit!');
    }
}
