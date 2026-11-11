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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        // Pesanan yang sedang aktif diproses
        $activePesanans = $pesanans
            ->whereIn('status_pesanan', ['Menunggu Pembayaran', 'Menunggu Verifikasi', 'Diproses'])
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
     * Halaman Khusus: Katalog Layanan Laundry
     */
    public function layanan()
    {
        $user = Auth::user();
        $layanans = Layanan::where('status', 'tersedia')->get();
        return view('pelanggan.layanan', compact('user', 'layanans'));
    }

    /**
     * Halaman Khusus: Riwayat Pesanan Pelanggan
     */
    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $query = Pesanan::with(['layanan', 'pembayaran', 'pengiriman', 'ulasan'])
            ->where('id_user', $user->id_user);

        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }

        $pesanans = $query->orderByDesc('id_pesanan')->get();
        return view('pelanggan.riwayat', compact('user', 'pesanans'));
    }

    /**
     * Membuat Pesanan Baru (QRIS Manual by_onest + Tunai)
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
            'metode_pembayaran' => 'required|in:QRIS,TUNAI,TRANSFER,QRIS_MANUAL',
            'catatan'           => 'nullable|string|max:2000',
        ], [
            'id_layanan.required'        => 'Silakan pilih jenis paket layanan terlebih dahulu.',
            'id_layanan.exists'          => 'Layanan yang dipilih tidak valid atau tidak tersedia.',
            'tanggal.required'           => 'Tanggal penjemputan cucian wajib diisi.',
            'tanggal.after_or_equal'     => 'Tanggal penjemputan minimal hari ini.',
            'waktu.required'             => 'Jam penjemputan cucian wajib ditentukan.',
            'alamat.required'            => 'Alamat penjemputan cucian wajib diisi dengan jelas.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        $layanan = Layanan::where('status', 'tersedia')
            ->findOrFail($validated['id_layanan']);

        $hargaSatuan = (float) $layanan->harga;
        $isKiloan = strtolower($layanan->satuan) === 'kg';

        // Daftar jenis pakaian dan estimasi berat (gram)
        $daftarPakaian = [
            'kaos'           => ['nama' => 'Kaos / T-Shirt', 'gram' => 120],
            'kemeja'         => ['nama' => 'Kemeja', 'gram' => 150],
            'celana_panjang' => ['nama' => 'Celana Panjang / Jeans', 'gram' => 300],
            'celana_pendek'  => ['nama' => 'Celana Pendek', 'gram' => 150],
            'jaket'          => ['nama' => 'Jaket / Sweater / Hoodie', 'gram' => 450],
            'pakaian_dalam'  => ['nama' => 'Pakaian Dalam / Kaos Kaki', 'gram' => 50],
            'baju_muslim'    => ['nama' => 'Gamis / Baju Muslim', 'gram' => 300],
            'sprei'          => ['nama' => 'Sprei / Sarung Bantal', 'gram' => 400],
            'lainnya'        => ['nama' => 'Pakaian / Item Lainnya', 'gram' => 150],
        ];

        $totalPcs = 0;
        $totalGram = 0;
        $rincianPakaian = [];

        if (!empty($validated['pakaian']) && is_array($validated['pakaian'])) {
            foreach ($validated['pakaian'] as $key => $qty) {
                $qty = (int) $qty;
                if ($qty > 0 && isset($daftarPakaian[$key])) {
                    $totalPcs += $qty;
                    $gramItem = $daftarPakaian[$key]['gram'] * $qty;
                    $totalGram += $gramItem;
                    $rincianPakaian[] = "{$daftarPakaian[$key]['nama']}: {$qty} pcs";
                }
            }
        }

        // Tentukan jumlah unit (kg atau pcs)
        if ($isKiloan) {
            if ($totalPcs > 0) {
                $beratKg = max(1.0, round($totalGram / 1000, 1));
                $jumlah = $beratKg;
            } else {
                $jumlah = max(1.0, (float) ($validated['jumlah'] ?? 1.0));
            }
        } else {
            $jumlah = $totalPcs > 0 ? $totalPcs : max(1, (int) ($validated['jumlah'] ?? 1));
        }

        $totalHarga = round($jumlah * $hargaSatuan);

        // Susun catatan rinci
        $catatanLines = [];
        $catatanLines[] = 'Jam Penjemputan: ' . $validated['waktu'];
        if ($totalPcs > 0) {
            $catatanLines[] = 'Total Pakaian: ' . $totalPcs . ' potong (' . implode(', ', $rincianPakaian) . ')';
        }
        if ($isKiloan) {
            $catatanLines[] = 'Estimasi Berat: ' . number_format($jumlah, 1, ',', '.') . ' kg';
        } else {
            $catatanLines[] = 'Jumlah Satuan: ' . $jumlah . ' ' . $layanan->satuan;
        }
        if (!empty($validated['catatan'])) {
            $catatanLines[] = 'Catatan Khusus Pelanggan: ' . trim($validated['catatan']);
        }
        $catatanPesanan = implode("\n", $catatanLines);

        $userId = Auth::user()->id_user;
        $nextId = (int) (Pesanan::max('id_pesanan') ?? 0) + 1;
        $kodePesanan = 'P' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $metodeBayar = in_array($validated['metode_pembayaran'], ['TUNAI']) ? 'Tunai' : 'QRIS';

        // Simpan ke database
        [$pesanan, $pembayaran] = DB::transaction(function () use (
            $validated,
            $layanan,
            $jumlah,
            $hargaSatuan,
            $totalHarga,
            $catatanPesanan,
            $userId,
            $kodePesanan,
            $metodeBayar
        ) {
            $pesanan = Pesanan::create([
                'kode_pesanan'       => $kodePesanan,
                'id_user'            => $userId,
                'tanggal_pesanan'    => $validated['tanggal'],
                'total_harga'        => $totalHarga,
                'status_pesanan'     => 'Menunggu Pembayaran',
                'status_laundry'     => 'Menunggu Antrean',
                'metode_pembayaran'  => $metodeBayar,
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
                'metode_pembayaran' => $metodeBayar,
                'status_pembayaran' => 'Pending',
                'tanggal_bayar'     => null,
                'bukti_transfer'    => null,
                'verifikator'       => null,
            ]);

            return [$pesanan, $pembayaran];
        });

        // Flash message & QR data
        if ($metodeBayar === 'QRIS') {
            return redirect()
                ->route('pelanggan.dashboard')
                ->with('success', "Pesanan #{$pesanan->kode_pesanan} berhasil dibuat! Silakan scan QRIS 'by_onest' sebesar Rp " . number_format($totalHarga, 0, ',', '.') . " dan unggah bukti transfer.")
                ->with('show_qris_modal', true)
                ->with('qris_kode_pesanan', $pesanan->kode_pesanan)
                ->with('qris_id_pesanan', $pesanan->id_pesanan)
                ->with('qris_total_bayar', (int) $totalHarga)
                ->with('qris_merchant', 'by_onest')
                ->with('qris_nmid', 'ID1025411758325');
        } else {
            return redirect()
                ->route('pelanggan.dashboard')
                ->with('success', "Pesanan #{$pesanan->kode_pesanan} berhasil dibuat dengan metode Tunai (Cash)! Silakan siapkan uang pas Rp " . number_format($totalHarga, 0, ',', '.') . " saat penjemputan cucian oleh kurir atau di outlet.");
        }
    }

   /**
     * Upload Bukti Pembayaran Manual ke Supabase Storage (HTTP REST API Direct)
     */
    public function uploadBuktiPembayaran(Request $request, $idPesanan)
    {
        $fileKey = $request->hasFile('bukti_transfer') ? 'bukti_transfer' : 'bukti_pembayaran';

        $request->validate([
            $fileKey => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            $fileKey . '.required' => 'File bukti transfer atau struk wajib diunggah.',
            $fileKey . '.image'    => 'File bukti transfer harus berupa gambar foto.',
            $fileKey . '.mimes'    => 'Format gambar bukti transfer harus JPG, PNG, atau WEBP.',
            $fileKey . '.max'      => 'Ukuran foto bukti transfer maksimal 5 MB.',
        ]);

        $pesanan = Pesanan::with('pembayaran')
            ->where('id_user', Auth::user()->id_user)
            ->findOrFail($idPesanan);

        if ($request->hasFile($fileKey)) {
            $file = $request->file($fileKey);
            $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            try {
                $supabaseUrl = rtrim(env('SUPABASE_URL'), '/');
                $bucket = env('SUPABASE_BUCKET', 'bukti-pembayaran');
                $secretKey = env('SUPABASE_SECRET_ACCESS_KEY');

                // Endpoint upload resmi API Supabase
                $uploadApiEndpoint = "{$supabaseUrl}/storage/v1/object/{$bucket}/{$filename}";

                // Kirim request ke Supabase menggunakan format Key baru (sb_secret_...)
                $response = \Illuminate\Support\Facades\Http::withOptions([
                    'verify' => false,
                ])->withHeaders([
                    'Authorization' => 'Bearer ' . $secretKey,
                    'apikey'        => $secretKey,
                    'Content-Type'  => $file->getMimeType(),
                ])->withBody(file_get_contents($file->getRealPath()), $file->getMimeType())
                  ->post($uploadApiEndpoint);

                if ($response->failed()) {
                    throw new \Exception('Supabase API Error: ' . $response->body());
                }

                // Susun URL Publik
                $publicUrl = "{$supabaseUrl}/storage/v1/object/public/{$bucket}/{$filename}";

                if ($pesanan->pembayaran) {
                    $pesanan->pembayaran->update([
                        'bukti_transfer'    => $publicUrl,
                        'status_pembayaran' => 'Pending',
                    ]);
                }

                $pesanan->update([
                    'status_pesanan' => 'Menunggu Verifikasi',
                ]);

                return back()->with('success', "Bukti pembayaran pesanan #{$pesanan->kode_pesanan} berhasil diunggah!");

            } catch (\Exception $e) {
                return back()->with('error', 'Gagal upload ke Supabase: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Gagal mengunggah file bukti pembayaran.');
    }

    /**
     * Detail Pesanan (JSON untuk Modal Interaktif)
     */
    public function detailPesanan($idPesanan)
    {
        $pesanan = Pesanan::with(['layanan', 'pembayaran', 'user', 'pengiriman'])
            ->where('id_user', Auth::user()->id_user)
            ->findOrFail($idPesanan);

        $buktiUrl = null;
        if ($pesanan->pembayaran?->bukti_transfer) {
            $bukti = $pesanan->pembayaran->bukti_transfer;
            $buktiUrl = Str::startsWith($bukti, 'http') ? $bukti : asset('storage/' . $bukti);
        }

        $dataPayload = [
            'id_pesanan'         => $pesanan->id_pesanan,
            'kode_pesanan'       => $pesanan->kode_pesanan,
            'tanggal_pesanan'    => $pesanan->tanggal_pesanan ? $pesanan->tanggal_pesanan->format('d M Y') : '-',
            'total_harga'        => $pesanan->total_harga,
            'total_harga_format' => 'Rp ' . number_format($pesanan->total_harga, 0, ',', '.'),
            'status_pesanan'     => $pesanan->status_pesanan,
            'status_laundry'     => $pesanan->status_laundry,
            'status_pembayaran'  => $pesanan->pembayaran?->status_pembayaran ?? 'Pending',
            'metode_pembayaran'  => $pesanan->metode_pembayaran,
            'alamat_penjemputan' => $pesanan->alamat_penjemputan,
            'catatan'            => $pesanan->catatan,
            'pembayaran'         => $pesanan->pembayaran ? [
                'kode_pembayaran'   => $pesanan->pembayaran->kode_pembayaran,
                'jumlah_bayar'      => $pesanan->pembayaran->jumlah_bayar,
                'status_pembayaran' => $pesanan->pembayaran->status_pembayaran,
                'bukti_transfer'    => $buktiUrl,
            ] : null,
            'layanan'            => $pesanan->layanan->map(function ($l) {
                $jumlah = $l->pivot->jumlah ?? 1;
                return [
                    'nama_layanan' => $l->nama_layanan,
                    'harga'        => $l->harga,
                    'satuan'       => $l->satuan,
                    'jumlah'       => $jumlah,
                    'subtotal'     => $l->pivot->subtotal ?? ($l->harga * $jumlah),
                ];
            }),
            'pengiriman'         => $pesanan->pengiriman ? [
                'nama_kurir'        => $pesanan->pengiriman->nama_kurir,
                'no_hp_kurir'       => $pesanan->pengiriman->no_hp_kurir,
                'status_pengiriman' => $pesanan->pengiriman->status_pengiriman,
                'no_resi'           => $pesanan->pengiriman->no_resi,
            ] : null,
        ];

        return response()->json([
            'success' => true,
            'data'    => $dataPayload,
            'pesanan' => $dataPayload,
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
     * Simpan Ulasan Layanan Laundry
     */
    public function storeUlasan(Request $request, $idPesanan)
    {
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ], [
            'rating.required'   => 'Mohon berikan bintang rating (1 - 5).',
            'komentar.required' => 'Ulasan wajib diisi.',
        ]);

        $pesanan = Pesanan::where('id_user', Auth::user()->id_user)
            ->where('status_pesanan', 'Selesai')
            ->findOrFail($idPesanan);

        Ulasan::updateOrCreate(
            ['id_pesanan' => $pesanan->id_pesanan],
            [
                'id_user'  => Auth::id(),
                'rating'   => $request->rating,
                'komentar' => $request->komentar,
            ]
        );

        return back()->with('success', 'Terima kasih! Ulasan dan rating Anda berhasil disimpan.');
    }
}