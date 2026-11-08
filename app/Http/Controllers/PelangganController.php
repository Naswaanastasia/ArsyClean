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

        // CADANGAN SINKRONISASI MIDTRANS: pesanan yang masih pending ditanyakan ke Midtrans
        $perluDicek = $pesanans
            ->filter(fn ($p) => $p->status_pesanan === 'Menunggu Pembayaran'
                && $p->pembayaran
                && $p->pembayaran->status_pembayaran === 'Pending'
                && !empty($p->pembayaran->midtrans_order_id))
            ->take(3);

        if ($perluDicek->isNotEmpty()) {
            foreach ($perluDicek as $p) {
                $this->sinkronkanDenganMidtrans($p->pembayaran);
            }
            $pesanans = $ambilPesanan();
        }

        // Semua pesanan yang sedang diproses oleh admin
        $activePesanans = $pesanans
            ->where('status_pesanan', 'Diproses')
            ->where('status_laundry', '!=', 'Selesai')
            ->values();

        $activePesanan = $activePesanans->first();

        return view('pelanggan.dashboard', compact(
            'user',
            'layanans',
            'pesanans',
            'activePesanans',
            'activePesanan'
        ));
    }

    /**
     * Membuat Pesanan Baru (1 Layanan = 1 Kode Pesanan Unik)
     */
    public function storePesanan(Request $request)
    {
        $validated = $request->validate([
            'id_layanan'        => 'required|integer|exists:layanan,id_layanan',
            'jumlah'            => 'nullable|numeric|min:0.1',
            'pakaian'           => 'nullable|array',
            'pakaian.*'         => 'nullable|integer|min:0',
            'tanggal'           => 'required|date|after_or_equal:today',
            'waktu'             => 'required|date_format:H:i',
            'alamat'            => 'required|string|max:1000',
            'metode_pembayaran' => 'required|in:QRIS,QRIS_MANUAL,TUNAI,TRANSFER',
            'catatan'           => 'nullable|string|max:2000',
        ], [
            'id_layanan.required'        => 'Silakan pilih jenis layanan terlebih dahulu.',
            'id_layanan.exists'          => 'Layanan yang dipilih tidak tersedia.',
            'tanggal.required'           => 'Tanggal penjemputan wajib diisi.',
            'tanggal.after_or_equal'     => 'Tanggal penjemputan minimal hari ini.',
            'waktu.required'             => 'Waktu penjemputan wajib diisi.',
            'alamat.required'            => 'Alamat penjemputan wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        $layanan = Layanan::where('status', 'tersedia')
            ->findOrFail($validated['id_layanan']);

        $kodeLayanan = $layanan->kode_layanan;
        $hargaSatuan = (float) $layanan->harga;

        // Estimasi berat pakaian dalam gram
        $beratPakaian = [
            'kaos'          => ['nama' => 'Kaos', 'berat' => 100],
            'kemeja'        => ['nama' => 'Kemeja', 'berat' => 111],
            'celana_bahan'  => ['nama' => 'Celana Bahan', 'berat' => 167],
            'celana_jeans'  => ['nama' => 'Celana Jeans', 'berat' => 250],
            'celana_pendek' => ['nama' => 'Celana Pendek', 'berat' => 118],
            'jaket_tipis'   => ['nama' => 'Jaket Tipis', 'berat' => 333],
            'jaket_tebal'   => ['nama' => 'Jaket Tebal', 'berat' => 400],
            'hoodie'        => ['nama' => 'Hoodie', 'berat' => 400],
            'sweater'       => ['nama' => 'Sweater', 'berat' => 250],
            'rok_tipis'     => ['nama' => 'Rok Tipis', 'berat' => 154],
            'rok_tebal'     => ['nama' => 'Rok Tebal', 'berat' => 250],
            'pakaian_anak'  => ['nama' => 'Pakaian Anak-anak', 'berat' => 63],
            'pakaian_dalam' => ['nama' => 'Pakaian Dalam', 'berat' => 50],
            'handuk_kecil'  => ['nama' => 'Handuk Kecil', 'berat' => 154],
            'handuk_besar'  => ['nama' => 'Handuk Besar', 'berat' => 333],
            'baju_tidur'    => ['nama' => 'Baju Tidur', 'berat' => 154],
        ];

        $jumlah = 0;
        $totalGram = 0;
        $totalPcs = 0;
        $rincianPakaian = [];

        // Perhitungan untuk kiloan (L001 / L004) atau default reguler
        if (in_array($kodeLayanan, ['L001', 'L004']) || strtolower($layanan->satuan) === 'kg') {
            $inputPakaian = $validated['pakaian'] ?? [];

            foreach ($inputPakaian as $jenis => $qty) {
                if (!array_key_exists($jenis, $beratPakaian)) {
                    continue;
                }
                $qty = (int) $qty;
                if ($qty > 0) {
                    $beratPerPcs = $beratPakaian[$jenis]['berat'];
                    $totalGram += $beratPerPcs * $qty;
                    $totalPcs += $qty;
                    $rincianPakaian[] = $beratPakaian[$jenis]['nama'] . ': ' . $qty . ' pcs';
                }
            }

            if ($totalPcs < 1) {
                if (isset($validated['jumlah']) && (float) $validated['jumlah'] > 0) {
                    $jumlah = (float) $validated['jumlah'];
                    $rincianPakaian[] = 'Berat: ' . $jumlah . ' kg';
                } else {
                    throw ValidationException::withMessages([
                        'pakaian' => 'Silakan masukkan minimal 1 pakaian atau berat cucian.'
                    ]);
                }
            } else {
                $beratKg = $totalGram / 1000;
                $jumlah = ceil($beratKg * 10) / 10;
            }
        } else {
            // Perhitungan satuan (L005, L006)
            $jumlahInput = $validated['jumlah'] ?? 1;
            if (!is_numeric($jumlahInput) || (float) $jumlahInput < 1) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Jumlah barang harus berupa angka minimal 1 pcs.'
                ]);
            }
            $jumlah = (int) $jumlahInput;
            $rincianPakaian[] = $layanan->nama_layanan . ': ' . $jumlah . ' ' . $layanan->satuan;
        }

        $totalHarga = round($jumlah * $hargaSatuan, 2);

        // Catatan pesanan lengkap
        $catatanLines = [];
        $catatanLines[] = 'Waktu penjemputan: ' . $validated['waktu'];
        $catatanLines[] = 'Rincian: ' . implode(', ', $rincianPakaian);
        if (strtolower($layanan->satuan) === 'kg') {
            $catatanLines[] = 'Estimasi berat: ' . number_format($jumlah, 1, ',', '.') . ' kg';
        }
        if (!empty($validated['catatan'])) {
            $catatanLines[] = 'Catatan pelanggan: ' . trim($validated['catatan']);
        }
        $catatanPesanan = implode("\n", $catatanLines);

        $userId = Auth::user()->id_user;
        $nextId = (int) (Pesanan::max('id_pesanan') ?? 0) + 1;
        $kodePesanan = 'P' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $pembayaranMetode = $validated['metode_pembayaran'];
        $midtransOrderId = 'ARSY-' . Str::uuid();

        // Simpan Pesanan & Pembayaran ke Database
        [$pesanan, $pembayaran] = DB::transaction(function () use (
            $validated,
            $layanan,
            $jumlah,
            $hargaSatuan,
            $totalHarga,
            $catatanPesanan,
            $userId,
            $kodePesanan,
            $pembayaranMetode,
            $midtransOrderId
        ) {
            $pesanan = Pesanan::create([
                'kode_pesanan'       => $kodePesanan,
                'id_user'            => $userId,
                'tanggal_pesanan'    => $validated['tanggal'],
                'total_harga'        => $totalHarga,
                'status_pesanan'     => 'Menunggu Pembayaran',
                'status_laundry'     => 'Menunggu Antrean',
                'metode_pembayaran'  => $pembayaranMetode === 'TUNAI' ? 'Tunai' : 'QRIS',
                'alamat_penjemputan' => $validated['alamat'],
                'catatan'            => $catatanPesanan,
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
                'metode_pembayaran' => $pembayaranMetode === 'TUNAI' ? 'Tunai' : 'QRIS',
                'status_pembayaran' => 'Pending',
                'tanggal_bayar'     => null,
                'bukti_transfer'    => null,
                'verifikator'       => null,
                'midtrans_order_id' => $midtransOrderId,
            ]);

            return [$pesanan, $pembayaran];
        });

        // Alur Pembayaran: Midtrans vs QR Manual Fallback
        $qrUrl = null;
        $isManualFallback = false;

        if ($pembayaranMetode === 'QRIS') {
            try {
                // Cek konfigurasi server key Midtrans
                $serverKey = config('services.midtrans.server_key');
                if (empty($serverKey) || str_contains($serverKey, 'your-') || strlen($serverKey) < 10) {
                    throw new \RuntimeException('Midtrans belum dikonfigurasi/acc.');
                }

                Config::$serverKey = $serverKey;
                Config::$isProduction = (bool) config('services.midtrans.is_production');
                Config::$isSanitized = true;

                $response = CoreApi::charge([
                    'payment_type' => 'qris',
                    'transaction_details' => [
                        'order_id'     => $midtransOrderId,
                        'gross_amount' => (int) round($totalHarga),
                    ],
                ]);

                $responseData = json_decode(json_encode($response), true);

                foreach ($responseData['actions'] ?? [] as $action) {
                    if (($action['name'] ?? '') === 'generate-qr-code') {
                        $qrUrl = $action['url'] ?? null;
                        break;
                    }
                }

                if (!$qrUrl) {
                    throw new \RuntimeException('Midtrans tidak menyediakan URL QRIS.');
                }

                $pembayaran->update([
                    'midtrans_transaction_id'     => $responseData['transaction_id'] ?? null,
                    'qr_code_url'                 => $qrUrl,
                    'midtrans_transaction_status' => $responseData['transaction_status'] ?? 'pending',
                    'midtrans_response'           => $responseData,
                ]);

            } catch (\Throwable $e) {
                // FALLBACK KE QR MANUAL JIKA MIDTRANS BELUM ACC / GAGAL
                Log::info("Midtrans unavailable, falling back to QR Manual: " . $e->getMessage());
                $isManualFallback = true;
                $qrUrl = route('pelanggan.qris.manual', ['kode' => $pesanan->kode_pesanan]);

                $pembayaran->update([
                    'qr_code_url'                 => $qrUrl,
                    'midtrans_transaction_status' => 'manual_qr',
                ]);
            }
        } elseif ($pembayaranMetode === 'QRIS_MANUAL' || $pembayaranMetode === 'TRANSFER') {
            $qrUrl = route('pelanggan.qris.manual', ['kode' => $pesanan->kode_pesanan]);
            $pembayaran->update([
                'qr_code_url'                 => $qrUrl,
                'midtrans_transaction_status' => 'manual_qr',
            ]);
        }

        $pesanSukses = $isManualFallback
            ? "Pesanan #{$pesanan->kode_pesanan} berhasil dibuat! Silakan scan QRIS Manual ArsyClean atau transfer manual."
            : "Pesanan #{$pesanan->kode_pesanan} berhasil dibuat! Silakan lakukan pembayaran.";

        return redirect()
            ->route('pelanggan.dashboard')
            ->with('success', $pesanSukses)
            ->with('qr_code_url', $qrUrl)
            ->with('kode_pesanan', $pesanan->kode_pesanan)
            ->with('total_bayar', (int) round($totalHarga));
    }

    /**
     * Endpoint Generate QRIS Manual ArsyClean (SVG Dinamis & Ramah Scan)
     */
    public function generateQrisManual($kode)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kode)->firstOrFail();
        $total = number_format($pesanan->total_harga, 0, ',', '.');

        // Menghasilkan SVG QRIS Manual ArsyClean dengan logo, NMID, dan nominal
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 460" width="400" height="460">
            <defs>
                <linearGradient id="qrisGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#0f2a5c" />
                    <stop offset="100%" stop-color="#1e3a8a" />
                </linearGradient>
            </defs>
            <rect width="400" height="460" rx="20" fill="#ffffff" stroke="#e2e8f0" stroke-width="2"/>
            <rect x="0" y="0" width="400" height="70" rx="20" fill="url(#qrisGrad)"/>
            <rect x="0" y="50" width="400" height="20" fill="url(#qrisGrad)"/>
            <text x="200" y="34" font-family="sans-serif" font-weight="900" font-size="20" fill="#ffffff" text-anchor="middle">QRIS - PEMBAYARAN ARSYCLEAN</text>
            <text x="200" y="54" font-family="sans-serif" font-weight="600" font-size="11" fill="#93c5fd" text-anchor="middle">NMID: ID1020261189442 - SATU QR UNTUK SEMUA</text>
            
            <!-- QR Frame Visual -->
            <rect x="50" y="90" width="300" height="300" rx="16" fill="#f8fafc" stroke="#cbd5e1" stroke-width="2"/>
            
            <!-- Posisi Marker QR Code -->
            <rect x="70" y="110" width="60" height="60" rx="8" fill="#0f2a5c"/>
            <rect x="80" y="120" width="40" height="40" rx="4" fill="#ffffff"/>
            <rect x="90" y="130" width="20" height="20" rx="2" fill="#0f2a5c"/>

            <rect x="270" y="110" width="60" height="60" rx="8" fill="#0f2a5c"/>
            <rect x="280" y="120" width="40" height="40" rx="4" fill="#ffffff"/>
            <rect x="290" y="130" width="20" height="20" rx="2" fill="#0f2a5c"/>

            <rect x="70" y="310" width="60" height="60" rx="8" fill="#0f2a5c"/>
            <rect x="80" y="320" width="40" height="40" rx="4" fill="#ffffff"/>
            <rect x="90" y="330" width="20" height="20" rx="2" fill="#0f2a5c"/>

            <!-- Pattern Dots -->
            <circle cx="170" cy="140" r="8" fill="#1e3a8a"/>
            <circle cx="210" cy="140" r="8" fill="#1e3a8a"/>
            <circle cx="240" cy="170" r="8" fill="#1e3a8a"/>
            <circle cx="150" cy="180" r="8" fill="#1e3a8a"/>
            <circle cx="190" cy="190" r="8" fill="#1e3a8a"/>
            <circle cx="170" cy="220" r="8" fill="#1e3a8a"/>
            <circle cx="230" cy="220" r="8" fill="#1e3a8a"/>
            <circle cx="200" cy="250" r="8" fill="#1e3a8a"/>
            <circle cx="250" cy="280" r="8" fill="#1e3a8a"/>
            <circle cx="180" cy="300" r="8" fill="#1e3a8a"/>
            <circle cx="220" cy="320" r="8" fill="#1e3a8a"/>
            <circle cx="260" cy="340" r="8" fill="#1e3a8a"/>

            <!-- Center Badge -->
            <rect x="160" y="200" width="80" height="40" rx="8" fill="#ffffff" stroke="#0f2a5c" stroke-width="2"/>
            <text x="200" y="226" font-family="sans-serif" font-weight="900" font-size="14" fill="#0f2a5c" text-anchor="middle">ARSY</text>

            <!-- Bottom Information -->
            <text x="200" y="415" font-family="sans-serif" font-weight="800" font-size="14" fill="#0f2a5c" text-anchor="middle">Kode Pesanan: ' . htmlspecialchars($pesanan->kode_pesanan) . '</text>
            <text x="200" y="440" font-family="sans-serif" font-weight="700" font-size="16" fill="#16a34a" text-anchor="middle">Nominal: Rp ' . htmlspecialchars($total) . '</text>
        </svg>';

        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Cek status pesanan realtime sesuai kode pesanan spesifik
     */
    public function statusPesanan($kode)
    {
        $pesanan = Pesanan::with(['pembayaran', 'layanan', 'pengiriman'])
            ->where('kode_pesanan', $kode)
            ->where('id_user', Auth::user()->id_user)
            ->firstOrFail();

        $pembayaran = $pesanan->pembayaran;

        if (
            $pembayaran
            && $pembayaran->status_pembayaran === 'Pending'
            && !empty($pembayaran->midtrans_order_id)
        ) {
            $this->sinkronkanDenganMidtrans($pembayaran);
            $pesanan = $pesanan->fresh(['pembayaran', 'layanan', 'pengiriman']);
        }

        return response()->json([
            'kode_pesanan'      => $pesanan->kode_pesanan,
            'status_pesanan'    => $pesanan->status_pesanan,
            'status_laundry'    => $pesanan->status_laundry,
            'status_pembayaran' => $pesanan->pembayaran->status_pembayaran ?? 'Pending',
            'metode_pembayaran' => $pesanan->metode_pembayaran,
        ]);
    }

    /**
     * Detail Pesanan (Untuk Modal Detail Interaktif & Tombol Aksi)
     */
    public function detailPesanan($idPesanan)
    {
        $pesanan = Pesanan::with(['layanan', 'pembayaran', 'pengiriman', 'ulasan'])
            ->where('id_user', Auth::user()->id_user)
            ->findOrFail($idPesanan);

        return response()->json([
            'success' => true,
            'data'    => [
                'id_pesanan'         => $pesanan->id_pesanan,
                'kode_pesanan'       => $pesanan->kode_pesanan,
                'tanggal_pesanan'    => $pesanan->tanggal_pesanan ? $pesanan->tanggal_pesanan->format('d M Y') : '-',
                'total_harga'        => $pesanan->total_harga,
                'total_harga_format' => 'Rp ' . number_format($pesanan->total_harga, 0, ',', '.'),
                'status_pesanan'     => $pesanan->status_pesanan,
                'status_laundry'     => $pesanan->status_laundry,
                'metode_pembayaran'  => $pesanan->metode_pembayaran,
                'status_pembayaran'  => $pesanan->pembayaran->status_pembayaran ?? 'Pending',
                'alamat_penjemputan' => $pesanan->alamat_penjemputan ?? '-',
                'catatan'            => $pesanan->catatan ?? '-',
                'qr_code_url'        => $pesanan->pembayaran->qr_code_url ?? null,
                'layanan'            => $pesanan->layanan->map(function ($l) {
                    return [
                        'nama_layanan' => $l->nama_layanan,
                        'jumlah'       => $l->pivot->jumlah,
                        'satuan'       => $l->satuan,
                        'harga_satuan' => $l->pivot->harga_satuan,
                        'subtotal'     => $l->pivot->subtotal,
                    ];
                }),
                'pengiriman'         => $pesanan->pengiriman ? [
                    'nama_kurir'        => $pesanan->pengiriman->nama_kurir,
                    'no_hp_kurir'       => $pesanan->pengiriman->no_hp_kurir,
                    'status_pengiriman' => $pesanan->pengiriman->status_pengiriman,
                    'no_resi'           => $pesanan->pengiriman->no_resi,
                ] : null,
                'tahap_laundry'      => $pesanan->daftarTahapLaundry(),
            ]
        ]);
    }

    /**
     * Cetak Nota / Struk PDF Kapan Saja
     */
    public function cetakNotaPdf($idPesanan)
    {
        $pesanan = Pesanan::with(['layanan', 'pembayaran', 'user', 'pengiriman'])
            ->where('id_user', Auth::user()->id_user)
            ->findOrFail($idPesanan);

        return view('pelanggan.cetak_nota', compact('pesanan'));
    }

    /**
     * Upload Bukti Pembayaran Manual
     */
    public function uploadBuktiPembayaran(Request $request, $idPesanan)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'bukti_transfer.required' => 'File bukti transfer wajib diunggah.',
            'bukti_transfer.image'    => 'File harus berupa gambar.',
            'bukti_transfer.mimes'    => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'bukti_transfer.max'      => 'Ukuran gambar maksimal 3 MB.',
        ]);

        $pesanan = Pesanan::with('pembayaran')
            ->where('id_user', Auth::user()->id_user)
            ->findOrFail($idPesanan);

        if ($request->hasFile('bukti_transfer')) {
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

            return back()->with('success', "Bukti transfer pesanan #{$pesanan->kode_pesanan} berhasil diunggah dan sedang menunggu verifikasi admin.");
        }

        return back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }

    /**
     * Memberikan Ulasan
     */
    public function storeUlasan(Request $request, $idPesanan)
    {
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Rating bintang wajib dipilih (1 s/d 5).',
        ]);

        $pesanan = Pesanan::where('id_pesanan', $idPesanan)
            ->where('id_user', Auth::user()->id_user)
            ->where('status_laundry', 'Selesai')
            ->firstOrFail();

        if ($pesanan->ulasan) {
            return back()->with('error', 'Pesanan ini sudah memiliki ulasan.');
        }

        $nextId = (int) (Ulasan::max('id_ulasan') ?? 0) + 1;

        Ulasan::create([
            'kode_ulasan'    => 'UL' . str_pad($nextId, 3, '0', STR_PAD_LEFT),
            'id_pesanan'     => $pesanan->id_pesanan,
            'id_user'        => Auth::user()->id_user,
            'rating'         => $request->rating,
            'komentar'       => $request->komentar,
            'tanggal_ulasan' => now(),
        ]);

        return redirect()
            ->route('pelanggan.dashboard')
            ->with('success', 'Terima kasih atas ulasan dan penilaian bintang yang Anda berikan!');
    }

    /**
     * Sinkronkan Status dengan Midtrans
     */
    private function sinkronkanDenganMidtrans(Pembayaran $pembayaran): void
    {
        try {
            $serverKey = config('services.midtrans.server_key');
            if (empty($serverKey) || str_contains($serverKey, 'your-')) {
                return;
            }

            Config::$serverKey = $serverKey;
            Config::$isProduction = (bool) config('services.midtrans.is_production');
            Config::$curlOptions = [
                CURLOPT_CONNECTTIMEOUT => 4,
                CURLOPT_TIMEOUT        => 6,
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

                if (!$bayar || $bayar->status_pembayaran !== 'Pending') {
                    return;
                }

                $status = $data['transaction_status'] ?? '';
                $fraud  = $data['fraud_status'] ?? '';

                if ($status === 'settlement' || ($status === 'capture' && $fraud === 'accept')) {
                    $bayar->status_pembayaran = 'Lunas';
                    $bayar->tanggal_bayar = now();
                    $bayar->pesanan()->update(['status_pesanan' => 'Diproses']);
                } elseif (in_array($status, ['deny', 'cancel', 'expire', 'failure'])) {
                    $bayar->status_pembayaran = $status === 'cancel' ? 'Dibatalkan' : 'Gagal';
                    $bayar->pesanan()->update(['status_pesanan' => 'Dibatalkan']);
                } else {
                    return;
                }

                $bayar->midtrans_transaction_id = $data['transaction_id'] ?? $bayar->midtrans_transaction_id;
                $bayar->midtrans_transaction_status = $status;
                $bayar->midtrans_response = $data;
                $bayar->save();
            });

        } catch (\Throwable $e) {
            // Abaikan kesalahan koneksi ke sandbox
        }
    }
}