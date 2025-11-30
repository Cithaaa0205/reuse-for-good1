<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // ======================
    // STATUS LAPORAN
    // ======================
    public const STATUS_BARU     = 'baru';
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_SELESAI  = 'selesai';

    // ======================
    // TIPE TARGET LAPORAN
    // ======================
    public const TYPE_USER   = 'user';
    public const TYPE_BARANG = 'barang';
    public const TYPE_PESAN  = 'pesan';

    protected $fillable = [
        'reporter_id',
        'reported_type', // user / barang / pesan
        'reported_id',
        'reason',
        'status',
        'handled_by',
        'handled_at',
        'admin_notes',
    ];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    // ======================
    // RELASI
    // ======================

    // User yang melaporkan
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // Admin yang menangani
    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    // Helper untuk ambil model target laporan
    public function getTargetModelAttribute()
    {
        switch ($this->reported_type) {
            case self::TYPE_USER:
                return User::find($this->reported_id);

            case self::TYPE_BARANG:
                return BarangDonasi::find($this->reported_id);

            case self::TYPE_PESAN:
                return Message::find($this->reported_id);

            default:
                return null;
        }
    }
}
