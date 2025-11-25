<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek jika admin login dengan email dan password tertentu
        if ($request->email === 'AdminRFG@gmail.com' && $request->password === 'reuseforgood') {
            $adminUser = User::where('email', $request->email)->first();
            
            // Jika admin ditemukan dan autentikasi berhasil
            if ($adminUser && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('admin.users.index');
            }
        }

        // Jika pengguna biasa login
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->intended('/');
        }

        // Jika login gagal
        return back()->with('error', 'Email atau password salah.');
    }
}
