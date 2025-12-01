<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'is_hidden',
        // 'latitude',
        // 'longitude',
    ];

    protected $casts = [
        'is_hidden' => 'boolean',
    ];

    /**
     * Scope: hanya barang yang boleh tampil ke publik
     * (status Tersedia dan tidak di-hide admin).
     */
    public function scopePublicVisible($query)
    {
        return $query->where('status', 'Tersedia')
                     ->where('is_hidden', false);
    }

    /**
     * Barang dimiliki oleh satu user (donatur).
     */
    public function donatur()
    {
        return $this->belongsTo(User::class, 'donatur_id');
    }

    /**
     * Barang termasuk dalam satu kategori.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Barang bisa memiliki banyak permintaan.
     */
    public function requestBarangs()
    {
        return $this->hasMany(RequestBarang::class, 'barang_donasi_id');
    }

    /**
     * Laporan yang ditujukan ke barang ini.
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'reported_id')
            ->where('reported_type', Report::TYPE_BARANG);
    }
}
