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
        Schema::create('m_barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang', 100);
            $table->text('deskripsi_barang')->nullable();
            $table->decimal('harga_barang', 10, 2);
            $table->unsignedBigInteger('id_kategori')->index(); // Foreign key ke m_kategori
            $table->unsignedBigInteger('id_supplier')->index(); // Foreign key ke m_supplier
            $table->timestamps();
        
            $table->foreign('id_kategori')->references('id')->on('m_kategori');
            $table->foreign('id_supplier')->references('id')->on('m_supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_barang');
    }
};
