
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_harga_layanan', function (Blueprint $table) {
            $table->id('id_detail_harga');

            $table->unsignedBigInteger('id_layanan');

            $table->string('nama_item', 100);
            $table->decimal('harga', 12, 2);
            $table->integer('minimal_pesanan')->default(1);
            $table->string('satuan', 20)->default('Pcs');

            $table->timestamps();

            $table->foreign('id_layanan')
                ->references('id_layanan')
                ->on('layanan')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_harga_layanan');
    }
};
