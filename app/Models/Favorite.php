<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'barang_donasi_id',
    ];

    public function barangDonasi()
    {
        return $this->belongsTo(BarangDonasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Barang yang DIFAVORITKAN oleh user ini
public function favorites()
{
    return $this->belongsToMany(BarangDonasi::class, 'favorites', 'user_id', 'barang_donasi_id')
                ->withTimestamps();
}

}
