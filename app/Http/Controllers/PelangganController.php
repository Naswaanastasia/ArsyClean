<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    /**
     * Dashboard Pelanggan
     */
    public function dashboard()
    {
        $user = Auth::user();

        $layanans = Layanan::where('status', 'tersedia')->get();

        $pesanans = Pesanan::with([
            'layanan',
            'pembayaran',
            'pengiriman',
            'ulasan'
        ])
            ->where('id_user', $user->id_user)
            ->orderByDesc('id_pesanan')
            ->get();

        $activePesanan = $pesanans
            ->where('status_laundry', '!=', 'Selesai')
            ->first();

        return view('pelanggan.dashboard', compact(
            'user',
            'layanans',
            'pesanans',
            'activePesanan'
        ));
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
            'catatan' => 'nullable|string',
        ]);

        $layanan = Layanan::where('status', 'tersedia')
            ->findOrFail($request->id_layanan);

        $jumlah = $request->jumlah;
        $totalHarga = $layanan->harga * $jumlah;

        try {
            DB::transaction(function () use (
                $request,
                $layanan,
                $jumlah,
                $totalHarga
            ) {
                $nextId = (Pesanan::max('id_pesanan') ?? 0) + 1;

                $kodePesanan = 'P' . str_pad(
                    $nextId,
                    3,
                    '0',
                    STR_PAD_LEFT
                );

                $pesanan = Pesanan::create([
                    'kode_pesanan' => $kodePesanan,
                    'id_user' => Auth::user()->id_user,
                    'tanggal_pesanan' => $request->tanggal,
                    'total_harga' => $totalHarga,
                    'status_pesanan' => 'Diproses',
                    'status_laundry' => 'Menunggu Antrean',
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'alamat_penjemputan' => $request->alamat,
                    'catatan' => $request->catatan,
                ]);

                $pesanan->layanan()->attach($layanan->id_layanan, [
                    'jumlah' => $jumlah,
                    'harga_satuan' => $layanan->harga,
                    'subtotal' => $totalHarga,
                ]);

                $nextBayarId = (Pembayaran::max('id_pembayaran') ?? 0) + 1;

                Pembayaran::create([
                    'kode_pembayaran' => 'BY' . str_pad(
                        $nextBayarId,
                        3,
                        '0',
                        STR_PAD_LEFT
                    ),
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_user' => Auth::user()->id_user,
                    'jumlah_bayar' => $totalHarga,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'status_pembayaran' => 'Pending',
                    'tanggal_bayar' => now(),
                    'bukti_transfer' => null,
                    'verifikator' => null,
                ]);
            });

            return redirect()
                ->route('pelanggan.dashboard')
                ->with('success', 'Pesanan berhasil dibuat!');

       } catch (\Throwable $e) {
    return back()
        ->withInput()
        ->with('error', 'Pesanan gagal dibuat: ' . $e->getMessage());

}
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
            ->where('id_user', Auth::user()->id_user)
            ->where('status_laundry', 'Selesai')
            ->firstOrFail();

        if ($pesanan->ulasan) {
            return back()->with('error', 'Pesanan ini sudah memiliki ulasan.');
        }

        $nextId = (Ulasan::max('id_ulasan') ?? 0) + 1;

        Ulasan::create([
            'kode_ulasan' => 'UL' . str_pad(
                $nextId,
                3,
                '0',
                STR_PAD_LEFT
            ),
            'id_pesanan' => $pesanan->id_pesanan,
            'id_user' => Auth::user()->id_user,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'tanggal_ulasan' => now(),
        ]);

        return redirect()
            ->route('pelanggan.dashboard')
            ->with('success', 'Terima kasih atas ulasan dan penilaian Anda!');
    }
}
