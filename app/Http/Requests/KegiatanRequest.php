<?php

namespace App\Http\Requests;

use App\Models\Kegiatan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // akses sudah dibatasi middleware "auth"
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'lokasi_id' => ['required', 'exists:lokasi,id'],
            'kategori_id' => ['required', 'exists:kategori,id'],
            'penanggung_jawab' => ['required', 'string', 'max:100'],
            'status' => ['required', Rule::in(Kegiatan::STATUS)],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return ['jam_selesai.after' => 'Jam selesai harus lebih akhir dari jam mulai.'];
    }

    /** Data siap simpan (checkbox publikasi diubah menjadi true/false). */
    public function datanya(): array
    {
        return $this->validated() + ['dipublikasikan' => $this->boolean('dipublikasikan')];
    }
}
