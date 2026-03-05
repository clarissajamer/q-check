N<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.N
     */
    public function up(): void
    {
        Schema::create('eskul_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('eskul_id')->constrained('eskul')->cascadeOnDelete();
            $table->foreignUuid('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['eskul_id', 'siswa_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eskul_siswa');
    }
};
