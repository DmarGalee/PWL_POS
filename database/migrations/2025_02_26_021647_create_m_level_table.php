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
        Schema::create('m_level', function (Blueprint $table) {
            $table->id('level_id'); // Kolom primary key dengan auto-increment dan tipe data integer

            $table->string('level_kode', 10)->unique(); // Kolom untuk kode level dengan panjang 10 karakter dan harus unik

             $table->string('level_nama', 100); // Kolom untuk nama level dengan panjang 100 karakter

            $table->timestamps(); // Kolom untuk created_at dan updated_at (waktu pembuatan dan pembaruan)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_level');
    }
};
