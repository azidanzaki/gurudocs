<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ADMIN
        User::create([
            'name' => 'Admin',
            'nip' => 'admin',
            'role' => 'admin',
            'password' => Hash::make('admin'),
        ]);

        // KEPALA SEKOLAH
        User::create([
            'name' => 'Kepala Sekolah',
            'nip' => 'kepsek123',
            'role' => 'kepala_sekolah',
            'password' => Hash::make('kepsek123'),
        ]);

        // GURU
        User::create([
            'name' => 'Guru',
            'nip' => 'guru123',
            'role' => 'guru',
            'password' => Hash::make('guru123'),
        ]);
    }
}
