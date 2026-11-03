<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class DokumentasiController extends Controller
{
    /** Jenis dan batas ukuran berkas yang diizinkan (5 MB). */
    private const FOTO = ['jpg', 'jpeg', 'png', 'webp'];
    private const DOKUMEN = ['pdf', 'doc', 'docx'];
    private const MAKS_KB = 5120;

    public function index(Request $request)
    {
        $q = Dokumentasi::with('kegiatan');

        if ($request->filled('kegiatan')) {
            $q->where('kegiatan_id', (int) $request->query('kegiatan'));
        }
        if (in_array($request->query('tipe'), ['Foto', 'Dokumen'], true)) {
            $q->where('tipe', $request->query('tipe'));
        }

        return view('admin.dokumentasi.index', [
            'daftar' => $q->latest()->paginate(12)->withQueryString(),
            'kegiatan' => Kegiatan::orderByDesc('tanggal')->get(['id', 'kode', 'nama']),
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.dokumentasi.form', [
            'd' => new Dokumentasi(['kegiatan_id' => $request->query('kegiatan'), 'dipublikasikan' => true]),
            'kegiatan' => Kegiatan::orderByDesc('tanggal')->get(['id', 'kode', 'nama']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->aturan(true));
        $berkas = $request->file('berkas');

        Dokumentasi::create([
            'kegiatan_id' => $data['kegiatan_id'] ?? null,
            'judul' => $data['judul'],
            'dipublikasikan' => $request->boolean('dipublikasikan'),
        ] + $this->simpanBerkas($berkas));

        return redirect()->route('admin.dokumentasi.index')->with('ok', 'Dokumentasi berhasil diunggah.');
    }

    public function edit(Dokumentasi $dokumentasi)
    {
        return view('admin.dokumentasi.form', [
            'd' => $dokumentasi,
            'kegiatan' => Kegiatan::orderByDesc('tanggal')->get(['id', 'kode', 'nama']),
        ]);
    }

    public function update(Request $request, Dokumentasi $dokumentasi)
    {
        $data = $request->validate($this->aturan(false));

        $dokumentasi->fill([
            'kegiatan_id' => $data['kegiatan_id'] ?? null,
            'judul' => $data['judul'],
            'dipublikasikan' => $request->boolean('dipublikasikan'),
        ]);

        if ($berkas = $request->file('berkas')) {
            $dokumentasi->hapusBerkas();                       // buang file lama (jika hasil unggahan)
            $dokumentasi->fill($this->simpanBerkas($berkas));
        }
        $dokumentasi->save();

        return redirect()->route('admin.dokumentasi.index')->with('ok', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy(Dokumentasi $dokumentasi)
    {
        $dokumentasi->delete(); // file ikut terhapus lewat event model

        return redirect()->route('admin.dokumentasi.index')->with('ok', 'Dokumentasi berhasil dihapus.');
    }

    private function aturan(bool $wajibBerkas): array
    {
        $jenis = implode(',', array_merge(self::FOTO, self::DOKUMEN));

        return [
            'judul' => ['required', 'string', 'max:150'],
            'kegiatan_id' => ['nullable', 'exists:kegiatan,id'],
            'berkas' => [$wajibBerkas ? 'required' : 'nullable', 'file', 'mimes:'.$jenis, 'max:'.self::MAKS_KB],
        ];
    }

    /** Pindahkan berkas ke public/uploads/dokumentasi dan kembalikan data untuk tabel. */
    private function simpanBerkas(UploadedFile $berkas): array
    {
        $ext = strtolower($berkas->guessExtension() ?: $berkas->getClientOriginalExtension());
        $nama = Str::uuid().'.'.$ext;
        $ukuran = $berkas->getSize();
        $asli = $berkas->getClientOriginalName();

        $berkas->move(public_path('uploads/dokumentasi'), $nama);

        return [
            'tipe' => in_array($ext, self::FOTO, true) ? 'Foto' : 'Dokumen',
            'path' => 'uploads/dokumentasi/'.$nama,
            'nama_asli' => Str::limit($asli, 250, ''),
            'ukuran' => $ukuran,
        ];
    }
}
