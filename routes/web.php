<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangDonasiController;
use App\Http\Controllers\RequestBarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController; // TAMBAHKAN INI

// Halaman Awal
// ... (rute welcome tidak berubah)
// ...
Route::get('/', [PageController::class, 'welcome'])->name('welcome');

// Rute Autentikasi (Guest)
// ... (rute guest tidak berubah)
// ...
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

// Rute yang Memerlukan Login (Auth)
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Beranda
    Route::get('home', [PageController::class, 'home'])->name('home');

    // Rute Barang Donasi
    Route::get('barang', [BarangDonasiController::class, 'index'])->name('barang.index');
    Route::get('barang/create', [BarangDonasiController::class, 'create'])->name('barang.create');
    Route::post('barang', [BarangDonasiController::class, 'store'])->name('barang.store');
    Route::get('barang/{id}', [BarangDonasiController::class, 'show'])->name('barang.show');
    Route::delete('barang/{id}', [BarangDonasiController::class, 'destroy'])->name('barang.destroy'); // <-- RUTE BARU HAPUS

    // Rute Request Barang
    Route::post('request', [RequestBarangController::class, 'store'])->name('request.store');

    // Rute Halaman "Tentang Kami"
    Route::get('about', [PageController::class, 'about'])->name('about');
    
    // Rute Profil
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    // Catatan: Rute 'show' ditaruh di luar auth agar bisa dilihat publik
    
    // === RUTE FAVORIT BARU ===
    Route::post('favorite/{id}', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('favorite/{id}', [FavoriteController::class, 'destroy'])->name('favorite.destroy');
});

// Rute Profil (Publik - di luar middleware auth)
// Rute ini harus paling bawah agar tidak bentrok dengan 'profile/edit'
Route::get('profile/{username}', [ProfileController::class, 'show'])->name('profile.show');