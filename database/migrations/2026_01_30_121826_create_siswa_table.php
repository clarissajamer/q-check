<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('nis')->unique();

            $table->string('nama');

            $table->uuid('tahun_ajaran_id');

            $table->timestamps();

            $table->foreign('tahun_ajaran_id')
                ->references('id')
                ->on('tahun_ajaran')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
