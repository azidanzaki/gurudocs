@extends('layouts.main')

@section('content')
  <div class="p-6">
    <h3 class="fw-bold mb-6 text-[#1f331d] text-2xl">Dokumen Guru</h3>

    <!-- 🔍 Bar Pencarian -->
    <div class="flex flex-wrap gap-4 items-center mb-8">
      <!-- Kolom Pencarian -->
      <div class="flex items-center bg-green-200 rounded-full px-4 py-2 flex-1 min-w-[250px]">
        <i class="fa-solid fa-magnifying-glass text-gray-600 mr-3"></i>
        <input type="text" placeholder="Cari Dokumen" class="w-full bg-transparent outline-none text-gray-700 text-sm">
      </div>

      <!-- Filter Jenis Dokumen -->
      <button class="bg-green-400 hover:bg-green-500 text-white px-5 py-2 rounded-full flex items-center gap-2 text-sm">
        Jenis Dokumen
        <i class="fa-solid fa-chevron-down text-xs"></i>
      </button>

      <!-- Filter Mata Pelajaran -->
      <button class="bg-green-400 hover:bg-green-500 text-white px-5 py-2 rounded-full flex items-center gap-2 text-sm">
        Mata Pelajaran
        <i class="fa-solid fa-chevron-down text-xs"></i>
      </button>
    </div>

    <!-- 📄 Bagian RPP -->
    <div class="mb-10">
      <h4 class="font-semibold text-lg mb-4 text-[#1f331d]">RPP</h4>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
        @for ($i = 0; $i < 5; $i++)
          <div
            class="bg-gray-100 rounded-2xl p-3 shadow-sm hover:shadow-lg hover:-translate-y-1 hover:bg-white transition-all duration-300 cursor-pointer">
            <img src="https://www.iconpacks.net/icons/1/free-document-icon-901-thumb.png" alt="Dokumen"
              class="w-full h-48 object-cover rounded-lg mb-2">
            <p class="text-c
            enter text-gray-700 text-sm font-medium">RPP Quran Hadits Kelas IX</p>
          </div>

        @endfor
      </div>
    </div>

    <!-- 📄 Bagian Silabus -->
    <div class="mb-10">
      <h4 class="font-semibold text-lg mb-4 text-[#1f331d]">Silabus</h4>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
        @for ($i = 0; $i < 5; $i++)
          <div
            class="bg-gray-100 rounded-2xl p-3 shadow-sm hover:shadow-lg hover:-translate-y-1 hover:bg-white transition-all duration-300 cursor-pointer">
            <img src="https://www.iconpacks.net/icons/1/free-document-icon-901-thumb.png" alt="Dokumen"
              class="w-full h-48 object-cover rounded-lg mb-2">
            <p class="text-center text-gray-700 text-sm font-medium">Silabus Quran Hadits Kelas IX</p>
          </div>
        @endfor
      </div>
    </div>
  </div>
@endsection