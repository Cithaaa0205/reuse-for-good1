<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangDonasi extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi mass-assignment.
     */
    protected $fillable = [
        'donatur_id',
        'kategori_id',
        'nama_barang',
        'deskripsi',
        'kondisi',
        'provinsi',
        'kabupaten',
        'foto_barang_utama',
        'foto_barang_lainnya',
        'catatan_pengambilan',
        'status',
        // Kalau nanti kamu tambahkan kolom latitude/longitude untuk barang,
        // tinggal aktifkan:
        // 'latitude',
        // 'longitude',
    ];

    /**
     * Relasi: Barang donasi dimiliki oleh satu User (Donatur).
     */
    public function donatur()
    {
        return $this->belongsTo(User::class, 'donatur_id');
    }

    /**
     * Relasi: Barang donasi termasuk dalam satu Kategori.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi: Barang donasi bisa memiliki banyak permintaan.
     */
    public function requestBarangs()
    {
        return $this->hasMany(RequestBarang::class, 'barang_donasi_id');
    }
}
