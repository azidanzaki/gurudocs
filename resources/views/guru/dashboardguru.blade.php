@extends('layouts.main')

@section('content')
<div class="p-6">
    <h3 class="fw-bold mb-6 text-[#1f331d] text-2xl">Dashboard Guru</h3>

    <!-- Bagian Atas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Card Tugas Semester -->
        <div class="shadow-md bg-white rounded-2xl shadow p-5 flex items-center">
            <div class="bg-green-100 text-green-700 rounded-full p-3 mr-4">
                <i class="fa-solid fa-file-lines text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Tugas Semester</p>
                <h4 class="font-bold text-xl">2 dari 10</h4>
            </div>
        </div>

        <!-- Card Profil Guru -->
        <div class="shadow-md bg-white rounded-2xl shadow p-5 flex items-center justify-between md:col-span-2">
            <div class="flex items-center space-x-4">
                <div class="bg-orange-100 rounded-full p-3">
                    <i class="fa-solid fa-user text-orange-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold">Zakiah</p>
                    <p class="text-gray-500 text-sm">12345678890</p>
                </div>
            </div>
            <a href="#" class="text-green-600 text-lg">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        </div>
    </div>

    <!-- Bagian Tengah -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri (Cari Dokumen dan daftar dokumen) -->
        <div class="lg:col-span-2">
            <!-- Pencarian -->
            <div class="flex items-center bg-gray-100 rounded-xl px-4 py-2 mb-5">
                <i class="fa-solid fa-magnifying-glass text-gray-400 mr-3"></i>
                <input type="text" placeholder="Cari Dokumen" 
                       class="w-full bg-transparent outline-none text-sm text-gray-700">
                <button class="bg-[#1f331d] text-white px-4 py-2 rounded-xl ml-3">Dokumen</button>
            </div>

            <!-- Daftar Dokumen -->
            <h5 class="font-semibold mb-3">Dokumen Yang Mungkin Kamu Butuhkan:</h5>
            <table class="shadow-md w-full bg-white rounded-2xl shadow overflow-hidden">
                <thead class="bg-gray-50 text-gray-600 text-sm">
                    <tr>
                        <th class="text-left py-3 px-5">Nama Dokumen</th>
                        <th class="text-left py-3 px-5">Mata Pelajaran</th>
                        <th class="text-left py-3 px-5">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-3 px-5">RPP Quran Hadits</td>
                        <td class="py-3 px-5">Quran Hadits IX</td>
                        <td class="py-3 px-5 text-green-600"><a href="#">Detail</a></td>
                    </tr>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-3 px-5">Silabus Quran Hadits</td>
                        <td class="py-3 px-5">Quran Hadits IX</td>
                        <td class="py-3 px-5 text-green-600"><a href="#">Detail</a></td>
                    </tr>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-3 px-5">Prota Quran Hadits</td>
                        <td class="py-3 px-5">Quran Hadits IX</td>
                        <td class="py-3 px-5 text-green-600"><a href="#">Detail</a></td>
                    </tr>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-3 px-5">Promes Quran Hadits</td>
                        <td class="py-3 px-5">Quran Hadits IX</td>
                        <td class="py-3 px-5 text-green-600"><a href="#">Detail</a></td>
                    </tr>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-3 px-5">RPP Bahasa Inggris</td>
                        <td class="py-3 px-5">Bahasa Inggris IX</td>
                        <td class="py-3 px-5 text-green-600"><a href="#">Detail</a></td>
                    </tr>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-3 px-5">Silabus Bahasa Inggris</td>
                        <td class="py-3 px-5">Bahasa Inggris IX</td>
                        <td class="py-3 px-5 text-green-600"><a href="#">Detail</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Kolom Kanan -->
        <div class="space-y-6">
            <!-- Tugas Dalam Waktu Dekat -->
            <div class="shadow-md bg-white rounded-2xl shadow p-5">
                <h6 class="font-semibold mb-3">Tugas Dalam Waktu Dekat</h6>
                <table class="w-full text-sm">
                    <thead class="text-gray-500">
                        <tr>
                            <th class="text-left py-2">Judul Tugas</th>
                            <th class="text-left py-2">Tenggat</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @for ($i = 0; $i < 5; $i++)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="py-2">RPP Quran Hadits</td>
                                <td class="py-2">26/09/2025 | 7:00</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <!-- Dokumen Baru Dilihat -->
            <div class="shadow-md bg-white rounded-2xl shadow p-5">
                <h6 class="font-semibold mb-3">Dokumen Baru Dilihat</h6>
                <table class="w-full text-sm">
                    <thead class="text-gray-500">
                        <tr>
                            <th class="text-left py-2">Nama Dokumen</th>
                            <th class="text-left py-2">Kelas</th>
                            <th class="text-left py-2">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @for ($i = 0; $i < 5; $i++)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="py-2">RPP Quran Hadits</td>
                                <td class="py-2">IX</td>
                                <td class="py-2">26/09/2025 | 7:00</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
