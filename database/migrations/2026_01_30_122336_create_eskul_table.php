<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('eskul', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_eskul');
            $table->uuid('kategori_id');
            $table->uuid('tahun_ajaran_id');
            $table->enum('status', ['aktif', 'nonaktif']);
            $table->timestamps();
            $table->foreign('kategori_id')->references('id')->on('kategori_eskul');
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajaran');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eskul');
    }
};
