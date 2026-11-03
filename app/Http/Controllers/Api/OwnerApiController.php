<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerApiController extends Controller
{
    // 1. Laporan Ringkasan Pendapatan & Statistik (Dashboard Owner)
    public function getLaporanRingkasan()
    {
        $totalPendapatan = DB::table('pesanan')
            ->where('status_pembayaran', 'Lunas')
            ->sum('total_harga');

        $totalPesananSelesai = DB::table('pesanan')
            ->where('status_pesanan', 'Selesai')
            ->count();

        $totalPelanggan = DB::table('users')
            ->where('role', 'pelanggan')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_pendapatan' => $totalPendapatan,
                'total_pesanan_selesai' => $totalPesananSelesai,
                'total_pelanggan' => $totalPelanggan
            ]
        ], 200);
    }

    // 2. Kelola/Kelola Katalog Layanan Laundry (Tambah/Edit Layanan)
    public function simpanLayanan(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string',
            'harga_per_kg' => 'required|numeric',
        ]);

        DB::table('layanan')->insert([
            'nama_layanan' => $request->nama_layanan,
            'harga_per_kg' => $request->harga_per_kg,
            'deskripsi' => $request->deskripsi ?? null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Layanan baru berhasil ditambahkan!'], 201);
    }

    public function updateLayanan(Request $request, $id)
    {
        DB::table('layanan')
            ->where('id', $id)
            ->update([
                'nama_layanan' => $request->nama_layanan,
                'harga_per_kg' => $request->harga_per_kg,
                'deskripsi' => $request->deskripsi,
                'is_active' => $request->is_active ?? true,
                'updated_at' => now()
            ]);

        return response()->json(['success' => true, 'message' => 'Data layanan berhasil diperbarui!'], 200);
    }
}