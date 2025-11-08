<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini

class RequestBarang extends Model
{
    use HasFactory; // Tambahkan ini

    protected $fillable = [
        'penerima_id',
        'barang_donasi_id',
        'alasan_permintaan',
        'status',
    ];

    // Relasi: Permintaan dimiliki oleh satu User (Penerima)
    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }

    // Relasi: Permintaan merujuk ke satu Barang Donasi
    public function barangDonasi()
    {
        return $this->belongsTo(BarangDonasi::class, 'barang_donasi_id');
    }
}