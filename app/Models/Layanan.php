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

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function pesanan()
    {
        return $this->belongsToMany(
            Pesanan::class,
            'pesanan_layanan',
            'id_layanan',
            'id_pesanan'
        )->withPivot(
            'id_detail',
            'jumlah',
            'harga_satuan',
            'subtotal'
        )->withTimestamps();
    }
}
use App\Http\Controllers\Api\PelangganApiController;

Route::prefix('pelanggan')->group(function () {
    Route::get('/layanan', [PelangganApiController::class, 'getLayanan']);
    Route::post('/pesanan', [PelangganApiController::class, 'createPesanan']);
    Route::get('/pesanan/riwayat/{userId}', [PelangganApiController::class, 'getRiwayatPesanan']);
    Route::post('/pembayaran', [PelangganApiController::class, 'uploadPembayaran']);
    Route::post('/ulasan', [PelangganApiController::class, 'kirimUlasan']);
});