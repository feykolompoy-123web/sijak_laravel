<?php

namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Support\Kalender;
use Illuminate\Http\Request;

/** Halaman publik: beranda, jadwal, kalender, detail kegiatan, dokumentasi. */
class PublikController extends Controller
{
    public function beranda()
    {
        $hariIni = now()->toDateString();
        $dasar = fn () => Kegiatan::publik()->with(['kategori', 'lokasi'])->urut();

        return view('publik.beranda', [
            'terdekat' => $dasar()->where('tanggal', '>=', $hariIni)
                ->whereIn('status', ['Terjadwal', 'Sedang Berlangsung'])->take(3)->get(),
            'hariIni' => $dasar()->where('tanggal', $hariIni)->get(),
            'mendatang' => $dasar()->where('tanggal', '>', $hariIni)->where('status', 'Terjadwal')->take(4)->get(),
            'terbaru' => Kegiatan::publik()->with(['kategori', 'lokasi'])->latest('id')->take(5)->get(),
        ]);
    }

    public function jadwal(Request $request)
    {
        $q = Kegiatan::publik()->with(['kategori', 'lokasi']);

        if ($cari = trim((string) $request->query('q'))) {
            $q->where('nama', 'like', '%'.$cari.'%');
        }
        if ($request->filled('kategori')) {
            $q->where('kategori_id', (int) $request->query('kategori'));
        }

        $waktu = $request->query('waktu', 'mendatang');
        if ($request->filled('tanggal')) {
            $q->whereDate('tanggal', Kalender::tanggal($request->query('tanggal')))->urut();
        } elseif ($waktu === 'lalu') {
            $q->where('tanggal', '<', now()->toDateString())->orderByDesc('tanggal')->orderByDesc('jam_mulai');
        } elseif ($waktu === 'semua') {
            $q->orderByDesc('tanggal')->orderByDesc('jam_mulai');
        } else {
            $q->where('tanggal', '>=', now()->toDateString())->urut();
        }

        return view('publik.jadwal', [
            'daftar' => $q->paginate(10)->withQueryString(),
            'kategori' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function kalender(Request $request)
    {
        $kal = Kalender::bulan($request->query('bulan'), Kegiatan::publik());
        $tgl = Kalender::tanggal($request->query('tgl'));

        return view('publik.kalender', [
            'kal' => $kal,
            'tgl' => $tgl,
            'daftar' => Kegiatan::publik()->with(['kategori', 'lokasi'])->where('tanggal', $tgl)->urut()->get(),
        ]);
    }

    public function detail(Kegiatan $kegiatan)
    {
        abort_unless($kegiatan->dipublikasikan, 404);

        return view('publik.detail', [
            'k' => $kegiatan->load(['kategori', 'lokasi']),
            'foto' => $kegiatan->dokumentasi()->where('dipublikasikan', true)->latest()->get(),
        ]);
    }

    public function dokumentasi()
    {
        return view('publik.dokumentasi', [
            'daftar' => Dokumentasi::publik()->with('kegiatan')->latest()->paginate(12),
        ]);
    }
}
