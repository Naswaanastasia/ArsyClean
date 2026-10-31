<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stok';
    protected $primaryKey = 'id_stok';

    protected $fillable = [
        'kode_stok',
        'nama_barang',
        'kategori',
        'jumlah',
        'satuan',
        'batas_minimum',
        'status',
    ];
}
