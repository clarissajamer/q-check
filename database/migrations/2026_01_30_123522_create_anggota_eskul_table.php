<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('anggota_eskul', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->uuid('siswa_id');
            $table->uuid('eskul_id');
            $table->uuid('tahun_ajaran_id');

            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            $table->timestamps();

            $table->foreign('siswa_id')
                ->references('id')
                ->on('siswa')
                ->cascadeOnDelete();

            $table->foreign('eskul_id')
                ->references('id')
                ->on('eskul')
                ->cascadeOnDelete();

            $table->foreign('tahun_ajaran_id')
                ->references('id')
                ->on('tahun_ajaran')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_eskul');
    }
};