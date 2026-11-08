<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $primaryKey = 'id_pesanan';

    /**
     * Urutan lengkap tahap laundry.
     */
    public const TAHAP_LAUNDRY = [
        'Menunggu Antrean',
        'Pencucian',
        'Pengeringan',
        'Setrika',
        'Siap Diambil',
        'Dalam Pengiriman',
        'Selesai',
    ];

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

    /**
     * Apakah pesanan ini memakai tahap pengiriman kurir?
     * (aturannya sama dengan yang dulu ada di AdminController)
     */
    public function pakaiPengiriman(): bool
    {
        return $this->layanan->contains(function ($item) {
            return in_array(
                strtolower($item->jenis_layanan ?? ''),
                ['antar jemput', 'pickup']
            );
        });
    }

    /**
     * Daftar tahap laundry untuk pesanan ini.
     * Kalau tidak memakai kurir, tahap "Dalam Pengiriman" dilewati.
     */
    public function daftarTahapLaundry(): array
    {
        $tahap = self::TAHAP_LAUNDRY;

        if (!$this->pakaiPengiriman()) {
            $tahap = array_values(array_diff($tahap, ['Dalam Pengiriman']));
        }

        return $tahap;
    }

    /**
     * Tahap yang boleh dipilih admin berikutnya (null = sudah tahap akhir).
     */
    public function tahapLaundryBerikutnya(): ?string
    {
        $tahap = $this->daftarTahapLaundry();
        $posisi = array_search($this->status_laundry, $tahap);

        if ($posisi === false) {
            return null;
        }

        return $tahap[$posisi + 1] ?? null;
    }
}