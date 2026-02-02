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
        Schema::create('anggota_eskul', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('siswa_id');
            $table->uuid('eskul_id');
            $table->uuid('tahun_ajaran_id');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();

            $table->foreign('siswa_id')->references('id')->on('siswa');
            $table->foreign('eskul_id')->references('id')->on('eskul');
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajaran');

            $table->unique(['siswa_id', 'eskul_id', 'tahun_ajaran_id'], );
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_eskul');
    }
};
