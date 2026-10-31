<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenggunaanMesin extends Model
{
    protected $table = 'penggunaan_mesin';
    protected $primaryKey = 'id_penggunaan';

    protected $fillable = [
        'id_mesin',
        'id_pesanan',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'durasi_menit',
    ];

    public function mesin()
    {
        return $this->belongsTo(Mesin::class, 'id_mesin', 'id_mesin');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
