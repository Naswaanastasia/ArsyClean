<?php

namespace App\Http\Controllers;



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
        // Hanya mengambil pesanan Laundry Reguler
        $pesanans = Pesanan::with([
            'user',
            'layanan',
            'pembayaran'
            ])
            ->whereHas('layanan', function ($query) {
                $query->where('jenis_layanan', 'Reguler');
                })
                ->orderBy('id_pesanan', 'desc')
                ->get();
                // Mengambil stok bahan baku, tanpa stok koin
                $stoks = Stok::where('kategori', '!=', 'Koin')->get();
                
                // Mengambil data pembayaran pesanan Reguler saja
                $pembayarans = Pembayaran::with([
                    'pesanan.user',
                    'pesanan.layanan'
                    ])
                    ->whereHas('pesanan.layanan', function ($query) {
                        $query->where('jenis_layanan', 'Reguler');
                        })
                        ->orderBy('id_pembayaran', 'desc')
                        ->get();
                        
                        return view('admin.dashboard', compact(
                            'pesanans',
                            'stoks',
                            'pembayarans'
                            ));
    }

    /**
     * MENERIMA PESANAN
     */
    public function konfirmasiPesanan($idPesanan)
    {
        $pesanan = Pesanan::findOrFail($idPesanan);

        if (in_array($pesanan->status_pesanan, ['Dibatalkan', 'Selesai'])) {
            return back()->with('error', 'Pesanan tidak dapat diterima.');
        }

        if ($pesanan->status_pesanan === 'Diproses') {
            return back()->with('error', 'Pesanan sudah diterima sebelumnya.');
        }

        $pesanan->status_pesanan = 'Diproses';
        $pesanan->save();

        return back()->with(
            'success',
            "Pesanan {$pesanan->kode_pesanan} berhasil diterima."
        );
    }

    /**
     * MENOLAK PESANAN
     */
    public function tolakPesanan(Request $request, $idPesanan)
    {
        $request->validate([
            'alasan' => 'required|string|max:500',
        ]);

        $pesanan = Pesanan::findOrFail($idPesanan);

        if (in_array($pesanan->status_pesanan, ['Diproses', 'Selesai', 'Dibatalkan'])) {
            return back()->with(
                'error',
                'Pesanan yang sudah diproses tidak dapat ditolak melalui proses ini.'
            );
        }

        DB::transaction(function () use ($pesanan, $request) {
            $pesanan->status_pesanan = 'Dibatalkan';

            $pesanan->catatan = trim(
                ($pesanan->catatan ?? '') .
                "\n[Ditolak Admin] " . $request->alasan
            );

            $pesanan->save();
        });

        return back()->with(
            'success',
            "Pesanan {$pesanan->kode_pesanan} berhasil ditolak."
        );
    }

    /**
     * MEMPERBARUI STATUS LAUNDRY
     */
    public function updateStatusLaundry(Request $request, $idPesanan)
    {
        $request->validate([
            'status_laundry' => 'required|in:Menunggu Antrean,Pencucian,Pengeringan,Setrika,Siap Diambil,Dalam Pengiriman,Selesai',
        ]);

        $pesanan = Pesanan::with('layanan')->findOrFail($idPesanan);

        if ($pesanan->status_pesanan !== 'Diproses') {
            return back()->with(
                'error',
                'Pesanan harus diterima Admin sebelum diproses.'
            );
        }

        $jenisLayanan = $pesanan->layanan
            ->pluck('jenis_layanan')
            ->map(fn($jenis) => strtolower($jenis))
            ->toArray();

        if (in_array('koin', $jenisLayanan)) {
            return back()->with(
                'error',
                'Laundry Koin tidak menggunakan tahapan pencucian petugas.'
            );
        }

        $statusBerikutnya = [
            'Menunggu Antrean',
            'Pencucian',
            'Pengeringan',
            'Setrika',
            'Siap Diambil',
            'Dalam Pengiriman',
            'Selesai',
        ];

        $statusSekarang = $pesanan->status_laundry;
        $statusTujuan = $request->status_laundry;

        // Laundry Reguler tidak menggunakan pengiriman kurir.
        $isPickup = in_array('antar jemput', $jenisLayanan)
            || in_array('pickup', $jenisLayanan);

        if (!$isPickup && $statusTujuan === 'Dalam Pengiriman') {
            return back()->with(
                'error',
                'Laundry Reguler tidak menggunakan proses pengiriman kurir.'
            );
        }

        $posisiSekarang = array_search($statusSekarang, $statusBerikutnya);
        $posisiTujuan = array_search($statusTujuan, $statusBerikutnya);

        if ($posisiSekarang === false || $posisiTujuan === false) {
            return back()->with('error', 'Status laundry tidak valid.');
        }

        if ($posisiTujuan !== $posisiSekarang + 1) {
            return back()->with(
                'error',
                'Status laundry harus diperbarui secara berurutan.'
            );
        }

        $pesanan->status_laundry = $statusTujuan;

        if ($statusTujuan === 'Selesai') {
            $pesanan->status_pesanan = 'Selesai';
        }

        $pesanan->save();

        return back()->with(
            'success',
            "Status pesanan {$pesanan->kode_pesanan} diperbarui menjadi {$statusTujuan}."
        );
    }

    /**
     * VERIFIKASI PEMBAYARAN
     */
    public function verifikasiPembayaran($idPembayaran)
    {
        $pembayaran = Pembayaran::with('pesanan')
            ->findOrFail($idPembayaran);

        if ($pembayaran->status_pembayaran !== 'Pending') {
            return back()->with(
                'error',
                'Pembayaran ini sudah tidak berstatus Pending.'
            );
        }

        if (!$pembayaran->pesanan) {
            return back()->with('error', 'Data pesanan tidak ditemukan.');
        }

        if ($pembayaran->pesanan->status_pesanan === 'Dibatalkan') {
            return back()->with(
                'error',
                'Pembayaran pesanan yang dibatalkan tidak dapat diverifikasi.'
            );
        }

        // Memastikan nominal pembayaran sesuai total pesanan.
        if (
            round((float) $pembayaran->jumlah_bayar, 2) !==
            round((float) $pembayaran->pesanan->total_harga, 2)
        ) {
            return back()->with(
                'error',
                'Nominal pembayaran tidak sesuai dengan total pesanan.'
            );
        }

        DB::transaction(function () use ($pembayaran) {
            $pembayaran->status_pembayaran = 'Lunas';
            $pembayaran->verifikator = Auth::user()->nama ?? 'Admin';
            $pembayaran->save();

            if (
                $pembayaran->pesanan->status_pesanan === 'Menunggu Pembayaran'
            ) {
                $pembayaran->pesanan->status_pesanan = 'Menunggu Verifikasi';
                $pembayaran->pesanan->save();
            }
        });

        return back()->with(
            'success',
            "Pembayaran {$pembayaran->kode_pembayaran} berhasil diverifikasi."
        );
    }

    /**
     * MEMPERBARUI STOK BAHAN BAKU DAN KOIN
     */
    public function updateStok(Request $request, $idStok)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
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
            "Stok {$stok->nama_barang} berhasil diperbarui."
        );
    }

    /**
     * MENJADWALKAN PENGIRIMAN
     */
    public function simpanPengiriman(Request $request)
    {
        $request->validate([
            'id_pesanan' => 'required|exists:pesanan,id_pesanan',
            'nama_kurir' => 'required|string|max:100',
            'no_hp_kurir' => 'required|string|max:20',
            'kendaraan' => 'required|string|max:50',
            'alamat_tujuan' => 'required|string',
        ]);

        $pesanan = Pesanan::findOrFail($request->id_pesanan);

        if ($pesanan->status_pesanan !== 'Diproses') {
            return back()->with(
                'error',
                'Pesanan harus diterima terlebih dahulu.'
            );
        }

        if (Pengiriman::where('id_pesanan', $pesanan->id_pesanan)->exists()) {
            return back()->with(
                'error',
                'Pesanan ini sudah memiliki data pengiriman.'
            );
        }

        $nextId = (Pengiriman::max('id_pengiriman') ?? 0) + 1;

        $kodePengiriman = 'KIRIM' . str_pad(
            $nextId,
            3,
            '0',
            STR_PAD_LEFT
        );

        Pengiriman::create([
            'kode_pengiriman' => $kodePengiriman,
            'id_pesanan' => $request->id_pesanan,
            'nama_kurir' => $request->nama_kurir,
            'no_hp_kurir' => $request->no_hp_kurir,
            'kendaraan' => $request->kendaraan,
            'no_resi' => 'RS-' . rand(100000, 999999),
            'alamat_tujuan' => $request->alamat_tujuan,
            'status_pengiriman' => 'Dijadwalkan',
            'tanggal_kirim' => now(),
        ]);

        return back()->with(
            'success',
            "Pengiriman berhasil dijadwalkan dengan kurir {$request->nama_kurir}."
        );
    }

    /**
     * MEMPERBARUI STATUS PENGIRIMAN
     */
    public function updateStatusPengiriman(Request $request, $idPengiriman)
    {
        $request->validate([
            'status_pengiriman' => 'required|in:Dijadwalkan,Dalam Perjalanan,Terkirim,Gagal',
        ]);

        $pengiriman = Pengiriman::findOrFail($idPengiriman);

        $urutanStatus = [
            'Dijadwalkan',
            'Dalam Perjalanan',
            'Terkirim',
        ];

        $statusSekarang = $pengiriman->status_pengiriman;
        $statusTujuan = $request->status_pengiriman;

        if ($statusTujuan === 'Gagal') {
            if (in_array($statusSekarang, ['Terkirim', 'Gagal'])) {
                return back()->with(
                    'error',
                    'Status pengiriman tidak dapat diubah.'
                );
            }
        } else {
            $posisiSekarang = array_search($statusSekarang, $urutanStatus);
            $posisiTujuan = array_search($statusTujuan, $urutanStatus);

            if (
                $posisiSekarang === false ||
                $posisiTujuan !== $posisiSekarang + 1
            ) {
                return back()->with(
                    'error',
                    'Status pengiriman harus diperbarui secara berurutan.'
                );
            }
        }

        $pengiriman->status_pengiriman = $statusTujuan;
        $pengiriman->save();

        return back()->with(
            'success',
            "Status pengiriman berhasil diperbarui menjadi {$statusTujuan}."
        );
    }
}
