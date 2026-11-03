<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'kode_pesanan',
        'id_user',
        'tanggal_pesanan',
        'total_harga',
        'status_pesanan',
        'status_laundry',
        'metode_pembayaran',
        'alamat_penjemputan',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pesanan' => 'datetime',
        'total_harga' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    public function layanan()
    {
        return $this->belongsToMany(
            Layanan::class,
            'pesanan_layanan',
            'id_pesanan',
            'id_layanan'
        )->withPivot(
            'id_detail',
            'jumlah',
            'harga_satuan',
            'subtotal'
        )->withTimestamps();
    }

    public function pembayaran()
    {
        return $this->hasOne(
            Pembayaran::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function pengiriman()
    {
        return $this->hasOne(
            Pengiriman::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function ulasan()
    {
        return $this->hasOne(
            Ulasan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function penggunaanMesin()
    {
        return $this->hasMany(
            PenggunaanMesin::class,
            'id_pesanan',
            'id_pesanan'
        );
    }
}
