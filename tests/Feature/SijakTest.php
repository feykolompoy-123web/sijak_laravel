<?php

namespace Tests\Feature;

use App\Models\Dokumentasi;
use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SijakTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create(['name' => 'Admin Uji', 'username' => 'admin', 'email' => 'admin@contoh.test', 'password' => 'rahasia123']);
    }

    private function kegiatan(array $o = []): Kegiatan
    {
        return Kegiatan::create($o + [
            'nama' => 'Rapat Uji', 'tanggal' => now()->toDateString(), 'jam_mulai' => '09:00', 'jam_selesai' => '11:00',
            'lokasi_id' => Lokasi::firstOrCreate(['nama' => 'Ruang A'])->id,
            'kategori_id' => Kategori::firstOrCreate(['nama' => 'Rapat'])->id,
            'penanggung_jawab' => 'Budi', 'status' => 'Terjadwal', 'keterangan' => 'Uji', 'dipublikasikan' => true,
        ]);
    }

    private function berkas(string $nama, string $isi, string $mime): UploadedFile
    {
        $p = tempnam(sys_get_temp_dir(), 'sj');
        file_put_contents($p, $isi);

        return new UploadedFile($p, $nama, $mime, null, true);
    }

    private function png(): UploadedFile
    {
        return $this->berkas('foto.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='), 'image/png');
    }

    protected function tearDown(): void
    {
        foreach (File::glob(public_path('uploads/dokumentasi/*')) as $f) {
            if (basename($f) !== '.gitkeep') {
                File::delete($f);
            }
        }
        parent::tearDown();
    }

    /* ---------- Publik ---------- */

    public function test_halaman_publik_terbuka_tanpa_login(): void
    {
        $k = $this->kegiatan();
        foreach (['/', '/jadwal', '/kalender', '/dokumentasi', '/kegiatan/'.$k->id, '/jadwal?q=Rapat&waktu=semua', '/kalender?bulan=2026-02&tgl=2026-02-10'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->get('/')->assertSee('Dinas Komunikasi dan Informatika Kota Tomohon')->assertSee('Rapat Uji');
        $this->get('/login')->assertOk()->assertSee('Masuk ke SIJAK');
    }

    public function test_kegiatan_tidak_publik_tidak_tampil_dan_detailnya_404(): void
    {
        $k = $this->kegiatan(['nama' => 'Rahasia Internal', 'dipublikasikan' => false]);
        $this->get('/')->assertDontSee('Rahasia Internal');
        $this->get('/jadwal?waktu=semua')->assertDontSee('Rahasia Internal');
        $this->get('/kegiatan/'.$k->id)->assertNotFound();
    }

    public function test_kode_kegiatan_otomatis(): void
    {
        $a = $this->kegiatan()->fresh();
        $b = $this->kegiatan()->fresh();
        $this->assertSame(sprintf('KGT-%03d', $a->id), $a->kode);
        $this->assertSame(sprintf('KGT-%03d', $b->id), $b->kode);
        $this->assertNotSame($a->kode, $b->kode);
    }

    /* ---------- Autentikasi ---------- */

    public function test_tamu_dialihkan_ke_login_untuk_semua_halaman_admin(): void
    {
        $k = $this->kegiatan();
        foreach (['/admin', '/admin/kegiatan', '/admin/kegiatan/create', '/admin/kegiatan/'.$k->id, '/admin/kalender', '/admin/kategori', '/admin/lokasi',
            '/admin/dokumentasi', '/admin/dokumentasi/create', '/admin/laporan', '/admin/laporan/export', '/admin/akun'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
        $this->post('/admin/kegiatan', [])->assertRedirect('/login');
        $this->delete('/admin/kegiatan/'.$k->id)->assertRedirect('/login');
        $this->assertDatabaseHas('kegiatan', ['id' => $k->id]);
    }

    public function test_login_berhasil_gagal_dan_logout(): void
    {
        $this->admin();

        $this->from('/login')->post('/login', ['username' => 'admin', 'password' => 'salah'])
            ->assertRedirect('/login')->assertSessionHasErrors('username');
        $this->assertGuest();

        $this->post('/login', ['username' => '', 'password' => ''])->assertSessionHasErrors(['username', 'password']);

        $this->post('/login', ['username' => 'admin', 'password' => 'rahasia123'])->assertRedirect('/admin');
        $this->assertAuthenticated();
        $this->get('/admin')->assertOk()->assertSee('Dashboard');
        $this->get('/login')->assertRedirect('/admin'); // sudah login

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_login_dengan_email(): void
    {
        $this->admin();
        $this->post('/login', ['username' => 'admin@contoh.test', 'password' => 'rahasia123'])->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    /* ---------- CRUD kegiatan ---------- */

    public function test_crud_kegiatan(): void
    {
        $this->actingAs($this->admin());
        $lok = Lokasi::create(['nama' => 'Aula'])->id;
        $kat = Kategori::create(['nama' => 'Pelatihan'])->id;

        $this->get('/admin/kegiatan/create')->assertOk();

        $data = ['nama' => 'Pelatihan Operator', 'tanggal' => '2026-11-05', 'jam_mulai' => '09:00', 'jam_selesai' => '12:00',
            'lokasi_id' => $lok, 'kategori_id' => $kat, 'penanggung_jawab' => 'Grace', 'status' => 'Terjadwal', 'keterangan' => 'Uji', 'dipublikasikan' => '1'];

        $this->post('/admin/kegiatan', $data)->assertRedirect();
        $k = Kegiatan::firstWhere('nama', 'Pelatihan Operator');
        $this->assertNotNull($k);
        $this->assertTrue($k->dipublikasikan);
        $this->get('/admin/kegiatan')->assertOk()->assertSee('Pelatihan Operator');
        $this->get('/admin/kegiatan/'.$k->id)->assertOk();
        $this->get('/admin/kegiatan/'.$k->id.'/edit')->assertOk()->assertSee('Pelatihan Operator')->assertSee('value="09:00"', false);

        $this->put('/admin/kegiatan/'.$k->id, array_merge($data, ['nama' => 'Pelatihan Revisi', 'status' => 'Selesai', 'dipublikasikan' => '0']))->assertRedirect();
        $k->refresh();
        $this->assertSame('Pelatihan Revisi', $k->nama);
        $this->assertSame('Selesai', $k->status);
        $this->assertFalse($k->dipublikasikan);

        $this->get('/admin/kegiatan?q=Revisi&status=Selesai')->assertOk()->assertSee('Pelatihan Revisi');

        $this->delete('/admin/kegiatan/'.$k->id)->assertRedirect('/admin/kegiatan');
        $this->assertDatabaseMissing('kegiatan', ['id' => $k->id]);
    }

    public function test_validasi_kegiatan(): void
    {
        $this->actingAs($this->admin());
        $this->post('/admin/kegiatan', [])->assertSessionHasErrors(['nama', 'tanggal', 'jam_mulai', 'jam_selesai', 'lokasi_id', 'kategori_id', 'penanggung_jawab', 'status']);

        $k = $this->kegiatan();
        $this->post('/admin/kegiatan', ['nama' => 'X', 'tanggal' => '2026-11-05', 'jam_mulai' => '11:00', 'jam_selesai' => '09:00',
            'lokasi_id' => $k->lokasi_id, 'kategori_id' => $k->kategori_id, 'penanggung_jawab' => 'A', 'status' => 'Terjadwal'])
            ->assertSessionHasErrors('jam_selesai');
        $this->post('/admin/kegiatan', ['nama' => 'X', 'tanggal' => '2026-11-05', 'jam_mulai' => '09:00', 'jam_selesai' => '10:00',
            'lokasi_id' => $k->lokasi_id, 'kategori_id' => $k->kategori_id, 'penanggung_jawab' => 'A', 'status' => 'Ngawur'])
            ->assertSessionHasErrors('status');
    }

    /* ---------- Kategori & lokasi ---------- */

    public function test_kategori_dan_lokasi(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/kategori', ['nama' => 'Baru'])->assertRedirect('/admin/kategori');
        $this->post('/admin/kategori', ['nama' => 'Baru'])->assertSessionHasErrors('nama'); // unik
        $c = Kategori::firstWhere('nama', 'Baru');
        $this->get('/admin/kategori/'.$c->id.'/edit')->assertOk();
        $this->put('/admin/kategori/'.$c->id, ['nama' => 'Baru Diubah'])->assertRedirect('/admin/kategori');
        $this->assertDatabaseHas('kategori', ['nama' => 'Baru Diubah']);
        $this->delete('/admin/kategori/'.$c->id)->assertRedirect();
        $this->assertDatabaseMissing('kategori', ['id' => $c->id]);

        $k = $this->kegiatan(); // memakai lokasi & kategori
        $this->delete('/admin/lokasi/'.$k->lokasi_id)->assertSessionHas('galat');
        $this->delete('/admin/kategori/'.$k->kategori_id)->assertSessionHas('galat');
        $this->assertDatabaseHas('lokasi', ['id' => $k->lokasi_id]);

        $this->post('/admin/lokasi', ['nama' => 'Ruang Baru'])->assertRedirect('/admin/lokasi');
        $this->get('/admin/lokasi')->assertOk()->assertSee('Ruang Baru');
    }

    /* ---------- Dokumentasi & upload ---------- */

    public function test_upload_foto_dokumen_dan_hapus(): void
    {
        $this->actingAs($this->admin());
        $k = $this->kegiatan();

        $this->post('/admin/dokumentasi', ['judul' => 'Foto Rapat', 'kegiatan_id' => $k->id, 'dipublikasikan' => '1', 'berkas' => $this->png()])
            ->assertRedirect('/admin/dokumentasi');
        $d = Dokumentasi::firstWhere('judul', 'Foto Rapat');
        $this->assertSame('Foto', $d->tipe);
        $this->assertFileExists(public_path($d->path));
        $this->get('/dokumentasi')->assertOk()->assertSee('Foto Rapat');
        $this->get('/kegiatan/'.$k->id)->assertOk()->assertSee('Foto Rapat');

        $pdf = $this->berkas('notulen.pdf', "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF", 'application/pdf');
        $this->post('/admin/dokumentasi', ['judul' => 'Notulen', 'berkas' => $pdf, 'dipublikasikan' => '1'])->assertRedirect();
        $this->assertSame('Dokumen', Dokumentasi::firstWhere('judul', 'Notulen')->tipe);

        // Edit tanpa mengganti berkas, lalu ganti berkas
        $lama = $d->path;
        $this->put('/admin/dokumentasi/'.$d->id, ['judul' => 'Foto Rapat 2', 'dipublikasikan' => '0'])->assertRedirect();
        $this->assertSame('Foto Rapat 2', $d->fresh()->judul);
        $this->assertFileExists(public_path($lama));
        $this->put('/admin/dokumentasi/'.$d->id, ['judul' => 'Foto Rapat 2', 'berkas' => $this->png()])->assertRedirect();
        $this->assertFileDoesNotExist(public_path($lama));
        $this->assertFileExists(public_path($d->fresh()->path));

        // Hapus dokumentasi menghapus file
        $path = $d->fresh()->path;
        $this->delete('/admin/dokumentasi/'.$d->id)->assertRedirect();
        $this->assertFileDoesNotExist(public_path($path));

        // Hapus kegiatan menghapus file dokumentasinya
        $notulen = Dokumentasi::firstWhere('judul', 'Notulen');
        $this->post('/admin/dokumentasi', ['judul' => 'Terkait', 'kegiatan_id' => $k->id, 'berkas' => $this->png()]);
        $terkait = Dokumentasi::firstWhere('judul', 'Terkait');
        $this->delete('/admin/kegiatan/'.$k->id);
        $this->assertFileDoesNotExist(public_path($terkait->path));
        $this->assertDatabaseMissing('dokumentasi', ['judul' => 'Terkait']);
        $this->assertNotNull($notulen->fresh());
    }

    public function test_upload_ditolak_untuk_jenis_berbahaya_dan_kosong(): void
    {
        $this->actingAs($this->admin());
        $php = $this->berkas('shell.php', '<?php echo 1;', 'application/x-php');
        $this->post('/admin/dokumentasi', ['judul' => 'Jahat', 'berkas' => $php])->assertSessionHasErrors('berkas');
        $exe = $this->berkas('foto.png', '<?php echo 1;', 'image/png'); // ekstensi png, isi php
        $this->post('/admin/dokumentasi', ['judul' => 'Palsu', 'berkas' => $exe])->assertSessionHasErrors('berkas');
        $this->post('/admin/dokumentasi', ['judul' => 'Tanpa berkas'])->assertSessionHasErrors('berkas');
        $this->assertDatabaseCount('dokumentasi', 0);
        $this->assertEmpty(array_filter(File::glob(public_path('uploads/dokumentasi/*')), fn ($f) => basename($f) !== '.gitkeep'));
    }

    /* ---------- Kalender, laporan, akun ---------- */

    public function test_kalender_dan_laporan(): void
    {
        $this->actingAs($this->admin());
        $this->kegiatan(['nama' => 'Agenda Laporan']);
        $this->kegiatan(['nama' => 'Agenda Tersembunyi', 'dipublikasikan' => false]);

        $this->get('/admin/kalender')->assertOk()->assertSee('Agenda Laporan')->assertSee('Agenda Tersembunyi');
        $this->get('/kalender')->assertOk()->assertSee('Agenda Laporan')->assertDontSee('Agenda Tersembunyi');
        $this->get('/admin/kalender?bulan=ngawur&tgl=xx')->assertOk(); // masukan buruk tidak menyebabkan error

        $this->get('/admin/laporan')->assertOk()->assertSee('Agenda Laporan');
        $this->get('/admin/laporan?dari=2020-01-01&sampai=2020-01-31')->assertOk()->assertDontSee('Agenda Laporan');

        $csv = $this->get('/admin/laporan/export');
        $csv->assertOk();
        $this->assertStringContainsString('text/csv', $csv->headers->get('content-type'));
        $this->assertStringContainsString('Agenda Laporan', $csv->streamedContent());
    }

    public function test_manajemen_akun_admin(): void
    {
        $a = $this->admin();
        $this->actingAs($a);

        $this->post('/admin/akun', ['name' => 'Admin Dua', 'username' => 'admin2', 'email' => '', 'password' => 'passwordku1', 'password_confirmation' => 'passwordku1'])
            ->assertRedirect('/admin/akun');
        $b = User::firstWhere('username', 'admin2');
        $this->assertNotSame('passwordku1', $b->password); // tersimpan sebagai hash
        $this->post('/admin/akun', ['name' => 'X', 'username' => 'admin2', 'password' => 'passwordku1', 'password_confirmation' => 'passwordku1'])->assertSessionHasErrors('username');
        $this->post('/admin/akun', ['name' => 'X', 'username' => 'xx', 'password' => 'pendek', 'password_confirmation' => 'pendek'])->assertSessionHasErrors(['username', 'password']);

        // Ubah tanpa password: password lama tetap
        $hash = $b->password;
        $this->put('/admin/akun/'.$b->id, ['name' => 'Admin Dua Baru', 'username' => 'admin2', 'password' => ''])->assertRedirect('/admin/akun');
        $this->assertSame($hash, $b->fresh()->password);
        $this->assertSame('Admin Dua Baru', $b->fresh()->name);

        // Tidak boleh hapus diri sendiri; boleh hapus akun lain; tidak boleh hapus yang terakhir
        $this->delete('/admin/akun/'.$a->id)->assertSessionHas('galat');
        $this->assertDatabaseHas('users', ['id' => $a->id]);
        $this->delete('/admin/akun/'.$b->id)->assertRedirect('/admin/akun');
        $this->assertDatabaseMissing('users', ['id' => $b->id]);
    }
}
