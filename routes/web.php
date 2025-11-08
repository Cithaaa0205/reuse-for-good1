<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangDonasiController;
use App\Http\Controllers\RequestBarangController;
use App\Http\Controllers\ProfileController; // TAMBAHKAN INI

// Halaman Awal
Route::get('/', [PageController::class, 'welcome'])->name('welcome');

// Rute Autentikasi (Guest)
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

    // Rute Request Barang
    Route::post('request', [RequestBarangController::class, 'store'])->name('request.store');

    // === RUTE BARU ===
    // Rute Halaman "Tentang Kami"
    Route::get('about', [PageController::class, 'about'])->name('about');
    
    // Rute Profil
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/{username}', [ProfileController::class, 'show'])->name('profile.show');
});