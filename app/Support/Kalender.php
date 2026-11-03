<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/** Menyiapkan data kalender bulanan (dipakai halaman publik dan admin). */
class Kalender
{
    public static function bulan(?string $param, Builder $query): array
    {
        try {
            $awal = Carbon::createFromFormat('!Y-m', (string) $param);
        } catch (\Throwable) {
            $awal = now()->startOfMonth();
        }
        $awal = $awal->startOfMonth();

        $jumlah = (clone $query)
            ->whereBetween('tanggal', [$awal->toDateString(), $awal->copy()->endOfMonth()->toDateString()])
            ->selectRaw('tanggal as tgl, COUNT(*) as total')
            ->groupBy('tanggal')
            ->get()
            ->mapWithKeys(fn ($r) => [substr((string) $r->tgl, 0, 10) => (int) $r->total])
            ->all();

        return [
            'awal' => $awal,
            'judul' => $awal->translatedFormat('F Y'),
            'sebelumnya' => $awal->copy()->subMonth()->format('Y-m'),
            'berikutnya' => $awal->copy()->addMonth()->format('Y-m'),
            'kosong' => $awal->dayOfWeek,          // 0 = Minggu
            'hari' => $awal->daysInMonth,
            'jumlah' => $jumlah,
        ];
    }

    /** Tanggal terpilih harus berformat Y-m-d, jika tidak pakai hari ini. */
    public static function tanggal(?string $tgl): string
    {
        try {
            return Carbon::createFromFormat('!Y-m-d', (string) $tgl)->toDateString();
        } catch (\Throwable) {
            return now()->toDateString();
        }
    }
}
