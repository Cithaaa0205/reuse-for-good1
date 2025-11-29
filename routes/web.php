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
use App\Http\Controllers\LocationController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureUserHasLocation;   // ← TAMBAHAN INI

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
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
    // Auth
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // ==============================
    // SETUP LOKASI (TIDAK BOLEH KENA MIDDLEWARE hasLocation)
    // ==============================
    Route::get('setup-lokasi', [LocationController::class, 'create'])->name('lokasi.create');
    Route::post('setup-lokasi', [LocationController::class, 'store'])->name('lokasi.store');

    // ==============================
    // ROUTE YANG BUTUH LOKASI USER
    // ==============================
    Route::middleware(EnsureUserHasLocation::class)->group(function () {   // ← PAKAI CLASS, BUKAN 'hasLocation'

        // Halaman utama & about
        Route::get('home', [PageController::class, 'home'])->name('home');
        Route::get('about', [PageController::class, 'about'])->name('about');

        // ==============================
        // BARANG DONASI
        // ==============================
        Route::prefix('barang')->name('barang.')->group(function () {
            Route::get('/', [BarangDonasiController::class, 'index'])->name('index');
            Route::get('create', [BarangDonasiController::class, 'create'])->name('create');
            Route::post('/', [BarangDonasiController::class, 'store'])->name('store');

            Route::get('{barang}', [BarangDonasiController::class, 'show'])->name('show');
            Route::delete('{barang}', [BarangDonasiController::class, 'destroy'])->name('destroy');
        });

        // PROFIL
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('edit', [ProfileController::class, 'edit'])->name('edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
            Route::get('{user:username}', [ProfileController::class, 'show'])->name('show');
        });

        // FAVORIT
        Route::post('favorite/{id}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

        // CHAT
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/', [ChatController::class, 'index'])->name('index');
            Route::get('{user}', [ChatController::class, 'show'])->name('show');
            Route::post('{user}', [ChatController::class, 'store'])->name('store');
        });

        // REQUEST BARANG
        Route::post('request/{barang}', [RequestBarangController::class, 'store'])->name('request.store');
        Route::get('kelola-pengajuan', [RequestBarangController::class, 'index'])->name('request.manage');
        Route::patch('request/{requestBarang}/{status}', [RequestBarangController::class, 'updateStatus'])->name('request.updateStatus');

        // LOKASI USER (simpan latitude/longitude dari geolocation JS)
        Route::post('/save-location', function (\Illuminate\Http\Request $request) {
            auth()->user()->update([
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            return response()->json(['success' => true]);
        })->name('location.save');
    });
});

// ADMIN PANEL
Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('users', [AdminController::class, 'indexUsers'])->name('users.index');
        Route::get('users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::patch('users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        Route::get('barang', [AdminController::class, 'indexBarang'])->name('barang.index');
        Route::delete('barang/{barang}', [AdminController::class, 'destroyBarang'])->name('barang.destroy');
    });
