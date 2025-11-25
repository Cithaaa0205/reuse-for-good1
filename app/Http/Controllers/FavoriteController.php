<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\BarangDonasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;

class FavoriteController extends Controller
{
    /**
     * Toggle Favorite:
     * - Kalau belum ada → buat record baru
     * - Kalau sudah ada → hapus (unfavorite)
     */
    public function toggle(BarangDonasi $barangDonasi)
    {
        $userId = Auth::id();

        if (! $userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Logging sementara untuk debug: siapa klik like dan barang apa
        Log::info('Favorite toggle called', ['user_id' => $userId, 'barang_id' => $barangDonasi->id]);

        $existingFavorite = Favorite::where('user_id', $userId)
            ->where('barang_donasi_id', $barangDonasi->id)
            ->first();

        if ($existingFavorite) {
            // Unfavorite
            $existingFavorite->delete();
            Log::info('Favorite removed', ['user_id' => $userId, 'barang_id' => $barangDonasi->id]);
            return back()->with('success', 'Barang dihapus dari favorit.');
        }

        // Favorite baru
        Favorite::create([
            'user_id'          => $userId,
            'barang_donasi_id' => $barangDonasi->id,
        ]);
        Log::info('Favorite created', ['user_id' => $userId, 'barang_id' => $barangDonasi->id]);

        return back()->with('success', 'Barang ditambahkan ke favorit!');
    }
}
