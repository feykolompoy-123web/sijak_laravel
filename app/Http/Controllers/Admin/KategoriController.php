<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index()
    {
        return view('admin.kategori.index', ['daftar' => Kategori::withCount('kegiatan')->orderBy('nama')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nama' => ['required', 'string', 'max:100', 'unique:kategori,nama']]);
        Kategori::create($data);

        return redirect()->route('admin.kategori.index')->with('ok', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', ['item' => $kategori]);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate(['nama' => ['required', 'string', 'max:100', Rule::unique('kategori', 'nama')->ignore($kategori->id)]]);
        $kategori->update($data);

        return redirect()->route('admin.kategori.index')->with('ok', 'Perubahan berhasil disimpan.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->kegiatan()->exists()) {
            return redirect()->route('admin.kategori.index')
                ->with('galat', 'Kategori "'.$kategori->nama.'" tidak dapat dihapus karena masih dipakai oleh kegiatan.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('ok', 'Kategori berhasil dihapus.');
    }
}
