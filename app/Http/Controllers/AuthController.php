<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login (semua user: admin & non-admin)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Cek status keamanan
            if ($user->isSuspended()) {
                Auth::logout();

                return back()
                    ->withErrors([
                        'email' => 'Akun Anda sedang disuspend oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.',
                    ])
                    ->onlyInput('email');
            }

            if ($user->isBanned()) {
                Auth::logout();

                return back()
                    ->withErrors([
                        'email' => 'Akun Anda telah diblokir permanen oleh admin.',
                    ])
                    ->onlyInput('email');
            }

            // Kalau admin → lempar ke halaman admin
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Selain admin → ke home biasa
            return redirect()->route('home');
        }

        // Kalau gagal login
        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->onlyInput('email');
    }

    // Tampilkan form registrasi
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Proses registrasi
    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap'   => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nomor_telepon'  => ['required', 'string', 'max:15'],
            'username'       => ['required', 'string', 'max:255', 'unique:users'],
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'nama_lengkap'  => $request->nama_lengkap,
            'email'         => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'role'          => 'user', // default
            'status'        => User::STATUS_AKTIF,
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Registrasi berhasil! Silakan masuk dengan akun baru Anda.');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
