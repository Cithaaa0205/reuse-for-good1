<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Status user
    public const STATUS_AKTIF     = 'aktif';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_BANNED    = 'banned';

    /**
     * Default attribute untuk user baru.
     */
    protected $attributes = [
        'status' => self::STATUS_AKTIF,
    ];

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

        // Koordinat dari geolocation
        'latitude',
        'longitude',

        // Status keamanan
        'status',
        'status_reason',
        'status_changed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status_changed_at' => 'datetime',
    ];

    // =======================
    // Relasi Donasi & Request
    // =======================

    /**
     * Barang yang didonasikan oleh user ini.
     */
    public function barangDonasis()
    {
        return $this->hasMany(BarangDonasi::class, 'donatur_id');
    }

    /**
     * Request yang dibuat oleh user ini.
     */
    public function requestBarangs()
    {
        return $this->hasMany(RequestBarang::class, 'penerima_id');
    }

    /**
     * Barang yang berhasil diterima oleh user ini.
     */
    public function barangDiterima()
    {
        return $this->belongsToMany(BarangDonasi::class, 'request_barangs', 'penerima_id', 'barang_donasi_id')
            ->wherePivot('status', 'Disetujui')
            ->withTimestamps();
    }

    /**
     * Barang yang difavoritkan oleh user ini.
     */
    public function favorites()
    {
        return $this->belongsToMany(BarangDonasi::class, 'favorites', 'user_id', 'barang_donasi_id')
            ->withTimestamps();
    }

    // =======================
    // Relasi Chat
    // =======================

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // =======================
    // Relasi Laporan
    // =======================

    /**
     * Laporan yang dibuat oleh user ini (sebagai pelapor).
     */
    public function reportsMade()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    /**
     * Laporan yang ditujukan ke user ini (reported_type = user).
     */
    public function reportsAsTarget()
    {
        return $this->hasMany(Report::class, 'reported_id')
            ->where('reported_type', Report::TYPE_USER);
    }

    // =======================
    // Helper status
    // =======================

    public function isAktif(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    public function isBanned(): bool
    {
        return $this->status === self::STATUS_BANNED;
    }
}
