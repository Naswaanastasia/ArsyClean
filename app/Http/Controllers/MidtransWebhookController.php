<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $notification = $request->all();

        $orderId = $notification['order_id'] ?? null;
        $statusCode = $notification['status_code'] ?? null;
        $grossAmount = $notification['gross_amount'] ?? null;
        $signatureKey = $notification['signature_key'] ?? null;

        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            return response()->json([
                'message' => 'Data notifikasi tidak lengkap.'
            ], 400);
        }

        // Verifikasi signature Midtrans.
        $serverKey = config('services.midtrans.server_key');

        $expectedSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $serverKey
        );

        if (!hash_equals($expectedSignature, $signatureKey)) {
            Log::warning('Signature Midtrans tidak valid.', [
                'order_id' => $orderId,
            ]);

            return response()->json([
                'message' => 'Signature tidak valid.'
            ], 403);
        }

        try {
            DB::transaction(function () use ($notification, $orderId) {

                $pembayaran = Pembayaran::where(
                    'midtrans_order_id',
                    $orderId
                )
                    ->lockForUpdate()
                    ->first();

                if (!$pembayaran) {
                    throw new \RuntimeException(
                        'Pembayaran tidak ditemukan.'
                    );
                }

                // Verifikasi nominal pembayaran.
                if (
                    (int) round((float) $notification['gross_amount']) !==
                    (int) round((float) $pembayaran->jumlah_bayar)
                ) {
                    throw new \RuntimeException(
                        'Nominal pembayaran tidak sesuai.'
                    );
                }

                $status = $notification['transaction_status'] ?? '';
                $fraud = $notification['fraud_status'] ?? '';

                // Pembayaran yang sudah lunas tidak boleh diturunkan.
                if ($pembayaran->status_pembayaran === 'Lunas') {
                    return;
                }

                $pembayaran->midtrans_transaction_id =
                    $notification['transaction_id'] ?? null;

                $pembayaran->midtrans_transaction_status = $status;
                $pembayaran->midtrans_response = $notification;

                if (
                    $status === 'settlement' ||
                    ($status === 'capture' && $fraud === 'accept')
                ) {
                    $pembayaran->status_pembayaran = 'Lunas';
                    $pembayaran->tanggal_bayar = now();

                    $pembayaran->pesanan()->update([
                        'status_pesanan' => 'Diproses',
                    ]);

                } elseif ($status === 'pending') {

                    $pembayaran->status_pembayaran = 'Pending';

                } elseif (in_array($status, [
                    'deny',
                    'cancel',
                    'expire',
                    'failure',
                ])) {

                    $pembayaran->status_pembayaran =
                        $status === 'cancel'
                            ? 'Dibatalkan'
                            : 'Gagal';

                    $pembayaran->pesanan()->update([
                        'status_pesanan' => 'Dibatalkan',
                    ]);
                }

                $pembayaran->save();
            });

            return response()->json([
                'message' => 'Notifikasi berhasil diproses.'
            ], 200);

        } catch (\Throwable $e) {

            Log::error('Gagal memproses notifikasi Midtrans.', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal memproses notifikasi.'
            ], 500);
        }
    }
}
