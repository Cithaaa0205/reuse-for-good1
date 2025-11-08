<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use App\Models\BarangDonasi;
use App\Models\User;

class PageController extends Controller
{
    /**
     * Halaman Awal (Welcome)
     * Ini adalah fungsi yang dipanggil untuk http://127.0.0.1:8000/
     */
    public function welcome()
    {
        // Cek apakah user sudah login
        if (Auth::check()) {
            // Jika sudah, lempar ke halaman 'home'
            return redirect()->route('home');
        }
        // Jika belum, tampilkan halaman 'welcome'
        return view('welcome');
    }

    /**
     * Halaman Beranda (Home)
     * Ini adalah halaman setelah user login
     */
    public function home()
    {
        // Ambil data statistik asli
        $stats = [
            'barang_didonasikan' => BarangDonasi::count(),
            'pengguna_aktif' => User::count(),
            'kota' => BarangDonasi::distinct('lokasi')->count('lokasi'),
            'tingkat_keberhasilan' => 88 // Ini bisa jadi logika kompleks nanti
        ];
        $barangTerbaru = BarangDonasi::where('status', 'Tersedia')->latest()->take(10)->get();

        return view('home', compact('stats', 'barangTerbaru'));
    }

    /**
     * Halaman Tentang Kami
     */
    public function about()
    {
        return view('about'); // Akan memanggil file resources/views/about.blade.php
    }
}