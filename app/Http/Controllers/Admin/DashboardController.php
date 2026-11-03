<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Kegiatan;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = now()->toDateString();
        $awalBulan = now()->startOfMonth()->toDateString();
        $akhirBulan = now()->endOfMonth()->toDateString();

        return view('admin.dashboard', [
            'total' => Kegiatan::count(),
            'hariIniJumlah' => Kegiatan::where('tanggal', $hariIni)->count(),
            'bulanIni' => Kegiatan::whereBetween('tanggal', [$awalBulan, $akhirBulan])->count(),
            'mendatang' => Kegiatan::where('tanggal', '>=', $hariIni)->where('status', 'Terjadwal')->count(),
            'selesai' => Kegiatan::where('status', 'Selesai')->count(),
            'dokumentasi' => Dokumentasi::count(),
            'agendaHariIni' => Kegiatan::with(['kategori', 'lokasi'])->where('tanggal', $hariIni)->urut()->get(),
            'agendaTerdekat' => Kegiatan::with(['kategori', 'lokasi'])->where('tanggal', '>', $hariIni)
                ->whereIn('status', ['Terjadwal', 'Sedang Berlangsung'])->urut()->take(6)->get(),
        ]);
    }
}
