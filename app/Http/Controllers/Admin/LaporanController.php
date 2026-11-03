<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\Lokasi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        [$dari, $sampai] = $this->rentang($request);
        $data = $this->query($request, $dari, $sampai)->get();

        return view('admin.laporan', [
            'daftar' => $data,
            'dari' => $dari,
            'sampai' => $sampai,
            'ringkas' => collect(Kegiatan::STATUS)->mapWithKeys(fn ($s) => [$s => $data->where('status', $s)->count()]),
            'kategori' => Kategori::orderBy('nama')->get(),
            'lokasi' => Lokasi::orderBy('nama')->get(),
        ]);
    }

    /** Unduh CSV (dibuka langsung di Excel). */
    public function export(Request $request)
    {
        [$dari, $sampai] = $this->rentang($request);
        $data = $this->query($request, $dari, $sampai)->get();
        $nama = 'laporan-kegiatan-'.$dari.'_sd_'.$sampai.'.csv';

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
            fputcsv($out, ['Kode', 'Kegiatan', 'Tanggal', 'Jam', 'Lokasi', 'Kategori', 'Penanggung Jawab', 'Status', 'Publik', 'Keterangan'], ';');
            foreach ($data as $k) {
                fputcsv($out, [
                    $k->kode, $k->nama, $k->tanggal->format('d/m/Y'), $k->jam, $k->lokasi->nama, $k->kategori->nama,
                    $k->penanggung_jawab, $k->status, $k->dipublikasikan ? 'Ya' : 'Tidak', $k->keterangan,
                ], ';');
            }
            fclose($out);
        }, $nama, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function rentang(Request $request): array
    {
        $tgl = function (?string $v, string $cadangan) {
            try {
                return Carbon::createFromFormat('!Y-m-d', (string) $v)->toDateString();
            } catch (\Throwable) {
                return $cadangan;
            }
        };

        $dari = $tgl($request->query('dari'), now()->startOfMonth()->toDateString());
        $sampai = $tgl($request->query('sampai'), now()->endOfMonth()->toDateString());

        return $dari <= $sampai ? [$dari, $sampai] : [$sampai, $dari];
    }

    private function query(Request $request, string $dari, string $sampai): Builder
    {
        $q = Kegiatan::with(['kategori', 'lokasi'])->whereBetween('tanggal', [$dari, $sampai]);

        if (in_array($request->query('status'), Kegiatan::STATUS, true)) {
            $q->where('status', $request->query('status'));
        }
        if ($request->filled('kategori')) {
            $q->where('kategori_id', (int) $request->query('kategori'));
        }
        if ($request->filled('lokasi')) {
            $q->where('lokasi_id', (int) $request->query('lokasi'));
        }

        return $q->urut();
    }
}
