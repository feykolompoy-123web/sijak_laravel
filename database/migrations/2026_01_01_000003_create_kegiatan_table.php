<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->nullable()->unique();   // diisi otomatis: KGT-001, KGT-002, ...
            $table->string('nama', 150);
            $table->date('tanggal')->index();
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->foreignId('lokasi_id')->constrained('lokasi')->restrictOnDelete();
            $table->foreignId('kategori_id')->constrained('kategori')->restrictOnDelete();
            $table->string('penanggung_jawab', 100);
            $table->string('status', 30)->default('Terjadwal')->index();
            $table->text('keterangan')->nullable();
            $table->boolean('dipublikasikan')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
