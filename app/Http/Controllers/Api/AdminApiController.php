<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminApiController extends Controller
{
    // 1. Ambil Semua Pesanan Masuk (Kelola Pesanan)
    public function getSemuaPesanan()
    {
        $pesanan = DB::table('pesanan')
            ->join('users', 'pesanan.user_id', '=', 'users.id')
            ->join('layanan', 'pesanan.layanan_id', '=', 'layanan.id')
            ->select(
                'pesanan.*',
                'users.name as nama_pelanggan',
                'layanan.nama_layanan'
            )
            ->orderBy('pesanan.created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $pesanan], 200);
    }

    // 2. Update Status Proses Laundry (misal: 'Dicuci', 'Selesai')
    public function updateStatusPesanan(Request $request, $id)
    {
        $request->validate([
            'status_pesanan' => 'required|string'
        ]);

        DB::table('pesanan')
            ->where('id', $id)
            ->update([
                'status_pesanan' => $request->status_pesanan,
                'updated_at' => now()
            ]);

        return response()->json(['success' => true, 'message' => 'Status pesanan berhasil diperbarui!'], 200);
    }

    // 3. Verifikasi Pembayaran Pelanggan
    public function verifikasiPembayaran(Request $request, $idPembayaran)
    {
        $request->validate([
            'status' => 'required|in:Lunas,Ditolak',
            'verifikator_id' => 'required'
        ]);

        $pembayaran = DB::table('pembayaran')->where('id', $idPembayaran)->first();
        if (!$pembayaran) {
            return response()->json(['success' => false, 'message' => 'Data pembayaran tidak ditemukan'], 404);
        }

        // Update status di tabel pembayaran
        DB::table('pembayaran')
            ->where('id', $idPembayaran)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

        // Update status pembayaran di tabel pesanan
        DB::table('pesanan')
            ->where('id', $pembayaran->pesanan_id)
            ->update([
                'status_pembayaran' => $request->status,
                'updated_at' => now()
            ]);

        return response()->json(['success' => true, 'message' => 'Status pembayaran berhasil diupdate!'], 200);
    }

    // 4. Update Status Pengiriman / Penjemputan Kurir
    public function updateStatusPengiriman(Request $request, $idPengiriman)
    {
        $request->validate([
            'status_pengiriman' => 'required|string'
        ]);

        DB::table('pengiriman')
            ->where('id', $idPengiriman)
            ->update([
                'status_pengiriman' => $request->status_pengiriman,
                'updated_at' => now()
            ]);

        return response()->json(['success' => true, 'message' => 'Status pengiriman berhasil diperbarui!'], 200);
    }
}