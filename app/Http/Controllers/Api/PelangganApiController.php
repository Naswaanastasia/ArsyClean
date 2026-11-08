<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganApiController extends Controller
{
    /**
     * 1. Ambil Daftar Katalog Layanan Aktif
     */
    public function getLayanan()
    {
        $layanan = Layanan::where('status', 'tersedia')
            ->orderBy('id_layanan')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar layanan tersedia berhasil diambil.',
            'total'   => $layanan->count(),
            'data'    => $layanan
        ], 200);
    }

    /**
     * 2. Buat Pesanan Baru (1 Layanan = 1 Kode Pesanan)
     */
    public function createPesanan(Request $request)
    {
        $validated = $request->validate([
            'id_layanan'         => 'required|integer|exists:layanan,id_layanan',
            'jumlah'             => 'required|numeric|min:0.1',
            'tanggal_pesanan'    => 'required|date|after_or_equal:today',
            'alamat_penjemputan' => 'required|string|max:500',
            'metode_pembayaran'  => 'required|in:Tunai,QRIS,Transfer Bank,Koin',
            'catatan'            => 'nullable|string|max:1000',
        ], [
            'id_layanan.required'         => 'Layanan wajib dipilih.',
            'id_layanan.exists'           => 'Layanan tidak ditemukan atau tidak tersedia.',
            'jumlah.required'             => 'Jumlah cucian atau berat wajib diisi.',
            'jumlah.numeric'              => 'Jumlah cucian harus berupa angka.',
            'jumlah.min'                  => 'Jumlah cucian minimal 0.1.',
            'tanggal_pesanan.required'    => 'Tanggal pesanan wajib diisi.',
            'tanggal_pesanan.after_or_equal' => 'Tanggal pesanan minimal hari ini.',
            'alamat_penjemputan.required' => 'Alamat penjemputan cucian wajib diisi.',
            'metode_pembayaran.required'  => 'Metode pembayaran wajib dipilih.',
            'metode_pembayaran.in'        => 'Metode pembayaran harus salah satu dari: Tunai, QRIS, Transfer Bank, Koin.',
        ]);

        $userId = auth()->id() ?? $request->input('id_user');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi.'], 401);
        }

        $layanan = Layanan::findOrFail($validated['id_layanan']);
        $jumlah = (float) $validated['jumlah'];
        $hargaSatuan = (float) $layanan->harga;
        $totalHarga = round($jumlah * $hargaSatuan, 2);

        $nextId = (int) (Pesanan::max('id_pesanan') ?? 0) + 1;
        $kodePesanan = 'P' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        [$pesanan, $pembayaran] = DB::transaction(function () use (
            $userId,
            $kodePesanan,
            $validated,
            $totalHarga,
            $layanan,
            $jumlah,
            $hargaSatuan
        ) {
            $pesanan = Pesanan::create([
                'kode_pesanan'       => $kodePesanan,
                'id_user'            => $userId,
                'tanggal_pesanan'    => $validated['tanggal_pesanan'],
                'total_harga'        => $totalHarga,
                'status_pesanan'     => 'Menunggu Pembayaran',
                'status_laundry'     => 'Menunggu Antrean',
                'metode_pembayaran'  => $validated['metode_pembayaran'],
                'alamat_penjemputan' => trim($validated['alamat_penjemputan']),
                'catatan'            => $validated['catatan'] ? trim($validated['catatan']) : null,
            ]);

            $pesanan->layanan()->attach($layanan->id_layanan, [
                'jumlah'       => $jumlah,
                'harga_satuan' => $hargaSatuan,
                'subtotal'     => $totalHarga,
            ]);

            $nextBayarId = (int) (Pembayaran::max('id_pembayaran') ?? 0) + 1;
            $kodePembayaran = 'BY' . str_pad($nextBayarId, 4, '0', STR_PAD_LEFT);

            $pembayaran = Pembayaran::create([
                'kode_pembayaran'   => $kodePembayaran,
                'id_pesanan'        => $pesanan->id_pesanan,
                'id_user'           => $userId,
                'jumlah_bayar'      => $totalHarga,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_pembayaran' => 'Pending',
                'tanggal_bayar'     => null,
            ]);

            return [$pesanan, $pembayaran];
        });

        return response()->json([
            'success' => true,
            'message' => "Pesanan #{$pesanan->kode_pesanan} berhasil dibuat.",
            'data'    => $pesanan->load('layanan', 'pembayaran')
        ], 201);
    }

    /**
     * 3. Ambil Riwayat Pesanan Pelanggan (Read)
     */
    public function getRiwayatPesanan($userId)
    {
        $pesanan = Pesanan::with(['layanan', 'pembayaran', 'pengiriman', 'ulasan'])
            ->where('id_user', $userId)
            ->orderByDesc('id_pesanan')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pesanan pelanggan berhasil diambil.',
            'total'   => $pesanan->count(),
            'data'    => $pesanan
        ], 200);
    }

    /**
     * 4. Upload Bukti Transfer / Pembayaran
     */
    public function uploadPembayaran(Request $request)
    {
        $validated = $request->validate([
            'id_pesanan'     => 'required|integer|exists:pesanan,id_pesanan',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'id_pesanan.required'     => 'ID pesanan wajib diisi.',
            'id_pesanan.exists'       => 'Data pesanan tidak ditemukan.',
            'bukti_transfer.required' => 'File bukti transfer wajib diunggah.',
            'bukti_transfer.image'    => 'File harus berupa gambar.',
            'bukti_transfer.mimes'    => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'bukti_transfer.max'      => 'Ukuran gambar maksimal 3 MB.',
        ]);

        $pesanan = Pesanan::with('pembayaran')->findOrFail($validated['id_pesanan']);

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        if ($pesanan->pembayaran) {
            $pesanan->pembayaran->update([
                'bukti_transfer'    => $path,
                'status_pembayaran' => 'Pending',
            ]);
        }

        $pesanan->update([
            'status_pesanan' => 'Menunggu Verifikasi',
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Bukti transfer berhasil diunggah. Menunggu verifikasi admin.',
            'bukti_url' => asset('storage/' . $path),
            'pesanan'   => $pesanan->fresh(['pembayaran']),
        ], 200);
    }

    /**
     * 5. Kirim Ulasan Layanan
     */
    public function kirimUlasan(Request $request)
    {
        $validated = $request->validate([
            'id_pesanan' => 'required|integer|exists:pesanan,id_pesanan',
            'rating'     => 'required|integer|min:1|max:5',
            'komentar'   => 'nullable|string|max:1000',
        ], [
            'id_pesanan.required' => 'ID pesanan wajib diisi.',
            'rating.required'     => 'Rating bintang wajib dipilih (1 sampai 5).',
            'rating.min'          => 'Rating bintang minimal 1.',
            'rating.max'          => 'Rating bintang maksimal 5.',
        ]);

        $pesanan = Pesanan::findOrFail($validated['id_pesanan']);

        if ($pesanan->status_pesanan !== 'Selesai' && $pesanan->status_laundry !== 'Selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Ulasan hanya dapat diberikan untuk pesanan yang telah selesai.'
            ], 422);
        }

        if (Ulasan::where('id_pesanan', $pesanan->id_pesanan)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah memiliki ulasan.'
            ], 422);
        }

        $nextId = (int) (Ulasan::max('id_ulasan') ?? 0) + 1;
        $kodeUlasan = 'UL' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $ulasan = Ulasan::create([
            'kode_ulasan'    => $kodeUlasan,
            'id_pesanan'     => $pesanan->id_pesanan,
            'id_user'        => $pesanan->id_user,
            'rating'         => $validated['rating'],
            'komentar'       => $validated['komentar'] ?? null,
            'tanggal_ulasan' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil disimpan. Terima kasih atas penilaian Anda!',
            'data'    => $ulasan
        ], 201);
    }
}
