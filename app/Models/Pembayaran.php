<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'kode_pembayaran',
        'id_pesanan',
        'id_user',
        'jumlah_bayar',
        'metode_pembayaran',
        'status_pembayaran',
        'tanggal_bayar',
        'bukti_transfer',
        'verifikator',

        // Data Midtrans
        'midtrans_order_id',
        'midtrans_transaction_id',
        'qr_code_url',
        'midtrans_transaction_status',
        'waktu_kedaluwarsa',
        'midtrans_response',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
<<<<<<< HEAD
        'jumlah_bayar' => 'decimal:2',
=======
        'waktu_kedaluwarsa' => 'datetime',
        'midtrans_response' => 'array',
>>>>>>> 9da5eb4 (Update fitur pembayaran QRIS ArsyClean)
    ];

    public function pesanan()
    {
        return $this->belongsTo(
            Pesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }
}
