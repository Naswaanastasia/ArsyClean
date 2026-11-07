<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Transaction;

class PelangganController extends Controller
{
    /**
     * Dashboard Pelanggan
     */
    public function dashboard()
    {
        $user = Auth::user();

        $layanans = Layanan::where('status', 'tersedia')
            ->whereIn('kode_layanan', [
                'L001',
                'L004',
                'L005',
                'L006'
            ])
            ->get();

        $ambilPesanan = fn () => Pesanan::with([
            'layanan',
            'pembayaran',
            'pengiriman',
            'ulasan'
        ])
            ->where('id_user', $user->id_user)
            ->orderByDesc('id_pesanan')
            ->get();

        $pesanans = $ambilPesanan();

        // CADANGAN WEBHOOK: pesanan yang masih menunggu pembayaran
        // ditanyakan langsung ke Midtrans (maksimal 2 pesanan terbaru
        // supaya halaman tetap cepat).
        $perluDicek = $pesanans
            ->filter(fn ($p) => $p->status_pesanan === 'Menunggu Pembayaran'
                && $p->pembayaran
                && $p->pembayaran->status_pembayaran === 'Pending'
                && $p->pembayaran->midtrans_order_id)
            ->take(2);

        if ($perluDicek->isNotEmpty()) {
            foreach ($perluDicek as $p) {
                $this->sinkronkanDenganMidtrans($p->pembayaran);
            }

            // Ambil ulang supaya status terbaru ikut tampil.
            $pesanans = $ambilPesanan();
        }

        // Hanya pesanan yang sudah dibayar dan sedang dikerjakan.
        // Pesanan batal / belum dibayar tidak boleh tampil sebagai
        // "Cucian Sedang Berlangsung".
        $activePesanan = $pesanans
            ->where('status_pesanan', 'Diproses')
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
     * Membuat Pesanan Pick Up Laundry dan QRIS Midtrans
     */
    public function storePesanan(Request $request)
    {
        $validated = $request->validate([
            'id_layanan' => 'required|integer|exists:layanan,id_layanan',
            'jumlah' => 'nullable|numeric|min:0.1',
            'pakaian' => 'nullable|array',
            'pakaian.*' => 'nullable|integer|min:0',
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required|date_format:H:i',
            'alamat' => 'required|string|max:1000',
            'metode_pembayaran' => 'required|in:QRIS',
            'catatan' => 'nullable|string|max:2000',
        ]);

        $layanan = Layanan::where('status', 'tersedia')
            ->whereIn('kode_layanan', [
                'L001',
                'L004',
                'L005',
                'L006'
            ])
            ->findOrFail($validated['id_layanan']);

        $kodeLayanan = $layanan->kode_layanan;
        $hargaSatuan = (float) $layanan->harga;

        // Daftar estimasi berat pakaian dalam gram.
        $beratPakaian = [
            'kaos' => [
                'nama' => 'Kaos',
                'berat' => 100
            ],
            'kemeja' => [
                'nama' => 'Kemeja',
                'berat' => 111
            ],
            'celana_bahan' => [
                'nama' => 'Celana Bahan',
                'berat' => 167
            ],
            'celana_jeans' => [
                'nama' => 'Celana Jeans',
                'berat' => 250
            ],
            'celana_pendek' => [
                'nama' => 'Celana Pendek',
                'berat' => 118
            ],
            'jaket_tipis' => [
                'nama' => 'Jaket Tipis',
                'berat' => 333
            ],
            'jaket_tebal' => [
                'nama' => 'Jaket Tebal',
                'berat' => 400
            ],
            'hoodie' => [
                'nama' => 'Hoodie',
                'berat' => 400
            ],
            'sweater' => [
                'nama' => 'Sweater',
                'berat' => 250
            ],
            'rok_tipis' => [
                'nama' => 'Rok Tipis',
                'berat' => 154
            ],
            'rok_tebal' => [
                'nama' => 'Rok Tebal',
                'berat' => 250
            ],
            'pakaian_anak' => [
                'nama' => 'Pakaian Anak-anak',
                'berat' => 63
            ],
            'pakaian_dalam' => [
                'nama' => 'Pakaian Dalam',
                'berat' => 50
            ],
            'handuk_kecil' => [
                'nama' => 'Handuk Kecil',
                'berat' => 154
            ],
            'handuk_besar' => [
                'nama' => 'Handuk Besar',
                'berat' => 333
            ],
            'baju_tidur' => [
                'nama' => 'Baju Tidur',
                'berat' => 154
            ],
        ];

        $jumlah = 0;
        $totalGram = 0;
        $totalPcs = 0;
        $rincianPakaian = [];

        /*
         * L001 dan L004 dihitung berdasarkan estimasi berat pakaian.
         */
        if (in_array($kodeLayanan, ['L001', 'L004'])) {

            $inputPakaian = $validated['pakaian'] ?? [];

            foreach ($inputPakaian as $jenis => $qty) {

                if (!array_key_exists($jenis, $beratPakaian)) {
                    throw ValidationException::withMessages([
                        'pakaian' => 'Jenis pakaian tidak valid.'
                    ]);
                }

                $qty = (int) $qty;

                if ($qty > 0) {
                    $beratPerPcs = $beratPakaian[$jenis]['berat'];

                    $totalGram += $beratPerPcs * $qty;
                    $totalPcs += $qty;

                    $rincianPakaian[] =
                        $beratPakaian[$jenis]['nama'] .
                        ': ' . $qty . ' pcs';
                }
            }

            if ($totalPcs < 1) {
                throw ValidationException::withMessages([
                    'pakaian' => 'Masukkan minimal satu pakaian.'
                ]);
            }

            // Konversi gram ke kilogram.
            $beratKg = $totalGram / 1000;

            // Pembulatan ke atas setiap 0,1 kg.
            $jumlah = ceil($beratKg * 10) / 10;
        }

        /*
         * L005 dan L006 dihitung berdasarkan jumlah barang.
         */
        elseif (in_array($kodeLayanan, ['L005', 'L006'])) {

            $jumlahInput = $validated['jumlah'] ?? 0;

            if (
                !is_numeric($jumlahInput) ||
                (float) $jumlahInput < 1 ||
                floor((float) $jumlahInput) != (float) $jumlahInput
            ) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Jumlah barang harus berupa angka bulat minimal 1 pcs.'
                ]);
            }

