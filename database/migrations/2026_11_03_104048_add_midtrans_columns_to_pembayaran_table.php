<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('midtrans_order_id', 100)
                ->nullable()
                ->unique();

            $table->string('midtrans_transaction_id', 100)
                ->nullable();

            $table->text('qr_code_url')
                ->nullable();

            $table->string('midtrans_transaction_status', 50)
                ->nullable();

            $table->timestamp('waktu_kedaluwarsa')
                ->nullable();

            $table->json('midtrans_response')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropUnique(['midtrans_order_id']);

            $table->dropColumn([
                'midtrans_order_id',
                'midtrans_transaction_id',
                'qr_code_url',
                'midtrans_transaction_status',
                'waktu_kedaluwarsa',
                'midtrans_response',
            ]);
        });
    }
};