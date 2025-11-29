<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email ini tidak terdaftar.'
        ]);

        // 2. Cari User & Generate Password
        $user = User::where('email', $request->email)->first();
        $newPassword = Str::random(8); 

        // 3. Update Database
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // 4. Kirim Email (Silent fail jika error)
        try {
            Mail::raw("Password baru: $newPassword", function ($m) use ($user) {
                $m->to($user->email)->subject('Password Baru');
            });
        } catch (\Exception $e) {}

        // 5. [UPDATE DISINI] Kirim data password terpisah session
        // Kita kirim variable 'generated_password' ke view
        return back()->with('generated_password', $newPassword);
    }
}