            $jumlah = (int) $jumlahInput;

            $namaBarang = $kodeLayanan === 'L005'
                ? 'Dry Clean Jas / Blazer'
                : 'Cuci Bed Cover / Selimut Besar';

            $rincianPakaian[] =
                $namaBarang . ': ' . $jumlah . ' pcs';
        }

        else {
            throw ValidationException::withMessages([
                'id_layanan' => 'Layanan yang dipilih tidak valid.'
            ]);
        }

        // Menghitung total harga berdasarkan tarif database.
        $totalHarga = round($jumlah * $hargaSatuan, 2);

        // Menyusun catatan pesanan.
        $catatanFinal = [];

        $catatanFinal[] =
            'Waktu penjemputan: ' . $validated['waktu'];

        $catatanFinal[] =
            'Rincian pesanan: ' . implode(', ', $rincianPakaian);

        if (in_array($kodeLayanan, ['L001', 'L004'])) {
            $catatanFinal[] =
                'Estimasi berat: ' .
                number_format($jumlah, 1, ',', '.') . ' kg';
        }

        if (!empty($validated['catatan'])) {
            $catatanFinal[] =
                'Catatan pelanggan: ' . trim($validated['catatan']);
        }

        $catatan = implode("\n", $catatanFinal);

        /*
         * PROSES PEMBUATAN PESANAN DAN QRIS MIDTRANS
         */
        $pembayaran = null;
        $pesanan = null;

        try {

            $userId = Auth::user()->id_user;

            // Membuat ID transaksi unik untuk Midtrans.
            $midtransOrderId = 'ARSY-' . Str::uuid();

            /*
             * Simpan pesanan dan pembayaran terlebih dahulu.
             */
            [$pesanan, $pembayaran] = DB::transaction(function () use (
                $validated,
                $layanan,
                $jumlah,
                $hargaSatuan,
                $totalHarga,
                $catatan,
                $userId,
                $midtransOrderId
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
                    'id_user' => $userId,
                    'tanggal_pesanan' => $validated['tanggal'],
                    'total_harga' => $totalHarga,
                    'status_pesanan' => 'Menunggu Pembayaran',
                    'status_laundry' => 'Menunggu Antrean',
                    'metode_pembayaran' => 'QRIS',
                    'alamat_penjemputan' => $validated['alamat'],
                    'catatan' => $catatan,
                ]);

                // Simpan detail layanan.
                $pesanan->layanan()->attach(
                    $layanan->id_layanan,
                    [
                        'jumlah' => $jumlah,
                        'harga_satuan' => $hargaSatuan,
                        'subtotal' => $totalHarga,
                    ]
                );

                $nextBayarId =
                    (Pembayaran::max('id_pembayaran') ?? 0) + 1;

                // Buat data pembayaran.
                $pembayaran = Pembayaran::create([
                    'kode_pembayaran' => 'BY' . str_pad(
                        $nextBayarId,
                        3,
                        '0',
                        STR_PAD_LEFT
                    ),
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_user' => $userId,
                    'jumlah_bayar' => $totalHarga,
                    'metode_pembayaran' => 'QRIS',
                    'status_pembayaran' => 'Pending',
                    'tanggal_bayar' => null,
                    'bukti_transfer' => null,
                    'verifikator' => null,
                    'midtrans_order_id' => $midtransOrderId,
                ]);

                return [$pesanan, $pembayaran];
            });

            /*
             * Mengatur koneksi Midtrans Sandbox.
             */
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = (bool) config('services.midtrans.is_production');
            Config::$isSanitized = true;

            /*
             * Meminta Midtrans membuat transaksi QRIS.
             */
            $response = CoreApi::charge([
                'payment_type' => 'qris',
                'transaction_details' => [
                    'order_id' => $midtransOrderId,
                    'gross_amount' => (int) round($totalHarga),
                ],
            ]);

            // Mengubah respons Midtrans menjadi array.
            $responseData = json_decode(
                json_encode($response),
                true
            );

            if (!is_array($responseData)) {
                throw new \RuntimeException(
                    'Respons Midtrans tidak dapat dibaca.'
                );
            }

            // Mengambil URL QRIS.
            $qrUrl = null;

            foreach ($responseData['actions'] ?? [] as $action) {

                if (($action['name'] ?? '') === 'generate-qr-code') {
                    $qrUrl = $action['url'] ?? null;
                    break;
                }
            }

            if (!$qrUrl) {
                throw new \RuntimeException(
                    'Midtrans tidak memberikan URL QRIS.'
                );
            }

            // Menyimpan respons transaksi Midtrans.
            $pembayaran->update([
                'midtrans_transaction_id' =>
                    $responseData['transaction_id'] ?? null,

                'qr_code_url' => $qrUrl,

                'midtrans_transaction_status' =>
                    $responseData['transaction_status'] ?? 'pending',

                'midtrans_response' => $responseData,
            ]);

            return redirect()
                ->route('pelanggan.dashboard')
                ->with(
                    'success',
                    'QRIS berhasil dibuat. Silakan lakukan pembayaran.'
                )
                ->with('qr_code_url', $qrUrl)
                ->with('kode_pesanan', $pesanan->kode_pesanan)
                ->with('total_bayar', (int) round($totalHarga));

        } catch (\Throwable $e) {

            report($e);

            // Jika pembayaran gagal dibuat, jangan proses laundry.
            if ($pembayaran) {

                $pembayaran->update([
                    'status_pembayaran' => 'Gagal',
                ]);

                $pembayaran->pesanan()->update([
                    'status_pesanan' => 'Dibatalkan',
                ]);
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'QRIS gagal dibuat. Silakan coba kembali.'
                );
        }
    }

    /**
     * Cek status pembayaran (dipanggil otomatis oleh halaman pelanggan
     * saat QR terbuka, supaya halaman ikut berubah setelah dibayar).
     *
     * Kalau pembayaran masih Pending, ditanyakan langsung ke Midtrans.
     * Ini cadangan kalau kabar (webhook) dari Midtrans belum sampai,
     * misalnya saat aplikasi masih berjalan di localhost.
     */
    public function statusPesanan($kode)
    {
        $pesanan = Pesanan::with('pembayaran')
            ->where('kode_pesanan', $kode)
            ->where('id_user', Auth::user()->id_user)
            ->firstOrFail();

        $pembayaran = $pesanan->pembayaran;

        if (
            $pembayaran
            && $pembayaran->status_pembayaran === 'Pending'
            && $pembayaran->midtrans_order_id
        ) {
            $this->sinkronkanDenganMidtrans($pembayaran);
            $pesanan = $pesanan->fresh('pembayaran');
        }

        return response()->json([
            'status_pesanan'    => $pesanan->status_pesanan,
            'status_pembayaran' => $pesanan->pembayaran->status_pembayaran ?? 'Pending',
        ]);
    }

    /**
     * Menanyakan status transaksi ke Midtrans, lalu memperbarui database.
     * Aturannya sama dengan MidtransWebhookController.
     */
    private function sinkronkanDenganMidtrans(Pembayaran $pembayaran): void
    {
        try {
            Config::$serverKey    = config('services.midtrans.server_key');
            Config::$isProduction = (bool) config('services.midtrans.is_production');

            // Batas waktu: kalau Midtrans lambat, jangan sampai halaman
            // menggantung (loading terus).
            Config::$curlOptions = [
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT        => 8,
                CURLOPT_HTTPHEADER     => [],
            ];

            $data = json_decode(
                json_encode(Transaction::status($pembayaran->midtrans_order_id)),
                true
            );

            if (!is_array($data)) {
                return;
            }

            DB::transaction(function () use ($pembayaran, $data) {

                $bayar = Pembayaran::where('id_pembayaran', $pembayaran->id_pembayaran)
                    ->lockForUpdate()
                    ->first();

                // Sudah diproses (mis. oleh webhook): jangan diubah lagi.
                if (!$bayar || $bayar->status_pembayaran !== 'Pending') {
                    return;
                }

                // Nominal dari Midtrans harus sama dengan tagihan.
                if (
                    (int) round((float) ($data['gross_amount'] ?? 0)) !==
                    (int) round((float) $bayar->jumlah_bayar)
                ) {
                    return;
                }

                $status = $data['transaction_status'] ?? '';
                $fraud  = $data['fraud_status'] ?? '';

                if (
                    $status === 'settlement' ||
                    ($status === 'capture' && $fraud === 'accept')
                ) {
                    $bayar->status_pembayaran = 'Lunas';
                    $bayar->tanggal_bayar = now();
                    $bayar->pesanan()->update(['status_pesanan' => 'Diproses']);

                } elseif (in_array($status, ['deny', 'cancel', 'expire', 'failure'])) {

                    $bayar->status_pembayaran = $status === 'cancel' ? 'Dibatalkan' : 'Gagal';
                    $bayar->pesanan()->update(['status_pesanan' => 'Dibatalkan']);

                } else {
                    // Masih pending: belum ada yang perlu diubah.
                    return;
                }

                $bayar->midtrans_transaction_id =
                    $data['transaction_id'] ?? $bayar->midtrans_transaction_id;
                $bayar->midtrans_transaction_status = $status;
                $bayar->midtrans_response = $data;
                $bayar->save();
            });

        } catch (\Throwable $e) {
            // 404 = transaksi belum dikenal Midtrans, wajar, abaikan.
            if ((int) $e->getCode() !== 404) {
                Log::warning('Gagal cek status ke Midtrans.', [
                    'order_id' => $pembayaran->midtrans_order_id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Memberikan Ulasan
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
            return back()->with(
                'error',
                'Pesanan ini sudah memiliki ulasan.'
            );
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
            ->with(
                'success',
                'Terima kasih atas ulasan dan penilaian Anda!'
            );
    }
}