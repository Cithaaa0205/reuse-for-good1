<?php

namespace App\Http\Controllers;

use App\Models\BarangDonasi;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(BarangDonasi $barangDonasi)
    {
        $user = Auth::user();

        $sudahFavorit = $user->favorites()
            ->where('barang_donasi_id', $barangDonasi->id)
            ->exists();

        if ($sudahFavorit) {
            $user->favorites()->detach($barangDonasi->id);
            return back()->with('success', 'Barang dihapus dari favorit.');
        }

        $user->favorites()->attach($barangDonasi->id);
        return back()->with('success', 'Barang ditambahkan ke favorit!');
    }
}
