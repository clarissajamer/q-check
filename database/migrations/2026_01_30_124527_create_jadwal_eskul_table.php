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
        Schema::create('jadwal_eskul', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('eskul_id');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('radius_meter')->default(50);
            $table->enum('status', ['aktif', 'dibatalkan']);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->foreign('eskul_id')->references('id')->on('eskul')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')
                ->restrictOnDelete();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_eskul');
    }
};
