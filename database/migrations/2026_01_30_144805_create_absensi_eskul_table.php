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
        Schema::create('absensi_eskul', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sesi_absensi_id');
            $table->uuid('siswa_id');
            $table->enum('status', ['Hadir', 'Sakit', 'Izin']);
            $table->timestamp('waktu_scan');
            $table->timestamps();

            $table->foreign('sesi_absensi_id')->references('id')->on('sesi_absensi');
            $table->foreign('siswa_id')->references('id')->on('siswa');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_eskul');
    }
};
