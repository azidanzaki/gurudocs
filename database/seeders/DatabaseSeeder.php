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

        $users = [
            // ================= KEPALA SEKOLAH =================
            [
                'name' => 'DEWI SAMI WARDANI, S.Pd., M.Pd',
                'nip' => '197604022003122003',
                'role' => 'kepala_sekolah',
                'password' => Hash::make('197604022003122003'),
            ],

            // ================= GURU =================
            [
                'name' => 'YULIANTI, S.Pd',
                'nip' => '197807172005012014',
                'role' => 'guru',
                'password' => Hash::make('197807172005012014'),
            ],
            [
                'name' => 'Hj. ASMUL HAYATI, S.Pd',
                'nip' => '197003051999032003',
                'role' => 'guru',
                'password' => Hash::make('197003051999032003'),
            ],
            [
                'name' => "AS'ADI, S.Ag",
                'nip' => '197302182002121001',
                'role' => 'guru',
                'password' => Hash::make('197302182002121001'),
            ],
            [
                'name' => 'JAFRI, S.Pd',
                'nip' => '196806071997031002',
                'role' => 'guru',
                'password' => Hash::make('196806071997031002'),
            ],
            [
                'name' => 'HUZAIMAH, S.Ag',
                'nip' => '197704062007012011',
                'role' => 'guru',
                'password' => Hash::make('197704062007012011'),
            ],
            [
                'name' => 'Hj. YUNIDAWATI, S.Pd',
                'nip' => '197306052007012013',
                'role' => 'guru',
                'password' => Hash::make('197306052007012013'),
            ],
            [
                'name' => 'Hj. SALAMAH, S.Ag',
                'nip' => '197811172007012001',
                'role' => 'guru',
                'password' => Hash::make('197811172007012001'),
            ],
            [
                'name' => 'H. SYAFRI, S.Ag',
                'nip' => '197208172007011054',
                'role' => 'guru',
                'password' => Hash::make('197208172007011054'),
            ],
            [
                'name' => 'AINAL BADRI, S.Pd',
                'nip' => '197308142003122001',
                'role' => 'guru',
                'password' => Hash::make('197308142003122001'),
            ],
            [
                'name' => 'MARDIAH HASIBUAN, S.Pd',
                'nip' => '196907072005012009',
                'role' => 'guru',
                'password' => Hash::make('196907072005012009'),
            ],
            [
                'name' => 'RISMALIDA, S.Pd',
                'nip' => '196905232005012002',
                'role' => 'guru',
                'password' => Hash::make('196905232005012002'),
            ],
            [
                'name' => 'YURLENA DEWI, S.Pd',
                'nip' => '197910022005012008',
                'role' => 'guru',
                'password' => Hash::make('197910022005012008'),
            ],
            [
                'name' => 'ENDANG YORDANI, S.Pd.I',
                'nip' => '197708182000032003',
                'role' => 'guru',
                'password' => Hash::make('197708182000032003'),
            ],
            [
                'name' => 'MERIATI, S.Pd',
                'nip' => '197701012005012008',
                'role' => 'guru',
                'password' => Hash::make('197701012005012008'),
            ],
            [
                'name' => 'NELLI AFRIDA, S.Pd',
                'nip' => '198110162007102003',
                'role' => 'guru',
                'password' => Hash::make('198110162007102003'),
            ],
            [
                'name' => 'WAHIDAH, S.Pd.I',
                'nip' => '196904242000032008',
                'role' => 'guru',
                'password' => Hash::make('196904242000032008'),
            ],
            [
                'name' => 'FIRDAUS, S.Pd.I',
                'nip' => '197910092003121005',
                'role' => 'guru',
                'password' => Hash::make('197910092003121005'),
            ],
            [
                'name' => 'ERMANTO, S.Ag',
                'nip' => '196904152007011008',
                'role' => 'guru',
                'password' => Hash::make('196904152007011008'),
            ],
            [
                'name' => 'MAHRIM, S.Ag',
                'nip' => '197304132008011013',
                'role' => 'guru',
                'password' => Hash::make('197304132008011013'),
            ],
            [
                'name' => 'ZAKIAH, S.Pd.I',
                'nip' => '197604012005012011',
                'role' => 'guru',
                'password' => Hash::make('197604012005012011'),
            ],
            [
                'name' => 'LINDAWATI, S.Pd.I',
                'nip' => '197608062003122002',
                'role' => 'guru',
                'password' => Hash::make('197608062003122002'),
            ],
            [
                'name' => 'GUSMAWATI, S.Ag',
                'nip' => '197310052007012003',
                'role' => 'guru',
                'password' => Hash::make('197310052007012003'),
            ],
            [
                'name' => 'NIKMAH, S.Pd',
                'nip' => '198610132011012011',
                'role' => 'guru',
                'password' => Hash::make('198610132011012011'),
            ],
            [
                'name' => 'ERNI NAWANA TANJUNG, S.Pd',
                'nip' => '197908172009012007',
                'role' => 'guru',
                'password' => Hash::make('197908172009012007'),
            ],
            [
                'name' => 'MALINAR, S.Pd.I',
                'nip' => '197502242000032002',
                'role' => 'guru',
                'password' => Hash::make('197502242000032002'),
            ],
            [
                'name' => 'PUTRI ANDAYANI, S.Pd.I',
                'nip' => '199003192019032019',
                'role' => 'guru',
                'password' => Hash::make('199003192019032019'),
            ],
            [
                'name' => 'JHON LASKA, S.Pd',
                'nip' => '198810202019031011',
                'role' => 'guru',
                'password' => Hash::make('198810202019031011'),
            ],
            [
                'name' => 'BAYU SETIAWAN, S.Pd',
                'nip' => '199410172019031010',
                'role' => 'guru',
                'password' => Hash::make('199410172019031010'),
            ],
            [
                'name' => 'H. AFDOL ZIKRI, M.Si',
                'nip' => '199411092019031011',
                'role' => 'guru',
                'password' => Hash::make('199411092019031011'),
            ],
            [
                'name' => 'MUHAMMAD BUDI PRASETIO, S.Pd',
                'nip' => '199501072019031009',
                'role' => 'guru',
                'password' => Hash::make('199501072019031009'),
            ],
            [
                'name' => 'ARMANILA FEBRI, M.H.',
                'nip' => '199102142019032024',
                'role' => 'guru',
                'password' => Hash::make('199102142019032024'),
            ],
            [
                'name' => 'AMELIA, S.Ag',
                'nip' => '197009122014112003',
                'role' => 'guru',
                'password' => Hash::make('197009122014112003'),
            ],
            [
                'name' => 'MUHAMMAD ALI, S.Pd',
                'nip' => '199101022019031012',
                'role' => 'guru',
                'password' => Hash::make('199101022019031012'),
            ],
            [
                'name' => 'BUR ALAMSYAH, S.Si',
                'nip' => '198606042019031003',
                'role' => 'guru',
                'password' => Hash::make('198606042019031003'),
            ],
            [
                'name' => 'AMELIA ROY HANUN NASUTION, S.Si',
                'nip' => '198705092019032009',
                'role' => 'guru',
                'password' => Hash::make('198705092019032009'),
            ],
            [
                'name' => 'BAIHAKI, S.Pd.I',
                'nip' => '199007072023211027',
                'role' => 'guru',
                'password' => Hash::make('199007072023211027'),
            ],
            [
                'name' => 'SAMSIAH, S.Pd',
                'nip' => '199212122023212058',
                'role' => 'guru',
                'password' => Hash::make('199212122023212058'),
            ],
            [
                'name' => 'TRI YULIZA ARISTI, S.Pd',
                'nip' => '199107122023212052',
                'role' => 'guru',
                'password' => Hash::make('199107122023212052'),
            ],
            [
                'name' => 'HELSI MURPINA, S.Pd',
                'nip' => '198810022023212031',
                'role' => 'guru',
                'password' => Hash::make('198810022023212031'),
            ],
            [
                'name' => 'ZUNNURUL LAILA, S.Pd',
                'nip' => '199306162023212050',
                'role' => 'guru',
                'password' => Hash::make('199306162023212050'),
            ],
            [
                'name' => 'JOKO HARTONO, S.Pd',
                'nip' => '199406132023211019',
                'role' => 'guru',
                'password' => Hash::make('199406132023211019'),
            ],
            [
                'name' => 'SITI AULIA FITRIA RAHMAN, S.Psi',
                'nip' => '199602192023212024',
                'role' => 'guru',
                'password' => Hash::make('199602192023212024'),
            ],
            [
                'name' => 'ADE ZULFIA SEMBIRING, ST',
                'nip' => '197910262025212002',
                'role' => 'guru',
                'password' => Hash::make('197910262025212002'),
            ],
            [
                'name' => 'YELVIANTI, S.Pd',
                'nip' => '199407232025212018',
                'role' => 'guru',
                'password' => Hash::make('199407232025212018'),
            ],

            // ================= ADMIN =================
            [
                'name' => 'RIDWAN',
                'nip' => '198704052025211060',
                'role' => 'admin',
                'password' => Hash::make('198704052025211060'),
            ],
            [
                'name' => 'RINI MAIDIANTY',
                'nip' => '197905032025212023',
                'role' => 'admin',
                'password' => Hash::make('197905032025212023'),
            ],
            [
                'name' => 'SYAMSUL AMIN',
                'nip' => '199206182025211044',
                'role' => 'admin',
                'password' => Hash::make('199206182025211044'),
            ],
        ];
        
        // ======= TAMBAHKAN KODE INI ========
        foreach ($users as $user) {
            User::create($user);
        }
        // ===================================


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

        $this->call([
            PerangkatTemplateSeeder::class,
        ]);
    }
}