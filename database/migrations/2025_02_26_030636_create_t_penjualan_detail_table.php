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
        Schema::create('t_penjualan_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penjualan')->index(); // Foreign key ke t_penjualan
            $table->unsignedBigInteger('id_barang')->index(); // Foreign key ke m_barang
            $table->integer('jumlah_barang');
            $table->decimal('harga_satuan', 10, 2);
            $table->timestamps();
        
            $table->foreign('id_penjualan')->references('id')->on('t_penjualan');
            $table->foreign('id_barang')->references('id')->on('m_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_penjualan_detail');
    }
};
