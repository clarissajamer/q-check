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
        Schema::create('izin_absensi', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('siswa_id');
            $table->uuid('sesi_absensi_id');

            $table->enum('jenis_izin', ['izin', 'sakit']);
            $table->text('alasan')->nullable();
            $table->string('bukti_file')->nullable();

            $table->enum('status', [
                'pending',
                'disetujui',
                'ditolak'
            ])->default('pending');

            $table->timestamp('tanggal_pengajuan');

            $table->timestamps();

            $table->foreign('siswa_id')
                ->references('id')
                ->on('siswa')
                ->cascadeOnDelete();

            $table->foreign('sesi_absensi_id')
                ->references('id')
                ->on('sesi_absensi')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_absensi');
    }
};
