<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesin extends Model
{
    protected $table = 'mesin';
    protected $primaryKey = 'id_mesin';

    protected $fillable = [
        'kode_mesin',
        'nama_mesin',
        'jenis',
        'kapasitas_kg',
        'status',
        'terakhir_servis',
    ];

    protected $casts = [
        'kapasitas_kg' => 'decimal:2',
        'terakhir_servis' => 'date',
    ];

    // Satu mesin dapat memiliki banyak riwayat penggunaan.
    public function penggunaan()
    {
        return $this->hasMany(
            PenggunaanMesin::class,
            'id_mesin',
            'id_mesin'
        );
    }

    // Mendapatkan penggunaan mesin yang sedang berjalan.
    public function penggunaanAktif()
    {
        return $this->hasOne(
            PenggunaanMesin::class,
            'id_mesin',
            'id_mesin'
        )->where('status', 'Berjalan');
    }
}
