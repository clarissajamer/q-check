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
        Schema::create('sesi_absensi', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('jadwal_eskul_id');
            $table->dateTime('mulai_absen');
            $table->dateTime('selesai_absen')->nullable();
            $table->decimal('opened_lat', 10, 7)->nullable();
            $table->decimal('opened_lng', 10, 7)->nullable();

            // FK ke users.id (BIGINT)
            $table->unsignedBigInteger('dibuka_oleh');

            $table->enum('status', ['aktif', 'selesai']);
            $table->timestamps();

            $table->foreign('jadwal_eskul_id')
                ->references('id')->on('jadwal_eskul')
                ->cascadeOnDelete();

            $table->foreign('dibuka_oleh')
                ->references('id')->on('users')
                ->restrictOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_absensi');
    }
};
