<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangDonasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RequestBarangController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware; 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/', [PageController::class, 'welcome'])->name('welcome');
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

// Rute untuk yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('home', [PageController::class, 'home'])->name('home');
    Route::get('about', [PageController::class, 'about'])->name('about');

    // Rute Barang Donasi
    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/', [BarangDonasiController::class, 'index'])->name('index'); // Etalase
        Route::get('create', [BarangDonasiController::class, 'create'])->name('create'); // Form donasi
        Route::post('/', [BarangDonasiController::class, 'store'])->name('store'); // Simpan donasi
        // Gunakan {barangDonasi} (Route Model Binding)
        Route::get('{barangDonasi}', [BarangDonasiController::class, 'show'])->name('show'); // Detail barang
        Route::delete('{barangDonasi}', [BarangDonasiController::class, 'destroy'])->name('destroy'); // Hapus donasi
    });
    
    // Rute Profil
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update'); // PATCH lebih umum untuk update
        Route::get('{user:username}', [ProfileController::class, 'show'])->name('show');
    });

    // Rute Favorit
    // Gunakan {barangDonasi} (Route Model Binding)
    Route::middleware('auth')->group(function () {
    // ...
    Route::post('favorite/{barangDonasi}', [FavoriteController::class, 'toggle'])
        ->name('favorite.toggle');
});
    // === RUTE CHAT BARU ===
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index'); // Daftar chat
        // Gunakan {user} (Route Model Binding)
        Route::get('{user}', [ChatController::class, 'show'])->name('show'); // Ruang chat
        Route::post('{user}', [ChatController::class, 'store'])->name('store'); // Kirim pesan
    });
    // === AKHIR RUTE CHAT ===

    // Rute untuk Request Barang
    // 1. Rute untuk mengajukan permintaan (Penerima)
    Route::post('request/{barangDonasi}', [RequestBarangController::class, 'store'])->name('request.store');

    // 2. Rute untuk melihat daftar pengajuan masuk (Pendonasi) - BARU
    Route::get('kelola-pengajuan', [RequestBarangController::class, 'index'])->name('request.manage');

    // 3. Rute untuk menerima/menolak pengajuan (Pendonasi) - BARU
    Route::patch('request/{requestBarang}/{status}', [RequestBarangController::class, 'updateStatus'])->name('request.updateStatus');

    // routes/web.php
});

// Rute khusus Admin Panel
Route::middleware(['auth', AdminMiddleware::class])   // 👈 BUKAN 'admin' lagi
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Manajemen Pengguna
        Route::get('users', [AdminController::class, 'indexUsers'])->name('users.index');
        Route::get('users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::patch('users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        
        // Manajemen Barang Donasi
        Route::get('barang', [AdminController::class, 'indexBarang'])->name('barang.index');
        Route::delete('barang/{barang}', [AdminController::class, 'destroyBarang'])->name('barang.destroy');
    });





