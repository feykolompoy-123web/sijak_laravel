<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatan')->cascadeOnDelete();
            $table->string('judul', 150);
            $table->string('tipe', 20);                 // Foto atau Dokumen
            $table->string('path');                     // relatif terhadap folder public/
            $table->string('nama_asli')->nullable();
            $table->unsignedBigInteger('ukuran')->nullable(); // byte
            $table->boolean('dipublikasikan')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumentasi');
    }
};
