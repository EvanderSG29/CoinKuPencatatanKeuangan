<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->foreignId('id_user');
            $table->date('tanggal_transaksi');
            $table->string('nama_transaksi');
            $table->foreignId('id_kategori');
            $table->string('jenis_transaksi');
            $table->integer('qty')->default(1);
            $table->decimal('nominal', 15, 2);
            $table->decimal('total_nominal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_transaksi');
    }
};
