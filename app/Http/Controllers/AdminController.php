<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Stok;
use App\Models\Pengiriman;
use App\Models\Mesin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Dashboard Operasional Admin
     */
    public function dashboard()
    {
        // Mengambil data pesanan beserta relasinya
        $pesanans = Pesanan::with([
            'user',
            'layanan',
            'pembayaran',
            'pengiriman'
        ])
            ->orderBy('id_pesanan', 'desc')
            ->get();

        // Mengambil seluruh data stok
        $stoks = Stok::all();

        // Mengambil data pembayaran beserta pesanan dan pelanggan
        $pembayarans = Pembayaran::with([
            'pesanan.user'
        ])
            ->orderBy('id_pembayaran', 'desc')
            ->get();

        // Mengambil seluruh data mesin
        $mesins = Mesin::all();

        return view('admin.dashboard', compact(
            'pesanans',
            'stoks',
            'pembayarans',
            'mesins'
        ));
    }

    /**
     * Mengubah Status Laundry
     */
    public function updateStatusLaundry(Request $request, $idPesanan)
    {
        $request->validate([
            'status_laundry' => 'required|in:Menunggu Antrean,Pencucian,Pengeringan,Setrika,Siap Diambil,Dalam Pengiriman,Selesai',
        ]);

        $pesanan = Pesanan::findOrFail($idPesanan);

        $pesanan->status_laundry = $request->status_laundry;

        if ($request->status_laundry === 'Selesai') {
            $pesanan->status_pesanan = 'Selesai';
        } else {
            $pesanan->status_pesanan = 'Diproses';
        }

        $pesanan->save();

        return back()->with(
            'success',
            "Status cucian pesanan {$pesanan->kode_pesanan} diperbarui menjadi '{$request->status_laundry}'."
        );
    }

    /**
     * Verifikasi Pembayaran Kasir
     */
    public function verifikasiPembayaran($idPembayaran)
    {
        $pembayaran = Pembayaran::findOrFail($idPembayaran);

        $pembayaran->status_pembayaran = 'Lunas';
        $pembayaran->diverifikasi_oleh = Auth::user()->nama_user;

        $pembayaran->save();

        return back()->with(
            'success',
            "Pembayaran {$pembayaran->kode_pembayaran} berhasil diverifikasi Lunas."
        );
    }

    /**
     * Tambah / Perbarui Stok Bahan Baku dan Koin
     */
    public function updateStok(Request $request, $idStok)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
        ]);

        $stok = Stok::findOrFail($idStok);

        $stok->jumlah = $request->jumlah;

        $stok->status = $stok->jumlah <= 0
            ? 'Habis'
            : ($stok->jumlah <= $stok->batas_minimum
                ? 'Menipis'
                : 'Aman');

        $stok->save();

        return back()->with(
            'success',
            "Stok {$stok->nama_barang} berhasil diperbarui."
        );
    }

    /**
     * Input Data Pengiriman Kurir
     */
    public function simpanPengiriman(Request $request)
    {
        $request->validate([
            'id_pesanan' => 'required|exists:pesanan,id_pesanan',
            'nama_kurir' => 'required|string',
            'no_hp_kurir' => 'required|string',
            'kendaraan' => 'required|string',
            'alamat_tujuan' => 'required|string',
        ]);

        $nextId = Pengiriman::max('id_pengiriman') + 1;

        $kodePengiriman = 'KIRIM' . str_pad(
            $nextId,
            3,
            '0',
            STR_PAD_LEFT
        );

        Pengiriman::create([
            'kode_pengiriman' => $kodePengiriman,
            'id_pesanan' => $request->id_pesanan,
            'nama_kurir' => $request->nama_kurir,
            'no_hp_kurir' => $request->no_hp_kurir,
            'kendaraan' => $request->kendaraan,
            'no_resi' => 'RS-' . rand(100000, 999999),
            'alamat_tujuan' => $request->alamat_tujuan,
            'status_pengiriman' => 'Dalam Perjalanan',
            'tanggal_kirim' => now(),
        ]);

        $pesanan = Pesanan::findOrFail($request->id_pesanan);

        $pesanan->status_laundry = 'Dalam Pengiriman';
        $pesanan->save();

        return back()->with(
            'success',
            "Pengiriman berhasil dijadwalkan dengan kurir {$request->nama_kurir}."
        );
    }
}
