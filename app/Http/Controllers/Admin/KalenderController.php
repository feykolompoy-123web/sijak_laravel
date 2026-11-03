<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Support\Kalender;
use Illuminate\Http\Request;

class KalenderController extends Controller
{
    public function index(Request $request)
    {
        $tgl = Kalender::tanggal($request->query('tgl'));

        return view('admin.kalender', [
            'kal' => Kalender::bulan($request->query('bulan'), Kegiatan::query()),
            'tgl' => $tgl,
            'daftar' => Kegiatan::with(['kategori', 'lokasi'])->where('tanggal', $tgl)->urut()->get(),
        ]);
    }
}
