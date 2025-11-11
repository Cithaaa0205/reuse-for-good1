<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Pastikan ini ada

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Pastikan ini ada

    protected $fillable = [
// ... (isi $fillable Anda)
// ... ('foto_profil', 'deskripsi',)
// ...
        'nama_lengkap',
        'email',
        'nomor_telepon',
        'username',
        'password',
        'role',
        'foto_profil',
        'deskripsi',
    ];

    protected $hidden = [
// ... (isi $hidden Anda)
// ...
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

    // === TAMBAHAN BARU ===

    /**
     * Relasi: Barang yang diterima user (status Selesai)
     */
    public function barangDiterima()
    {
        return $this->hasManyThrough(
            BarangDonasi::class,
            RequestBarang::class,
            'penerima_id', // Foreign key di tabel request_barangs
            'id',          // Foreign key di tabel barang_donasis
            'id',          // Local key di tabel users
            'barang_donasi_id' // Local key di tabel request_barangs
        )->where('request_barangs.status', 'Selesai'); // Filter hanya yang Selesai
    }

    /**
     * Relasi: Barang yang difavoritkan user
     */
    public function favorites()
    {
        return $this->belongsToMany(BarangDonasi::class, 'favorites', 'user_id', 'barang_donasi_id')->withTimestamps();
    }
}