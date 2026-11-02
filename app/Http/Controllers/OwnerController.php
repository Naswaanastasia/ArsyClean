<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    /**
     * Dashboard Laporan Eksekutif Owner
     */
    public function dashboard()
    {
        // Total Omzet Keseluruhan
       $totalOmzet = Pembayaran::where('status_pembayaran', 'Lunas')->sum('jumlah_bayar');

        // Total Transaksi Selesai
        $totalTransaksi = Pesanan::where('status_pesanan', 'Selesai')->count();

        // Total Pelanggan Aktif
        $totalPelanggan = User::where('role', 'pelanggan')->count();

        // Riwayat Transaksi Lengkap
        $pembayarans = Pembayaran::with(['pesanan.user', 'pesanan.layanan'])
            ->orderBy('id_pembayaran', 'desc')
            ->get();

        // Katalog Layanan untuk Pengaturan Tarif
        $layanans = Layanan::all();

        return view('owner.dashboard', compact('totalOmzet', 'totalTransaksi', 'totalPelanggan', 'pembayarans', 'layanans'));
    }

    /**
     * Update Tarif dan Status Layanan
     */
    public function updateLayanan(Request $request, $idLayanan)
    {
        $request->validate([
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,tidak_tersedia',
        ]);

        $layanan = Layanan::findOrFail($idLayanan);
        $layanan->harga = $request->harga;
        $layanan->status = $request->status;
        $layanan->save();

        return back()->with('success', "Tarif layanan {$layanan->nama_layanan} berhasil diperbarui menjadi Rp " . number_format($layanan->harga, 0, ',', '.'));
    }
}
