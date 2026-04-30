<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun Admin secara otomatis
        \App\Models\User::factory()->create([
            'name' => 'Admin Nyoy',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'), // Ini passwordnya
        ]);
    }
}