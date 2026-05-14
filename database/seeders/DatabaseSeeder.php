<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

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



        /*
        |--------------------------------------------------------------------------
        | KELAS
        |--------------------------------------------------------------------------
        */

        // VII 1 - VII 9
        for ($i = 1; $i <= 9; $i++) {
            Kelas::create([
                'nama_kelas' => 'VII ' . $i,
            ]);
        }

        // VIII 1 - VIII 9
        for ($i = 1; $i <= 9; $i++) {
            Kelas::create([
                'nama_kelas' => 'VIII ' . $i,
            ]);
        }

        // IX 1 - IX 9
        for ($i = 1; $i <= 9; $i++) {
            Kelas::create([
                'nama_kelas' => 'IX ' . $i,
            ]);
        }



        /*
        |--------------------------------------------------------------------------
        | MAPEL MTs
        |--------------------------------------------------------------------------
        */

        $mapels = [

            // KEAGAMAAN
            'Al-Qur\'an Hadits',
            'Akidah Akhlak',
            'Fikih',
            'SKI',
            'Bahasa Arab',

            // UMUM
            'Bahasa Indonesia',
            'Matematika',
            'IPA',
            'IPS',
            'PPKn',
            'Bahasa Inggris',

            // TAMBAHAN
            'Seni Budaya',
            'PJOK',
            'Informatika',
            'Prakarya',

            // MUATAN / LOKAL
            'Tahfidz',
            'Kaligrafi',

        ];

        foreach ($mapels as $mapel) {

            Mapel::create([
                'nama_mapel' => $mapel,
            ]);
        }
    }
}