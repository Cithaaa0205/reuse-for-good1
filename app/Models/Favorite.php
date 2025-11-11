<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'barang_donasi_id',
    ];

    /**
     * Relasi ke BarangDonasi (jika diperlukan)
     */
    public function barangDonasi()
    {
        return $this->belongsTo(BarangDonasi::class);
    }

    /**
     * Relasi ke User (jika diperlukan)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}