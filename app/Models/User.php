<?php

namespace App\Models;

// Kita HAPUS 'use Laravel\Sanctum\HasApiTokens;' dari sini

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // Kita HAPUS 'HasApiTokens' dari sini
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'email',
        'nomor_telepon',
        'username',
        'password',
        'role',
        'foto_profil', // <-- Tambahan dari Edit Profil
        'deskripsi',   // <-- Tambahan dari Edit Profil
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
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

    // === RELASI BARU UNTUK FITUR PROFIL & FAVORIT ===

    /**
     * Relasi untuk mengambil barang-barang yang TELAH DITERIMA oleh user.
     * (Asumsi status 'Selesai' di tabel request_barangs)
     */
    public function barangDiterima()
    {
        return $this->hasManyThrough(
            BarangDonasi::class,    // Model tujuan
            RequestBarang::class,   // Model perantara
            'penerima_id',          // Foreign key di 'request_barangs'
            'id',                   // Foreign key di 'barang_donasis'
            'id',                   // Local key di 'users'
            'barang_donasi_id'      // Local key di 'request_barangs'
        )->where('request_barangs.status', 'Selesai'); // Ganti 'Selesai' jika statusnya beda
    }

    /**
     * Relasi untuk mengambil barang-barang yang difavoritkan user.
     * (Relasi Many-to-Many)
     */
    public function favorites()
    {
        return $this->belongsToMany(BarangDonasi::class, 'favorites', 'user_id', 'barang_donasi_id');
    }

    // === RELASI BARU UNTUK FITUR CHAT ===

    /**
     * Pesan yang dikirim oleh user ini.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Pesan yang diterima oleh user ini.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}