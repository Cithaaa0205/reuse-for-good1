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
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminReportController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureUserHasLocation;
use App\Http\Middleware\CheckUserStatus;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================
// RUTE UNTUK TAMU (GUEST)
// ==========================
Route::middleware('guest')->group(function () {
    Route::get('/', [PageController::class, 'welcome'])->name('welcome');

    // Auth
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    // Lupa password
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

// ==========================
// RUTE UNTUK USER LOGIN
// (dicek status: aktif / suspended / banned)
// ==========================
Route::middleware(['auth', CheckUserStatus::class])->group(function () {

    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // SETUP LOKASI (tidak kena EnsureUserHasLocation)
    Route::get('setup-lokasi', [LocationController::class, 'create'])->name('lokasi.create');
    Route::post('setup-lokasi', [LocationController::class, 'store'])->name('lokasi.store');

    // ==============================
    // ROUTE YANG WAJIB PUNYA LOKASI
    // ==============================
    Route::middleware(EnsureUserHasLocation::class)->group(function () {

        // Halaman utama & about
        Route::get('home', [PageController::class, 'home'])->name('home');
        Route::get('about', [PageController::class, 'about'])->name('about');

        // --------------------------
        // BARANG DONASI
        // --------------------------
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

        // Simpan lokasi (geolocation) user
        Route::post('/save-location', function (\Illuminate\Http\Request $request) {
            auth()->user()->update([
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            return response()->json(['success' => true]);
        })->name('location.save');

        // ===========================
        // LAPORAN (REPORT) dari user
        // ===========================
        Route::post('lapor/barang/{barang}', [ReportController::class, 'reportBarang'])->name('report.barang');
        Route::post('lapor/user/{user}', [ReportController::class, 'reportUser'])->name('report.user');
        Route::post('lapor/message/{message}', [ReportController::class, 'reportMessage'])->name('report.message');
    });
});

// ADMIN PANEL
Route::middleware(['auth', CheckUserStatus::class, AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Redirect /admin ke dashboard
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        })->name('index');

        // Dashboard admin
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Manajemen pengguna
        Route::get('users', [AdminController::class, 'indexUsers'])->name('users.index');
        Route::get('users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::patch('users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // Aksi status user
        Route::post('users/{user}/suspend', [AdminController::class, 'suspendUser'])->name('users.suspend');
        Route::post('users/{user}/ban', [AdminController::class, 'banUser'])->name('users.ban');
        Route::post('users/{user}/restore', [AdminController::class, 'restoreUser'])->name('users.restore');

        // ======================
        // Manajemen barang donasi
        // ======================
        Route::get('barang', [AdminController::class, 'indexBarang'])->name('barang.index');

        // toggle visibilitas (hide / unhide)
        Route::post('barang/{barang}/hide', [AdminController::class, 'hideBarang'])->name('barang.hide');
        Route::post('barang/{barang}/unhide', [AdminController::class, 'unhideBarang'])->name('barang.unhide');

        // hapus permanen
        Route::delete('barang/{barang}', [AdminController::class, 'destroyBarang'])->name('barang.destroy');

        // ===========================
        // ADMIN: Laporan (Report)
        // ===========================
        Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
        Route::patch('reports/{report}/status', [AdminReportController::class, 'updateStatus'])->name('reports.updateStatus');
        Route::post('reports/{report}/suspend-user', [AdminReportController::class, 'suspendUser'])->name('reports.suspendUser');
        Route::post('reports/{report}/hide-barang', [AdminReportController::class, 'hideBarang'])->name('reports.hideBarang');
    });

  
