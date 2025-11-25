<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BarangDonasi;
use App\Models\User;

class PageController extends Controller
{
    public function welcome()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('welcome');
    }

    public function home()
{
    $stats = [
    'barang_didonasikan' => BarangDonasi::count(),
    'barang_diterima' => BarangDonasi::where('status', 'Dipesan')->count(), // ✅ pakai "Dipesan"
    'pengguna_aktif' => User::count(),
    'kota' => BarangDonasi::distinct('lokasi')->count('lokasi'),
];

    $barangTerbaru = BarangDonasi::where('status', 'Tersedia')->latest()->take(10)->get();

    // === TAMBAHAN BARU ===
    $favoriteIds = [];
    if (Auth::check()) {
        $favoriteIds = Auth::user()->favorites()->pluck('barang_donasis.id')->toArray();
    }
    // === AKHIR TAMBAHAN ===

    return view('home', compact('stats', 'barangTerbaru', 'favoriteIds'));
}

    public function about()
    {
        return view('about');
    }
}
