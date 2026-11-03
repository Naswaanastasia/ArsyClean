<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    /**
     * Dashboard Eksekutif Owner
     */
    public function dashboard(Request $request)
    {
        // 1. Query Dasar Pembayaran Lunas dengan Filter Tanggal (Langkah 4: Memeriksa Laporan)
        $queryPembayaran = Pembayaran::where('status_pembayaran', 'Lunas');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $queryPembayaran->whereBetween('created_at', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59'
            ]);
        }

        // Total omzet berdasarkan filter / keseluruhan (Langkah 2: Melihat Ringkasan)
        $totalOmzet = $queryPembayaran->sum('jumlah_bayar');

        // 2. Total seluruh pesanan & transaksi selesai
        $totalPesanan = Pesanan::count();
        $totalTransaksi = Pesanan::where('status_pesanan', 'Selesai')->count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalAdmin = User::where('role', 'admin')->count();

        // 3. Memantau Aktivitas Layanan: Pickup, Reguler, dan Koin (Langkah 3: Memantau Layanan)
        $jumlahPickup = Pesanan::whereHas('layanan', function ($query) {
            $query->where('jenis_layanan', 'like', '%pickup%');
        })->count();

        $jumlahReguler = Pesanan::whereHas('layanan', function ($query) {
            $query->where('jenis_layanan', 'like', '%reguler%');
        })->count();

        $jumlahKoin = Pesanan::whereHas('layanan', function ($query) {
            $query->where('jenis_layanan', 'like', '%koin%');
        })->count();

        // 4. Statistik status pesanan
        $pesananSelesai = Pesanan::where('status_pesanan', 'Selesai')->count();
        $pesananDiproses = Pesanan::where('status_pesanan', 'Sedang Diproses')->count();
        $pesananDibatalkan = Pesanan::where('status_pesanan', 'Dibatalkan')->count();

        // 5. Riwayat Pembayaran & Layanan (Langkah 5: Mengatur Layanan)
        $pembayarans = Pembayaran::with(['pesanan.user', 'pesanan.layanan'])
            ->orderBy('id_pembayaran', 'desc')
            ->get();

        $layanans = Layanan::all();

        // 6. Kelola Data Akun Admin (Langkah 6: Mengelola Admin)
        $admins = User::where('role', 'admin')->get();

        return view('owner.dashboard', compact(
            'totalOmzet',
            'totalPesanan',
            'totalTransaksi',
            'totalPelanggan',
            'totalAdmin',
            'jumlahPickup',
            'jumlahReguler',
            'jumlahKoin',
            'pesananSelesai',
            'pesananDiproses',
            'pesananDibatalkan',
            'pembayarans',
            'layanans',
            'admins'
        ));
    }

    /**
     * Mengatur Tarif & Status Layanan (Langkah 5)
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

        return redirect()->route('owner.dashboard')
            ->with('success', "Layanan {$layanan->nama_layanan} berhasil diperbarui.");
    }

    /**
     * Menambah Akun Admin Baru (Langkah 6: Mengelola Admin)
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        return redirect()->route('owner.dashboard')
            ->with('success', 'Akun Admin baru berhasil ditambahkan.');
    }

    /**
     * Menghapus Akun Admin (Langkah 6: Mengelola Admin)
     */
    public function destroyAdmin($idAdmin)
    {
        $admin = User::where('role', 'admin')->findOrFail($idAdmin);
        $admin->delete();

        return redirect()->route('owner.dashboard')
            ->with('success', 'Akun Admin berhasil dihapus.');
    }

    /**
     * Cetak Laporan PDF (Langkah 4: Memeriksa Laporan)
     */
    public function cetakLaporanPdf(Request $request)
    {
        $queryPembayaran = Pembayaran::with(['pesanan.user', 'pesanan.layanan'])
            ->where('status_pembayaran', 'Lunas');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $queryPembayaran->whereBetween('created_at', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59'
            ]);
        }

        $pembayarans = $queryPembayaran->orderBy('id_pembayaran', 'desc')->get();
        $totalOmzet = $pembayarans->sum('jumlah_bayar');

        return view('owner.cetak_laporan', compact('pembayarans', 'totalOmzet'));
    }
}