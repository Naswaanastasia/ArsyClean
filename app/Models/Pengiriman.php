<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';

    protected $fillable = [
        'kode_pengiriman',
        'id_pesanan',
        'nama_kurir',
        'no_hp_kurir',
        'kendaraan',
        'no_resi',
        'alamat_tujuan',
        'status_pengiriman',
        'tanggal_kirim',
    ];

    protected $casts = [
        'tanggal_kirim' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
