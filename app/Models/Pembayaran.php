<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $primaryKey = 'id_pembayaran';

    // Tabel pembayaran tidak memiliki kolom updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'kode_pembayaran',
        'id_pesanan',
        'metode_pembayaran',
        'status_pembayaran',
        'jumlah_pembayaran',
        'tanggal_pembayaran',
        'diverifikasi_oleh',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'jumlah_pembayaran' => 'decimal:2',
    ];

    public function pesanan()
    {
        return $this->belongsTo(
            Pesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }
}
