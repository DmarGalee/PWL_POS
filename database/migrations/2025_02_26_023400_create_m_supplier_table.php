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
        Schema::create('m_supplier', function (Blueprint $table) {
        $table->id(); // Kolom primary key dengan auto-increment
        $table->string('nama_supplier', 100); // Kolom nama supplier
        $table->string('alamat_supplier', 255)->nullable(); // Kolom alamat supplier (boleh kosong)
        $table->string('telepon_supplier', 20)->nullable(); // Kolom telepon supplier (boleh kosong)
        $table->timestamps(); // Kolom created_at dan updated_at
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_supplier');
    }
};
