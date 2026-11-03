<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    public const STATUS = ['Terjadwal', 'Sedang Berlangsung', 'Selesai', 'Dibatalkan'];

    /** Warna lencana status (kelas CSS yang sudah ada di style.css). */
    public const BADGE = [
        'Terjadwal' => 'b-blue',
        'Sedang Berlangsung' => 'b-amber',
        'Selesai' => 'b-green',
        'Dibatalkan' => 'b-red',
    ];

    protected $fillable = [
        'nama', 'tanggal', 'jam_mulai', 'jam_selesai', 'lokasi_id', 'kategori_id',
        'penanggung_jawab', 'status', 'keterangan', 'dipublikasikan',
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
        'dipublikasikan' => 'boolean',
    ];

    protected static function booted(): void
    {
        // Kode otomatis berdasarkan nomor urut, mis. KGT-001.
        static::created(function (Kegiatan $k) {
            $k->forceFill(['kode' => sprintf('KGT-%03d', $k->id)])->saveQuietly();
        });

        // Hapus file dokumentasi milik kegiatan ini sebelum barisnya dihapus.
        static::deleting(function (Kegiatan $k) {
            $k->dokumentasi->each->delete();
        });
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class);
    }

    public function scopePublik(Builder $q): Builder
    {
        return $q->where('dipublikasikan', true);
    }

    public function scopeUrut(Builder $q): Builder
    {
        return $q->orderBy('tanggal')->orderBy('jam_mulai');
    }

    public function getBadgeAttribute(): string
    {
        return self::BADGE[$this->status] ?? 'b-blue';
    }

    public function getMulaiAttribute(): string
    {
        return substr($this->jam_mulai, 0, 5);
    }

    public function getSelesaiAttribute(): string
    {
        return substr($this->jam_selesai, 0, 5);
    }

    /** Contoh: "09.00 - 11.00 WITA" */
    public function getJamAttribute(): string
    {
        return str_replace(':', '.', $this->mulai.' - '.$this->selesai).' WITA';
    }
}
