<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Dokumentasi extends Model
{
    protected $table = 'dokumentasi';

    protected $fillable = ['kegiatan_id', 'judul', 'tipe', 'path', 'nama_asli', 'ukuran', 'dipublikasikan'];

    protected $casts = ['dipublikasikan' => 'boolean'];

    protected static function booted(): void
    {
        static::deleting(fn (Dokumentasi $d) => $d->hapusBerkas());
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    /** Hanya dokumentasi yang dipublikasikan dan (jika terkait) kegiatannya juga publik. */
    public function scopePublik(Builder $q): Builder
    {
        return $q->where('dipublikasikan', true)
            ->where(fn ($w) => $w->whereNull('kegiatan_id')
                ->orWhereHas('kegiatan', fn ($k) => $k->where('dipublikasikan', true)));
    }

    public function getUrlAttribute(): string
    {
        return asset($this->path);
    }

    public function getIsFotoAttribute(): bool
    {
        return $this->tipe === 'Foto';
    }

    public function getUkuranTeksAttribute(): string
    {
        $b = (int) $this->ukuran;
        if ($b <= 0) {
            return '-';
        }

        return $b >= 1048576 ? round($b / 1048576, 1).' MB' : max(1, round($b / 1024)).' KB';
    }

    /** Hanya file hasil unggahan (folder uploads/) yang dihapus. Foto bawaan di assets/ tidak disentuh. */
    public function hapusBerkas(): void
    {
        if (str_starts_with($this->path, 'uploads/') && File::exists(public_path($this->path))) {
            File::delete(public_path($this->path));
        }
    }
}
