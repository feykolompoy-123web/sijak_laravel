<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Rapat Koordinasi', 'Monitoring', 'Pelatihan', 'Sosialisasi', 'Kunjungan'] as $nama) {
            Kategori::firstOrCreate(['nama' => $nama]);
        }

        foreach (['Ruang Command Center', 'Aula Diskominfo', 'Ruang Rapat Utama', 'Ruang Server'] as $nama) {
            Lokasi::firstOrCreate(['nama' => $nama]);
        }
    }
}
