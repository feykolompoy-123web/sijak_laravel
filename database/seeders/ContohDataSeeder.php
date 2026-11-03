<?php

namespace Database\Seeders;

use App\Models\Dokumentasi;
use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\Lokasi;
use Illuminate\Database\Seeder;

/** Data contoh agar tampilan tidak kosong saat pertama dijalankan. Tanggal dihitung dari hari ini. */
class ContohDataSeeder extends Seeder
{
    public function run(): void
    {
        if (Kegiatan::exists()) {
            return; // jangan menggandakan data
        }

        $kat = Kategori::pluck('id', 'nama');
        $lok = Lokasi::pluck('id', 'nama');

        $data = [
            ['Rapat Koordinasi Command Center', 0, '09:00', '11:00', 'Ruang Command Center', 'Rapat Koordinasi', 'Budi Santoso', 'Sedang Berlangsung', 'Rapat koordinasi mingguan tim Command Center.', true],
            ['Monitoring Kegiatan', 0, '13:00', '15:00', 'Ruang Command Center', 'Monitoring', 'Maria Lumentut', 'Terjadwal', 'Pemantauan pelaksanaan kegiatan OPD.', true],
            ['Pelatihan Operator Command Center', 2, '09:00', '12:00', 'Aula Diskominfo', 'Pelatihan', 'Jefry Kawulur', 'Terjadwal', 'Pelatihan penggunaan dashboard pemantauan.', true],
            ['Sosialisasi Layanan Informasi Publik', 3, '10:00', '12:00', 'Aula Diskominfo', 'Sosialisasi', 'Grace Mantiri', 'Terjadwal', 'Sosialisasi kepada pegawai OPD.', true],
            ['Monitoring Jaringan Internet OPD', -1, '08:30', '10:30', 'Ruang Server', 'Monitoring', 'Robby Tumbel', 'Selesai', 'Pengecekan kualitas jaringan.', true],
            ['Rapat Evaluasi Bulanan', -3, '14:00', '16:00', 'Ruang Rapat Utama', 'Rapat Koordinasi', 'Budi Santoso', 'Selesai', 'Evaluasi agenda bulan berjalan.', false],
            ['Kunjungan Kerja Tamu Daerah', 5, '09:00', '11:30', 'Ruang Command Center', 'Kunjungan', 'Maria Lumentut', 'Terjadwal', 'Kunjungan studi tiru ke Command Center.', true],
            ['Rapat Persiapan Event Kota', 1, '10:00', '11:00', 'Ruang Rapat Utama', 'Rapat Koordinasi', 'Grace Mantiri', 'Dibatalkan', 'Dibatalkan karena jadwal bentrok.', true],
        ];

        foreach ($data as [$nama, $hari, $mulai, $selesai, $lokasi, $kategori, $pj, $status, $ket, $publik]) {
            Kegiatan::create([
                'nama' => $nama,
                'tanggal' => now()->addDays($hari)->toDateString(),
                'jam_mulai' => $mulai,
                'jam_selesai' => $selesai,
                'lokasi_id' => $lok[$lokasi],
                'kategori_id' => $kat[$kategori],
                'penanggung_jawab' => $pj,
                'status' => $status,
                'keterangan' => $ket,
                'dipublikasikan' => $publik,
            ]);
        }

        // Dua foto bawaan (sudah ada di public/assets/images).
        foreach ([
            ['Ruangan Command Center Kota Tomohon', 'assets/images/command-center-tomohon-stage.jpg'],
            ['Ruang Command Center Tomohon', 'assets/images/command-center-tomohon.jpg'],
        ] as [$judul, $path]) {
            Dokumentasi::create([
                'kegiatan_id' => null,
                'judul' => $judul,
                'tipe' => 'Foto',
                'path' => $path,
                'nama_asli' => basename($path),
                'ukuran' => filesize(public_path($path)) ?: null,
                'dipublikasikan' => true,
            ]);
        }
    }
}
