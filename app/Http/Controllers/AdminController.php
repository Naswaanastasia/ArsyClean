<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Stok;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * DASHBOARD ADMIN
     */
    public function dashboard()
    {
        // 0. Ambil data layanan yang tersedia
        $layanans = Layanan::where('status', 'tersedia')->get();

        // 1. Ambil seluruh pesanan non-koin beserta relasi terkait
        $pesanans = Pesanan::with(['layanan', 'pembayaran', 'user', 'pengiriman'])
            ->whereHas('layanan', function ($query) {
                $query->where('jenis_layanan', '!=', 'Koin');
            })
            ->orderByDesc('id_pesanan')
            ->get();

        // 2. Pesanan Masuk: pesanan baru yang belum diproses oleh admin
        $pesananMasuk = $pesanans->filter(function ($p) {
            return in_array($p->status_pesanan, [
                'Menunggu Pembayaran',
                'Menunggu Verifikasi',
                'Menunggu Konfirmasi',
                'Pending',
                'Baru'
            ]);
        })->values();

        // 3. Pesanan Dalam Proses: pesanan yang telah diterima dan sedang dikerjakan
        $pesananDiproses = $pesanans->filter(function ($p) {
            return $p->status_pesanan === 'Diproses' && $p->status_laundry !== 'Selesai';
        })->values();

        // 4. Pesanan Selesai
        $pesananSelesai = $pesanans->filter(function ($p) {
            return $p->status_pesanan === 'Selesai' || $p->status_laundry === 'Selesai';
        })->values();

        // 5. Pesanan Dibatalkan
        $pesananDibatalkan = $pesanans->filter(function ($p) {
            return $p->status_pesanan === 'Dibatalkan';
        })->values();

        // 6. Active pesanan jika dibutuhkan oleh view
        $activePesanan = $pesananDiproses->first() ?? $pesanans->first();

        // 7. Ambil data stok & pembayaran
        $stoks = Stok::where('kategori', '!=', 'Koin')->get();

        $pembayarans = Pembayaran::with(['pesanan.user', 'pesanan.layanan'])
            ->whereHas('pesanan.layanan', function ($query) {
                $query->where('jenis_layanan', '!=', 'Koin');
            })
            ->orderByDesc('id_pembayaran')
            ->get();

        // 8. Kirim SEMUA variabel yang dibutuhkan ke view admin.dashboard
        return view('admin.dashboard', compact(
            'layanans',
            'pesanans',
            'pesananMasuk',
            'pesananDiproses',
            'pesananSelesai',
            'pesananDibatalkan',
            'activePesanan',
            'stoks',
            'pembayarans'
        ));
    }

    /**
     * MENERIMA PESANAN MASUK
     */
    public function konfirmasiPesanan($idPesanan)
    {
        $pesanan = Pesanan::findOrFail($idPesanan);

        if (in_array($pesanan->status_pesanan, ['Dibatalkan', 'Selesai'])) {
            return back()->with('error', 'Pesanan tidak dapat diterima karena sudah dibatalkan atau selesai.');
        }

        if ($pesanan->status_pesanan === 'Diproses') {
            return back()->with('error', 'Pesanan sudah diterima sebelumnya.');
        }

        $pesanan->status_pesanan = 'Diproses';
        if (empty($pesanan->status_laundry) || $pesanan->status_laundry === 'Menunggu Antrean') {
            $pesanan->status_laundry = 'Menunggu Antrean';
        }
        $pesanan->save();

        return back()->with(
            'success',
            "Pesanan {$pesanan->kode_pesanan} berhasil diterima dan masuk ke tahap proses laundry."
        );
    }

    /**
     * MENOLAK PESANAN
     */
    public function tolakPesanan(Request $request, $idPesanan)
    {
        $request->validate([
            'alasan' => 'required|string|max:500',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.max' => 'Alasan maksimal 500 karakter.',
        ]);

        $pesanan = Pesanan::findOrFail($idPesanan);

        if (in_array($pesanan->status_pesanan, ['Selesai', 'Dibatalkan'])) {
            return back()->with(
                'error',
                'Pesanan yang sudah berstatus selesai atau dibatalkan tidak dapat ditolak.'
            );
        }

        DB::transaction(function () use ($pesanan, $request) {
            $pesanan->status_pesanan = 'Dibatalkan';
            $pesanan->catatan = trim(
                ($pesanan->catatan ?? '') .
                "\n[Ditolak Admin] " . $request->alasan
            );
            $pesanan->save();

            // Jika ada pembayaran pending, update statusnya juga
            if ($pesanan->pembayaran && $pesanan->pembayaran->status_pembayaran === 'Pending') {
                $pesanan->pembayaran->status_pembayaran = 'Dibatalkan';
                $pesanan->pembayaran->save();
            }
        });

        return back()->with(
            'success',
            "Pesanan {$pesanan->kode_pesanan} berhasil ditolak."
        );
    }

    /**
     * MEMPERBARUI STATUS LAUNDRY (Termasuk Siap Dikirim)
     */
    public function updateStatusLaundry(Request $request, $idPesanan)
    {
        $validStatuses = [
            'Menunggu Antrean',
            'Pencucian',
            'Pengeringan',
            'Setrika',
            'Siap Diambil',
            'Siap Dikirim',
            'Dalam Pengiriman',
            'Selesai',
        ];

        $request->validate([
            'status_laundry' => 'required|in:' . implode(',', $validStatuses),
        ], [
            'status_laundry.required' => 'Status pengerjaan wajib dipilih.',
            'status_laundry.in' => 'Pilihan status laundry tidak valid.',
        ]);

        $pesanan = Pesanan::with('layanan')->findOrFail($idPesanan);

        if ($pesanan->status_pesanan !== 'Diproses' && $pesanan->status_pesanan !== 'Menunggu Pembayaran' && $pesanan->status_pesanan !== 'Menunggu Verifikasi') {
            return back()->with(
                'error',
                'Status pesanan tidak dapat diubah karena sudah selesai atau dibatalkan.'
            );
        }

        $jenisLayanan = $pesanan->layanan
            ->map(fn($item) => strtolower($item->jenis_layanan ?? ''))
            ->toArray();

        if (in_array('koin', $jenisLayanan)) {
            return back()->with(
                'error',
                'Laundry Koin beroperasi mandiri dan tidak menggunakan tahapan pencucian manual.'
            );
        }

        $statusTujuan = $request->status_laundry;

        // Validasi pengiriman untuk non-pickup jika perlu
        $isPickup = in_array('antar jemput', $jenisLayanan) || in_array('pickup', $jenisLayanan);
        if (!$isPickup && in_array($statusTujuan, ['Siap Dikirim', 'Dalam Pengiriman'])) {
            // Berikan catatan bila pesanan bukan antar jemput
            return back()->with(
                'error',
                'Pilihan Siap Dikirim / Dalam Pengiriman khusus untuk layanan Antar Jemput.'
            );
        }

        $pesanan->status_laundry = $statusTujuan;
        $pesanan->status_pesanan = 'Diproses';

        if ($statusTujuan === 'Selesai') {
            $pesanan->status_pesanan = 'Selesai';
        }

        $pesanan->save();

        return back()->with(
            'success',
            "Status pengerjaan pesanan {$pesanan->kode_pesanan} berhasil diperbarui menjadi {$statusTujuan}."
        );
    }

    /**
     * VERIFIKASI PEMBAYARAN MANUAL
     */
    public function verifikasiPembayaran($idPembayaran)
    {
        $pembayaran = Pembayaran::with('pesanan')->findOrFail($idPembayaran);

        if ($pembayaran->status_pembayaran !== 'Pending') {
            return back()->with(
                'error',
                'Pembayaran ini sudah tidak berstatus Pending (sudah diproses sebelumnya).'
            );
        }

        if (!$pembayaran->pesanan) {
            return back()->with('error', 'Data pesanan terkait pembayaran ini tidak ditemukan.');
        }

        if ($pembayaran->pesanan->status_pesanan === 'Dibatalkan') {
            return back()->with(
                'error',
                'Pembayaran pesanan yang telah dibatalkan tidak dapat diverifikasi.'
            );
        }

        DB::transaction(function () use ($pembayaran) {
            $pembayaran->status_pembayaran = 'Lunas';
            $pembayaran->verifikator = Auth::user()->nama ?? 'Admin';
            $pembayaran->save();

            // Jika pesanan masih berstatus menunggu pembayaran/verifikasi, set ke Diproses
            if (in_array($pembayaran->pesanan->status_pesanan, ['Menunggu Pembayaran', 'Menunggu Verifikasi'])) {
                $pembayaran->pesanan->status_pesanan = 'Diproses';
                $pembayaran->pesanan->save();
            }
        });

        return back()->with(
            'success',
            "Pembayaran #{$pembayaran->kode_pembayaran} berhasil diverifikasi LUNAS."
        );
    }

    /**
     * MEMPERBARUI STOK BAHAN BAKU DAN KOIN
     */
    public function updateStok(Request $request, $idStok)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
        ], [
            'jumlah.required' => 'Jumlah stok wajib diisi.',
            'jumlah.numeric' => 'Jumlah stok harus berupa angka.',
            'jumlah.min' => 'Jumlah stok minimal 0.',
        ]);

        $stok = Stok::findOrFail($idStok);
        $stok->jumlah = $request->jumlah;

        $stok->status = $stok->jumlah <= 0
            ? 'Habis'
            : ($stok->jumlah <= $stok->batas_minimum
                ? 'Menipis'
                : 'Aman');

        $stok->save();

        return back()->with(
            'success',
            "Stok {$stok->nama_barang} berhasil diperbarui (Status: {$stok->status})."
        );
    }

    /**
     * MENJADWALKAN PENGIRIMAN
     */
    public function simpanPengiriman(Request $request)
    {
        $request->validate([
            'id_pesanan'    => 'required|exists:pesanan,id_pesanan',
            'nama_kurir'    => 'required|string|max:100',
            'no_hp_kurir'   => 'required|string|max:20',
            'kendaraan'     => 'required|string|max:50',
            'alamat_tujuan' => 'required|string|max:1000',
        ], [
            'id_pesanan.required'    => 'Pesanan wajib dipilih.',
            'nama_kurir.required'    => 'Nama kurir wajib diisi.',
            'no_hp_kurir.required'   => 'No. HP kurir wajib diisi.',
            'kendaraan.required'     => 'Kendaraan wajib diisi.',
            'alamat_tujuan.required' => 'Alamat pengantaran wajib diisi.',
        ]);

        $pesanan = Pesanan::findOrFail($request->id_pesanan);

        if ($pesanan->status_pesanan !== 'Diproses' && $pesanan->status_pesanan !== 'Selesai') {
            return back()->with(
                'error',
                'Pesanan harus berstatus Diproses terlebih dahulu sebelum dijadwalkan.'
            );
        }

        if (Pengiriman::where('id_pesanan', $pesanan->id_pesanan)->exists()) {
            return back()->with(
                'error',
                'Pesanan ini sudah memiliki jadwal pengiriman kurir.'
            );
        }

        $nextId = (Pengiriman::max('id_pengiriman') ?? 0) + 1;
        $kodePengiriman = 'KIRIM' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        Pengiriman::create([
            'kode_pengiriman'   => $kodePengiriman,
            'id_pesanan'        => $request->id_pesanan,
            'nama_kurir'        => $request->nama_kurir,
            'no_hp_kurir'       => $request->no_hp_kurir,
            'kendaraan'         => $request->kendaraan,
            'no_resi'           => 'RS-' . rand(100000, 999999),
            'alamat_tujuan'     => $request->alamat_tujuan,
            'status_pengiriman' => 'Dijadwalkan',
            'tanggal_kirim'     => now(),
        ]);

        // Opsional: perbarui status laundry menjadi Siap Dikirim jika belum
        if ($pesanan->status_laundry !== 'Dalam Pengiriman' && $pesanan->status_laundry !== 'Selesai') {
            $pesanan->status_laundry = 'Siap Dikirim';
            $pesanan->save();
        }

        return back()->with(
            'success',
            "Pengiriman kurir ({$request->nama_kurir}) berhasil dijadwalkan dengan kode {$kodePengiriman}."
        );
    }

    /**
     * MEMPERBARUI STATUS PENGIRIMAN
     */
    public function updateStatusPengiriman(Request $request, $idPengiriman)
    {
        $request->validate([
            'status_pengiriman' => 'required|in:Dijadwalkan,Dalam Perjalanan,Terkirim,Gagal',
        ], [
            'status_pengiriman.required' => 'Status pengiriman wajib dipilih.',
            'status_pengiriman.in' => 'Pilihan status pengiriman tidak valid.',
        ]);

        $pengiriman = Pengiriman::with('pesanan')->findOrFail($idPengiriman);
        $statusSekarang = $pengiriman->status_pengiriman;
        $statusTujuan = $request->status_pengiriman;

        if ($statusSekarang === 'Terkirim') {
            return back()->with('error', 'Pengiriman yang sudah Terkirim tidak dapat diubah lagi.');
        }

        $pengiriman->status_pengiriman = $statusTujuan;
        $pengiriman->save();

        if ($pengiriman->pesanan) {
            if ($statusTujuan === 'Dalam Perjalanan') {
                $pengiriman->pesanan->status_laundry = 'Dalam Pengiriman';
                $pengiriman->pesanan->save();
            } elseif ($statusTujuan === 'Terkirim') {
                $pengiriman->pesanan->status_laundry = 'Selesai';
                $pengiriman->pesanan->status_pesanan = 'Selesai';
                $pengiriman->pesanan->save();
            }
        }

        return back()->with(
            'success',
            "Status pengiriman kurir berhasil diperbarui menjadi {$statusTujuan}."
        );
    }
}
