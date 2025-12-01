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
        // Statistik global (boleh menghitung semua, termasuk yang hidden)
        $stats = [
            'barang_didonasikan' => BarangDonasi::count(),
            'barang_diterima'    => BarangDonasi::where('status', 'Dipesan')->count(),
            'pengguna_aktif'     => User::count(),
            'kota'               => BarangDonasi::distinct('kabupaten')->count('kabupaten'),
        ];

        $user = Auth::user();

        // ==== Rekomendasi barang berdasarkan lokasi user ====
        // HANYA barang publik (Tersedia + tidak di-hide)
        $barangQuery = BarangDonasi::publicVisible();

        if ($user && ($user->provinsi || $user->kabupaten)) {
            // Prioritaskan barang di kabupaten & provinsi yang sama,
            // lalu provinsi sama, lalu sisanya
            if ($user->kabupaten && $user->provinsi) {
                $barangQuery->orderByRaw("
                    CASE 
                        WHEN kabupaten = ? AND provinsi = ? THEN 0
                        WHEN provinsi = ? THEN 1
                        ELSE 2
                    END,
                    created_at DESC
                ", [
                    $user->kabupaten,
                    $user->provinsi,
                    $user->provinsi,
                ]);
            } elseif ($user->provinsi) {
                $barangQuery->orderByRaw("
                    CASE 
                        WHEN provinsi = ? THEN 0
                        ELSE 1
                    END,
                    created_at DESC
                ", [
                    $user->provinsi,
                ]);
            } else {
                $barangQuery->orderByRaw("
                    CASE 
                        WHEN kabupaten = ? THEN 0
                        ELSE 1
                    END,
                    created_at DESC
                ", [
                    $user->kabupaten,
                ]);
            }
        } else {
            // User belum punya lokasi → pakai terbaru biasa
            $barangQuery->latest();
        }

        // Barang terbaru untuk section "Barang Terbaru di Sekitar Anda"
        $barangTerbaru = $barangQuery->take(10)->get();

        // Favorite items untuk user login
        $favoriteIds = [];
        if ($user) {
            $favoriteIds = $user
                ->favorites()
                ->pluck('barang_donasis.id')
                ->toArray();
        }

        // Label lokasi user (bisa dipakai di view)
        $userLocationLabel = null;
        if ($user && ($user->kabupaten || $user->provinsi)) {
            $userLocationLabel = trim(
                ($user->kabupaten ? $user->kabupaten . ', ' : '') .
                ($user->provinsi ?? '')
            );
        }

        return view('home', [
            'stats'             => $stats,
            'barangTerbaru'     => $barangTerbaru,
            'favoriteIds'       => $favoriteIds,
            'userLocationLabel' => $userLocationLabel,
        ]);
    }

    public function about()
    {
        return view('about');
    }
}
