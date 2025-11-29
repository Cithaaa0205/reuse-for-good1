<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'nomor_telepon',
        'username',
        'password',
        'role',
        'foto_profil',
        'deskripsi',

        // Lokasi user (dipakai untuk rekomendasi)
        'provinsi',
        'kabupaten',

        // Koordinat dari geolocation (opsional, tapi sudah dipakai di route location.save)
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 1. Barang yang didonasikan OLEH user ini
    public function barangDonasis()
    {
        return $this->hasMany(BarangDonasi::class, 'donatur_id');
    }

    // 2. Request yang dibuat OLEH user ini
    public function requestBarangs()
    {
        return $this->hasMany(RequestBarang::class, 'penerima_id');
    }

    // 3. Barang yang berhasil DITERIMA oleh user ini
    // (mengambil barang dari tabel request_barangs dimana status = 'Disetujui')
    public function barangDiterima()
    {
        return $this->belongsToMany(BarangDonasi::class, 'request_barangs', 'penerima_id', 'barang_donasi_id')
                    ->wherePivot('status', 'Disetujui')
                    ->withTimestamps();
    }

    // 4. Barang yang DIFAVORITKAN oleh user ini
    public function favorites()
    {
        return $this->belongsToMany(BarangDonasi::class, 'favorites', 'user_id', 'barang_donasi_id')
                    ->withTimestamps();
    }

    // Relasi chat
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
