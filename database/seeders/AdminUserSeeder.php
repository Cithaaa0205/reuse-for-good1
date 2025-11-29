<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
    ['email' => 'admin@reuseforgood.com'],
    [
        'nama_lengkap'  => 'Admin ReuseForGood',
        'username'      => 'admin',
        'nomor_telepon' => '08123456789',
        'password'      => Hash::make('admin123'),
        'role'          => 'admin',
    ]
);
    }
}