<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Tambahkan HasFactory

    protected $fillable = [
        'nama_lengkap',
        'email',
        'nomor_telepon',
        'username',
        'password',
        'role','foto_profil', 
        'deskripsi',   
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi: User (Donatur) bisa memiliki banyak barang donasi
    public function barangDonasis()
    {
        return $this->hasMany(BarangDonasi::class, 'donatur_id');
    }

    // Relasi: User (Penerima) bisa memiliki banyak permintaan barang
    public function requestBarangs()
    {
        return $this->hasMany(RequestBarang::class, 'penerima_id');
    }
}