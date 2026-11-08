<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;

class OwnerApiController extends Controller
{
    /**
     * 1. Laporan Ringkasan Pendapatan & Statistik Eksekutif
     */
    public function getLaporanRingkasan()
    {
        $totalPendapatan = (float) Pembayaran::where('status_pembayaran', 'Lunas')->sum('jumlah_bayar');
        $totalPesanan = Pesanan::count();
        $totalPesananSelesai = Pesanan::where('status_pesanan', 'Selesai')->count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalAdmin = User::where('role', 'admin')->count();

        return response()->json([
            'success' => true,
            'message' => 'Ringkasan laporan bisnis berhasil diambil.',
            'data'    => [
                'total_pendapatan'        => $totalPendapatan,
                'total_pendapatan_format' => 'Rp ' . number_format($totalPendapatan, 0, ',', '.'),
                'total_pesanan'           => $totalPesanan,
                'total_pesanan_selesai'   => $totalPesananSelesai,
                'total_pelanggan'         => $totalPelanggan,
                'total_admin'             => $totalAdmin,
            ]
        ], 200);
    }

    /**
     * 2. Tambah Layanan Baru
     */
    public function simpanLayanan(Request $request)
    {
        $validated = $request->validate([
            'nama_layanan'  => 'required|string|max:100',
            'jenis_layanan' => 'required|in:Reguler,Koin,Antar Jemput,Dry Clean,Express',
            'harga'         => 'required|numeric|min:0',
            'satuan'        => 'required|string|max:20',
            'durasi'        => 'required|string|max:50',
            'deskripsi'     => 'nullable|string|max:1000',
            'status'        => 'nullable|in:tersedia,tidak_tersedia',
        ], [
            'nama_layanan.required'  => 'Nama layanan wajib diisi.',
            'jenis_layanan.required' => 'Jenis layanan wajib diisi.',
            'jenis_layanan.in'       => 'Jenis layanan harus salah satu dari: Reguler, Koin, Antar Jemput, Dry Clean, Express.',
            'harga.required'         => 'Tarif layanan wajib diisi.',
            'harga.numeric'          => 'Tarif harus berupa angka valid.',
            'satuan.required'        => 'Satuan layanan (misal: Kg, Pcs) wajib diisi.',
            'durasi.required'        => 'Durasi pengerjaan wajib diisi.',
        ]);

        $nextId = (int) (Layanan::max('id_layanan') ?? 0) + 1;
        $kodeLayanan = 'L' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $layanan = Layanan::create([
            'kode_layanan'  => $kodeLayanan,
            'nama_layanan'  => trim($validated['nama_layanan']),
            'jenis_layanan' => $validated['jenis_layanan'],
            'harga'         => (float) $validated['harga'],
            'satuan'        => trim($validated['satuan']),
            'durasi'        => trim($validated['durasi']),
            'deskripsi'     => $validated['deskripsi'] ?? null,
            'status'        => $validated['status'] ?? 'tersedia',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Layanan baru {$layanan->nama_layanan} berhasil ditambahkan.",
            'data'    => $layanan
        ], 201);
    }

    /**
     * 3. Update Data / Tarif Layanan
     */
    public function updateLayanan(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_layanan'  => 'nullable|string|max:100',
            'jenis_layanan' => 'nullable|in:Reguler,Koin,Antar Jemput,Dry Clean,Express',
            'harga'         => 'nullable|numeric|min:0',
            'satuan'        => 'nullable|string|max:20',
            'durasi'        => 'nullable|string|max:50',
            'deskripsi'     => 'nullable|string|max:1000',
            'status'        => 'nullable|in:tersedia,tidak_tersedia',
        ], [
            'jenis_layanan.in' => 'Jenis layanan harus salah satu dari: Reguler, Koin, Antar Jemput, Dry Clean, Express.',
            'harga.numeric'    => 'Tarif harga harus berupa angka.',
            'status.in'        => 'Status layanan harus tersedia atau tidak_tersedia.',
        ]);

        $layanan = Layanan::findOrFail($id);

        if ($request->has('nama_layanan')) {
            $layanan->nama_layanan = trim($validated['nama_layanan']);
        }
        if ($request->has('jenis_layanan')) {
            $layanan->jenis_layanan = $validated['jenis_layanan'];
        }
        if ($request->has('harga')) {
            $layanan->harga = (float) $validated['harga'];
        }
        if ($request->has('satuan')) {
            $layanan->satuan = trim($validated['satuan']);
        }
        if ($request->has('durasi')) {
            $layanan->durasi = trim($validated['durasi']);
        }
        if ($request->has('deskripsi')) {
            $layanan->deskripsi = $validated['deskripsi'];
        }
        if ($request->has('status')) {
            $layanan->status = $validated['status'];
        }

        $layanan->save();

        return response()->json([
            'success' => true,
            'message' => "Data layanan {$layanan->nama_layanan} berhasil diperbarui.",
            'data'    => $layanan
        ], 200);
    }
}
