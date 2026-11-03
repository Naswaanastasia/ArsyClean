<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganApiController extends Controller
{
    // 1. Ambil Daftar Layanan Laundry
    public function getLayanan()
    {
        $layanan = DB::table('layanan')->where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'data' => $layanan
        ], 200);
    }

    // 2. Buat Pesanan Baru
    public function createPesanan(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'layanan_id' => 'required',
            'berat_kg' => 'required|numeric',
        ]);

        $layanan = DB::table('layanan')->where('id', $request->layanan_id)->first();
        if (!$layanan) {
            return response()->json(['success' => false, 'message' => 'Layanan tidak ditemukan'], 404);
        }

        $totalHarga = $request->berat_kg * $layanan->harga_per_kg;

        // Simpan ke tabel pesanan
        $pesananId = DB::table('pesanan')->insertGetId([
            'user_id' => $request->user_id,
            'layanan_id' => $request->layanan_id,
            'berat_kg' => $request->berat_kg,
            'total_harga' => $totalHarga,
            'catatan' => $request->catatan ?? null,
            'status_pesanan' => 'Menunggu Konfirmasi',
            'status_pembayaran' => 'Belum Bayar',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Jika ada pengiriman/penjemputan
        if ($request->jenis_pengiriman && $request->jenis_pengiriman !== 'Self Take') {
            DB::table('pengiriman')->insert([
                'pesanan_id' => $pesananId,
                'jenis' => $request->jenis_pengiriman,
                'alamat' => $request->alamat_pengiriman,
                'status_pengiriman' => 'Pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat!',
            'pesanan_id' => $pesananId
        ], 201);
    }

    // 3. Ambil Riwayat Pesanan
    public function getRiwayatPesanan($userId)
    {
        $pesanan = DB::table('pesanan')
            ->join('layanan', 'pesanan.layanan_id', '=', 'layanan.id')
            ->leftJoin('pengiriman', 'pesanan.id', '=', 'pengiriman.pesanan_id')
            ->select(
                'pesanan.*',
                'layanan.nama_layanan',
                'layanan.harga_per_kg',
                'pengiriman.jenis as jenis_pengiriman'
            )
            ->where('pesanan.user_id', $userId)
            ->orderBy('pesanan.created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pesanan
        ], 200);
    }

    // 4. Upload Bukti Transfer / Bayar
    public function uploadPembayaran(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required',
            'user_id' => 'required',
            'metode_pembayaran' => 'required',
            'bukti_foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('bukti_foto')) {
            $path = $request->file('bukti_foto')->store('bukti_pembayaran', 'public');
            $buktiUrl = asset('storage/' . $path);

            DB::table('pembayaran')->insert([
                'pesanan_id' => $request->pesanan_id,
                'user_id' => $request->user_id,
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_url' => $buktiUrl,
                'status' => 'Menunggu Verifikasi',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('pesanan')
                ->where('id', $request->pesanan_id)
                ->update(['status_pembayaran' => 'Menunggu Verifikasi', 'updated_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diunggah!'
            ], 200);
        }

        return response()->json(['success' => false, 'message' => 'File gagal diunggah'], 400);
    }

    // 5. Kirim Ulasan
    public function kirimUlasan(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required',
            'user_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        DB::table('ulasan')->insert([
            'pesanan_id' => $request->pesanan_id,
            'user_id' => $request->user_id,
            'rating' => $request->rating,
            'komentar' => $request->komentar ?? '',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil terkirim!'
        ], 201);
    }
}