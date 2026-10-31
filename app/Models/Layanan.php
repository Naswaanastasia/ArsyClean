<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';

    protected $fillable = [
        'kode_layanan',
        'nama_layanan',
        'jenis_layanan',
        'harga',
        'satuan',
        'durasi',
        'deskripsi',
        'status',
        'icon',
    ];

    public function pesanan()
    {
        return $this->belongsToMany(Pesanan::class, 'pesanan_layanan', 'id_layanan', 'id_pesanan')
                    ->withPivot('jumlah', 'harga_satuan', 'subtotal')
                    ->withTimestamps();
    }
}
