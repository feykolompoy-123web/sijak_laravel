<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Login awal: username "admin", password "Sijak@2026". WAJIB diganti setelah login pertama.
        User::updateOrCreate(
            ['username' => 'admin'],
            ['name' => 'Administrator', 'email' => 'admin@tomohon.go.id', 'password' => 'Sijak@2026']
        );
    }
}
