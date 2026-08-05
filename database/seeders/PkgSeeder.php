<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PkgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aspects = [
            1 => [
                'A. Tujuan Pembelajaran' => [
                    'Standar Kompetensi',
                    'Ranah Tujuan (komprehenship)',
                    'Sesuai dengan Kurikulum'
                ],
                'B. Bahan Belajar/Materi Pelajaran' => [
                    'Bahan belajar mengacu/sesuai dengan tujuan',
                    'Bahan belajar disusun secara sistematis',
                    'Menggunakan bahan belajar sesuai dengan kurikulum',
                    'Memberi Pengayaan'
                ],
                'C. Strategi/Metode Pembelajaran' => [
                    'Pemilihan metode disesuaikan dengan tujuan',
                    'Pemilihan metode disesuaikan dengan materi',
                    'Penentuan langkah-langkah proses pembelajaran berdasarkan metode yang digunakan',
                    'Penataan alokasi waktu proses pembelajaran sesuai dengan proporsi.',
                    'Penetapan metode berdasarkan pertimbangan kemampuan siswa.',
                    'Memberi pengayaan'
                ],
                'D. Media Pembelajaran' => [
                    'Media disesuaikan dengan tujuan pembelajaran',
                    'Media disesuaikan dengan materi pembelajaran',
                    'Media disesuaikan dengan kondisi kelas',
                    'Media disesuaikan dengan jenis evaluasi',
                    'Media disesuaikan dengan kemampuan guru',
                    'Media disesuaikan dengan kebutuhan dan perkembangan siswa'
                ],
                'E. Evaluasi' => [
                    'Evaluasi mengacu pada tujuan',
                    'Mencantumkan teknik evaluasi',
                    'Mencantumkan bentuk evaluasi',
                    'Disesuaikan dengan alokasi waktu yang tersedia',
                    'Evaluasi disesuaikan dengan kaidah evaluasi'
                ]
            ],
            2 => [
                'A. Kemampuan Membuka Pelajaran' => [
                    'Memperhatikan sikap dan tempat duduk siswa',
                    'Memberikan motivasi awal',
                    'Memberikan apersepsi (kaitan materi sebelumnya dengan materi yang akan disampaikan)',
                    'Menyampaikan indikator/tujuan pembelajaran yang akan diberikan',
                    'Memberikan acuan bahan belajar yang akan diberikan'
                ],
                'B. Sikap Guru dalam Proses Pembelajaran' => [
                    'Kejelasan artikulasi suara',
                    'Variasi Gerakan badan tidak mengganggu perhatian siswa',
                    'Antusiasme dalam penampilan',
                    'Mobilitas posisi mengajar'
                ],
                'C. Penguasaan Bahan Belajar (Materi Pelajaran)' => [
                    'Bahan belajar disajikan sesuai dengan langkah-langkah yang direncanakan dalam RPP',
                    'Kejelasan dalam menjelaskan bahan belajar (materi)',
                    'Kejelasan dalam memberikan contoh',
                    'Memiliki wawasan yang luas dalam menyampaikan bahan belajar'
                ],
                'D. Kegiatan Belajar Mengajar (Proses Pembelajaran)' => [
                    'Kesesuaian metode dengan bahan belajar yang disampaikan',
                    'Penyajian bahan belajaran sesuai dengan tujuan/indikator yang telah ditetapkan',
                    'Memiliki keterampilan dalam menanggapi dan merespon pertanyaan siswa.',
                    'Ketepatan dalam penggunaan alokasi waktu yang disediakan'
                ],
                'E. Kemampuan Menggunakan Media Pembelajaran' => [
                    'Memperhatikan prinsip-prinsip penggunaan media',
                    'Ketepatan/kesesuaian penggunaan media dengan materi yang disampaikan',
                    'Memiliki keterampilan dalam penggunaan media pembelajaran',
                    'Membantu meningkatkan perhatian siswa dalam kegiatan pembelajaran'
                ],
                'F. Evaluasi Pembelajaran' => [
                    'Penilaian relevan dengan tujuan yang telah ditetapkan',
                    'Menggunakan bentuk dan jenis ragam penilaian',
                    'Penilaian yang diberikan sesuai dengan RPP',
                    'Menganalisis ketuntasan pembelajaran'
                ],
                'G. Kemampuan Menutup Kegiatan Pembelajaran' => [
                    'Meninjau kembali materi yang telah diberikan [kesimpulan materi]',
                    'Memberi kesempatan untuk bertanya dan menjawab pertanyaan.',
                    'Memberikan kesimpulan kegiatan pembelajaran [refleksi]'
                ],
                'H. Tindak Lanjut/Follow up' => [
                    'Memberikan tugas kepada siswa baik secara individu maupun kelompok',
                    'Menginformasikan materi/bahan belajar yang akan dipelajari berikunya.',
                    'Memberikan motivasi untuk selalu terus belajar'
                ]
            ],
            3 => [
                'A. Kegiatan Membuka Pembelajaran' => [
                    'Memperhatikan sikap dan tempat duduk siswa',
                    'Memulai pembelajaran setelah siswa siap untuk belajar',
                    'Menjelaskan pentingnya materi pelajaran yang akan dipelajari',
                    'Melakukan Appersepsi (mengkaitkan materi yang disajikan dengan materi yang telah dipelajari sehingga terjadi kesinambungan)',
                    'Kejelasan hubungan antara pendahuluan dengan inti pelajaran dilakukan semenarik mungkin'
                ],
                'B. Kegiatan Menutup Pembelajaran' => [
                    'Kemampuan menyimpulkan KBM dengan tepat',
                    'Kemampuan menggunakan kata-kata yang memebesarkan hati siswa',
                    'Kemampuan memberikan evaluasi lisan maupun tulisan',
                    'Kemampuan memberikan tugas yang sifatnya memberikan pengayaan, dan pendalaman'
                ]
            ],
            4 => [
                'A. Kegiatan Variasi Pembelajaran' => [
                    'Gerak bebas guru',
                    'Isyarat guru (tangan, badan, wajah)',
                    'Suara guru (variasi kecepatan/besar kecil/intonasi)',
                    'Pemusatan perhatian pada murid (penekanan pada hal yang penting-penting dengan verbal/gestural)',
                    'Pola interaksi (guru-kelompok/guru-murid/murid-murid)',
                    'Pause/diam sejenak (untuk memberi kesempatan pada murid untuk berpikir, memberi penekanan, memberi perhatian)',
                    'Memanfaatkan indra dalam menggunakan media pembelajaran'
                ]
            ],
            5 => [
                'A. Keterampilan Bertanya' => [
                    'Kejelasan pertanyaan yang disampaikan guru.',
                    'Kejelasan hubungan antara pertanyaan guru dengan masalah yang dibicarakan.',
                    'Pertanyaan ditujukan ke seluruh kelas lebih dahulu, baru menunjuk salah satu siswa.',
                    'Pemberian waktu berpikir untuk bertanya dan menjawab',
                    'Pendistribusian pertanyaan secara merata di antara para siswa.',
                    'Pemberian tuntunan: 1) Pengungkapan pertanyaan dgn cara lain. 2) Mengajukan pertanyaan lain yg lebih sederhana. 3) Menyederhanakan pertanyaan yg diajukan.'
                ]
            ],
            6 => [
                'A. Penguatan Verbal' => [
                    'Mengucapkan kata-kata benar, bagus, tepat, dan bagus sekali bila murid menjawab/mengajukan pertanyaan.',
                    'Mengucapkan kalimat pekerjaanmu baik sekali, saya senang dengan pekerjaanmu, pekerjaanmu makin lama makin baik, pikir dulu, dan lihat lagi, untuk membesarkan hati dan memberikan dorongan.'
                ],
                'B. Penguatan Non Verbal' => [
                    'Penguatan berupa senyuman, anggukan, pandangan yang ramah, atau gerakan badan.',
                    'Penguatan dengan cara mendekati.',
                    'Penguatan dengan sentuhan.',
                    'Penguatan dengan kegiatan yang menyenangkan.',
                    'Penguatan dengan memberikan hadiah yang relevan dan rasional'
                ]
            ],
            7 => [
                'A. Kegiatan Menutup Pembelajaran' => [
                    'Kemampuan menyimpulkan KBM dengan tepat',
                    'Kemampuan menggunakan kata-kata yang memebesarkan hati siswa',
                    'Kemampuan memberikan evaluasi lisan maupun tulisan',
                    'Kemampuan melakukan evaluasi beberapa aspek dengan berbagai teknik'
                ]
            ]
        ];

        foreach ($aspects as $aspectNum => $categories) {
            foreach ($categories as $catName => $indicators) {
                $kategori = \App\Models\PkgKategori::firstOrCreate([
                    'aspek' => $aspectNum,
                    'nama' => $catName
                ]);

                foreach ($indicators as $indName) {
                    \App\Models\PkgIndikator::firstOrCreate([
                        'kategori_id' => $kategori->id,
                        'nama' => $indName
                    ]);
                }
            }
        }
    }
}
