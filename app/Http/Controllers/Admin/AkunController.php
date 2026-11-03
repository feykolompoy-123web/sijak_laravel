<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Manajemen akun admin. Semua akun bersifat admin (tidak ada peran lain). */
class AkunController extends Controller
{
    public function index()
    {
        return view('admin.akun.index', ['daftar' => User::orderBy('name')->get()]);
    }

    public function create()
    {
        return view('admin.akun.form', ['u' => new User]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'alpha_dash', 'min:3', 'max:50', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create($data);

        return redirect()->route('admin.akun.index')->with('ok', 'Akun admin berhasil ditambahkan.');
    }

    public function edit(User $akun)
    {
        return view('admin.akun.form', ['u' => $akun]);
    }

    public function update(Request $request, User $akun)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'alpha_dash', 'min:3', 'max:50', Rule::unique('users', 'username')->ignore($akun->id)],
            'email' => ['nullable', 'email', 'max:150', Rule::unique('users', 'email')->ignore($akun->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (blank($data['password'] ?? null)) {
            unset($data['password']); // kosong = password tidak diubah
        }

        $akun->update($data);

        return redirect()->route('admin.akun.index')->with('ok', 'Akun berhasil diperbarui.');
    }

    public function destroy(Request $request, User $akun)
    {
        if ($akun->is($request->user())) {
            return back()->with('galat', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }
        if (User::count() <= 1) {
            return back()->with('galat', 'Akun admin terakhir tidak dapat dihapus.');
        }

        $akun->delete();

        return redirect()->route('admin.akun.index')->with('ok', 'Akun berhasil dihapus.');
    }
}
