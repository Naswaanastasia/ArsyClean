<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    /**
     * Dashboard Pelanggan: Katalog Layanan & Pelacakan Pesanan Aktif
     */
    public function dashboard()
    {
        $user = Auth::user();
        $layanans = Layanan::where('status', 'tersedia')->get();
        $pesanans = Pesanan::with(['layanan', 'pembayaran', 'pengiriman', 'ulasan'])
            ->where('id_user', $user->id_user)
            ->orderBy('id_pesanan', 'desc')
            ->get();

        $activePesanan = $pesanans->whereNotIn('status_laundry', ['Selesai'])->first();

        return view('pelanggan.dashboard', compact('user', 'layanans', 'pesanans', 'activePesanan'));
    }

    /**
     * Buat Pesanan Baru
     */
    public function storePesanan(Request $request)
    {
        $request->validate([
            'id_layanan' => 'required|exists:layanan,id_layanan',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|in:Tunai,QRIS,Transfer Bank,Koin',
        ]);

       $layanan = Layanan::findOrFail($request->id_layanan);
$totalHarga = $layanan->harga * $request->jumlah; // Pastikan ada tanda bintang (*)

$nextId = Pesanan::max('id_pesanan') + 1;
$kodePesanan = 'P' . str_pad($nextId, 3, '0', STR_PAD_LEFT);


$pesanan = Pesanan::create([
    'kode_pesanan' => $kodePesanan,
    'id_user' => Auth::id(),
    'tanggal_pesanan' => $request->tanggal,
    'waktu_jemput' => $request->waktu,
    'total_harga' => $totalHarga,
    'total_biaya' => $totalHarga,
    'status_pesanan' => 'Diproses',
    'status_laundry' => 'Menunggu Antrean',
    'metode_pembayaran' => $request->metode_pembayaran,
    'alamat_jemput' => $request->alamat,
    'alamat_penjemputan' => $request->alamat,
    'catatan' => $request->catatan,
]);

        // Detail layanan
       $pesanan->layanan()->attach($layanan->id_layanan, [
    'jumlah' => $request->jumlah,
    'subtotal' => $totalHarga,
]);

        
// Buat data pembayaran awal
$nextBayarId = Pembayaran::max('id_pembayaran') + 1;

Pembayaran::create([
    'kode_pembayaran' => 'BY' . str_pad($nextBayarId, 3, '0', STR_PAD_LEFT),
    'id_pesanan' => $pesanan->id_pesanan,
    'metode_pembayaran' => $request->metode_pembayaran,
    'status_pembayaran' => $request->metode_pembayaran === 'Koin' ? 'Lunas' : 'Belum Lunas',
    'jumlah_pembayaran' => $totalHarga,
    'tanggal_pembayaran' => now()->toDateString(),
    'diverifikasi_oleh' => $request->metode_pembayaran === 'Koin' ? 'Sistem Token Koin' : null,
]);

        return redirect()->route('pelanggan.dashboard')->with('success', "Pesanan #{$kodePesanan} berhasil dibuat!");
    }

    /**
     * Berikan Ulasan
     */
    public function storeUlasan(Request $request, $idPesanan)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
        ]);

        $pesanan = Pesanan::where('id_pesanan', $idPesanan)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $nextId = Ulasan::max('id_ulasan') + 1;
        Ulasan::create([
            'kode_ulasan' => 'UL' . str_pad($nextId, 3, '0', STR_PAD_LEFT),
            'id_pesanan' => $pesanan->id_pesanan,
            'id_user' => Auth::id(),
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'tanggal_ulasan' => now(),
        ]);

        return redirect()->route('pelanggan.dashboard')->with('success', 'Terima kasih atas ulasan dan penilaian Anda!');
    }
}
