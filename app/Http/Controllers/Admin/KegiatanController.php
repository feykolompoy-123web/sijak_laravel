<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\KegiatanRequest;
use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $q = Kegiatan::with(['kategori', 'lokasi']);

        if ($cari = trim((string) $request->query('q'))) {
            $q->where(fn ($w) => $w->where('nama', 'like', "%$cari%")
                ->orWhere('kode', 'like', "%$cari%")
                ->orWhere('penanggung_jawab', 'like', "%$cari%"));
        }
        if ($request->filled('status')) {
            $q->where('status', $request->query('status'));
        }
        if ($request->filled('kategori')) {
            $q->where('kategori_id', (int) $request->query('kategori'));
        }
        if ($request->filled('tanggal')) {
            $q->whereDate('tanggal', $request->query('tanggal'));
        }

        return view('admin.kegiatan.index', [
            'daftar' => $q->orderByDesc('tanggal')->orderByDesc('jam_mulai')->paginate(10)->withQueryString(),
            'kategori' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.kegiatan.form', $this->pilihan(new Kegiatan([
            'tanggal' => now(), 'jam_mulai' => '09:00', 'jam_selesai' => '10:00',
            'status' => 'Terjadwal', 'dipublikasikan' => true,
        ])));
    }

    public function store(KegiatanRequest $request)
    {
        $k = Kegiatan::create($request->datanya());

        return redirect()->route('admin.kegiatan.show', $k)->with('ok', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.show', [
            'k' => $kegiatan->load(['kategori', 'lokasi', 'dokumentasi']),
        ]);
    }

    public function edit(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.form', $this->pilihan($kegiatan));
    }

    public function update(KegiatanRequest $request, Kegiatan $kegiatan)
    {
        $kegiatan->update($request->datanya());

        return redirect()->route('admin.kegiatan.show', $kegiatan)->with('ok', 'Perubahan kegiatan berhasil disimpan.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()->route('admin.kegiatan.index')->with('ok', 'Kegiatan dan dokumentasinya berhasil dihapus.');
    }

    private function pilihan(Kegiatan $k): array
    {
        return [
            'k' => $k,
            'kategori' => Kategori::orderBy('nama')->get(),
            'lokasi' => Lokasi::orderBy('nama')->get(),
        ];
    }
}
