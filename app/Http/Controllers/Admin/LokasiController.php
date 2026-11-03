<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LokasiController extends Controller
{
    public function index()
    {
        return view('admin.lokasi.index', ['daftar' => Lokasi::withCount('kegiatan')->orderBy('nama')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nama' => ['required', 'string', 'max:150', 'unique:lokasi,nama']]);
        Lokasi::create($data);

        return redirect()->route('admin.lokasi.index')->with('ok', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(Lokasi $lokasi)
    {
        return view('admin.lokasi.edit', ['item' => $lokasi]);
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $data = $request->validate(['nama' => ['required', 'string', 'max:150', Rule::unique('lokasi', 'nama')->ignore($lokasi->id)]]);
        $lokasi->update($data);

        return redirect()->route('admin.lokasi.index')->with('ok', 'Perubahan berhasil disimpan.');
    }

    public function destroy(Lokasi $lokasi)
    {
        if ($lokasi->kegiatan()->exists()) {
            return redirect()->route('admin.lokasi.index')
                ->with('galat', 'Lokasi "'.$lokasi->nama.'" tidak dapat dihapus karena masih dipakai oleh kegiatan.');
        }

        $lokasi->delete();

        return redirect()->route('admin.lokasi.index')->with('ok', 'Lokasi berhasil dihapus.');
    }
}
