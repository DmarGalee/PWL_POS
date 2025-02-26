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
        Schema::create('t_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_penjualan', 20)->unique();
            $table->date('tanggal_penjualan');
            $table->unsignedBigInteger('id_user')->index(); // Foreign key ke m_user
            $table->timestamps();

            // Perbaikan foreign key: pastikan sesuai dengan primary key di m_user
            $table->foreign('id_user')->references('user_id')->on('m_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_penjualan');
    }
};
