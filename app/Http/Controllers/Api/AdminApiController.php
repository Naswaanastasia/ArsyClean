<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminApiController extends Controller
{
    /**
     * 1. Ambil Semua Pesanan Masuk & Aktif
     */
    public function getSemuaPesanan()
    {
        $pesanan = Pesanan::with(['user:id_user,nama,email,no_hp', 'layanan', 'pembayaran', 'pengiriman'])
            ->orderByDesc('id_pesanan')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua pesanan berhasil diambil.',
            'total'   => $pesanan->count(),
            'data'    => $pesanan
        ], 200);
    }

    /**
     * 2. Update Status Proses Laundry / Pesanan
     */
    public function updateStatusPesanan(Request $request, $id)
    {
        $request->validate([
            'status_pesanan' => 'nullable|in:Menunggu Pembayaran,Menunggu Verifikasi,Diproses,Selesai,Dibatalkan',
            'status_laundry' => 'nullable|in:Menunggu Antrean,Pencucian,Pengeringan,Setrika,Siap Diambil,Siap Dikirim,Dalam Pengiriman,Selesai',
            'catatan_admin'  => 'nullable|string|max:500',
        ], [
            'status_pesanan.in' => 'Status pesanan harus salah satu dari: Menunggu Pembayaran, Menunggu Verifikasi, Diproses, Selesai, Dibatalkan.',
            'status_laundry.in' => 'Status laundry harus salah satu dari: Menunggu Antrean, Pencucian, Pengeringan, Setrika, Siap Diambil, Siap Dikirim, Dalam Pengiriman, Selesai.',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        if ($request->filled('status_pesanan')) {
            $pesanan->status_pesanan = $request->status_pesanan;
        }

        if ($request->filled('status_laundry')) {
            $pesanan->status_laundry = $request->status_laundry;
            if ($request->status_laundry === 'Selesai') {
                $pesanan->status_pesanan = 'Selesai';
            }
        }

        if ($request->filled('catatan_admin')) {
            $pesanan->catatan = trim(($pesanan->catatan ?? '') . "\n[Catatan Admin: " . $request->catatan_admin . "]");
        }

        $pesanan->save();

        return response()->json([
            'success' => true,
            'message' => "Status pesanan #{$pesanan->kode_pesanan} berhasil diperbarui.",
            'data'    => $pesanan
        ], 200);
    }

    /**
     * 3. Verifikasi Pembayaran Pelanggan
     */
    public function verifikasiPembayaran(Request $request, $idPembayaran)
    {
        $request->validate([
            'status'      => 'required|in:Lunas,Gagal,Ditolak',
            'verifikator' => 'nullable|string|max:100',
        ], [
            'status.required' => 'Status verifikasi pembayaran wajib diisi.',
            'status.in'       => 'Pilihan status pembayaran harus: Lunas, Gagal, atau Ditolak.',
        ]);

        $pembayaran = Pembayaran::with('pesanan')->findOrFail($idPembayaran);

        DB::transaction(function () use ($pembayaran, $request) {
            $statusTujuan = $request->status === 'Ditolak' ? 'Gagal' : $request->status;
            $pembayaran->status_pembayaran = $statusTujuan;
            $pembayaran->verifikator = $request->verifikator ?? (auth()->user()->nama ?? 'Admin Operasional');

            if ($statusTujuan === 'Lunas') {
                $pembayaran->tanggal_bayar = now();
                if ($pembayaran->pesanan) {
                    $pembayaran->pesanan->status_pesanan = 'Diproses';
                    $pembayaran->pesanan->save();
                }
            } elseif ($statusTujuan === 'Gagal') {
                if ($pembayaran->pesanan) {
                    $pembayaran->pesanan->status_pesanan = 'Dibatalkan';
                    $pembayaran->pesanan->save();
                }
            }

            $pembayaran->save();
        });

        return response()->json([
            'success' => true,
            'message' => "Status pembayaran #{$pembayaran->kode_pembayaran} berhasil diperbarui menjadi {$pembayaran->status_pembayaran}.",
            'data'    => $pembayaran
        ], 200);
    }

    /**
     * 4. Update Status Pengiriman Kurir
     */
    public function updateStatusPengiriman(Request $request, $idPengiriman)
    {
        $request->validate([
            'status_pengiriman' => 'required|in:Dijadwalkan,Dalam Perjalanan,Terkirim,Gagal',
        ], [
            'status_pengiriman.required' => 'Status pengiriman kurir wajib diisi.',
            'status_pengiriman.in'       => 'Pilihan status pengiriman harus: Dijadwalkan, Dalam Perjalanan, Terkirim, atau Gagal.',
        ]);

        $pengiriman = Pengiriman::findOrFail($idPengiriman);
        $pengiriman->status_pengiriman = $request->status_pengiriman;
        $pengiriman->save();

        return response()->json([
            'success' => true,
            'message' => "Status pengiriman #{$pengiriman->kode_pengiriman} berhasil diperbarui menjadi {$pengiriman->status_pengiriman}.",
            'data'    => $pengiriman
        ], 200);
    }
}
