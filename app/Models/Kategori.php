<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini

class Kategori extends Model
{
    use HasFactory; // Tambahkan ini

    protected $fillable = ['nama_kategori', 'slug'];

    // Relasi: Kategori memiliki banyak barang donasi
    public function barangDonasis()
    {
        return $this->hasMany(BarangDonasi::class, 'kategori_id');
    }
}