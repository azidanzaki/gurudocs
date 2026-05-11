@extends('layouts.main')
@section('title', 'Tugas')
@section('content')
<div class="p-6">
  <h3 class="fw-bold mb-6 text-[#1f331d] text-2xl">Profil Guru</h3>

  <div class="bg-white shadow-md rounded-2xl p-6 p-8 mx-auto flex flex-col md:flex-row items-center justify-between">
    <!-- Foto profil -->
    <div class="flex flex-col items-center text-center md:w-1/3">
      <img src="{{ asset('images/avatar.png') }}" alt="Profile" class="w-40 h-40 rounded-full object-cover border-4 border-orange-500 mb-4">
      <h4 class="font-semibold text-lg">Zakiah</h4>
      <p class="text-sm text-gray-500">1234567890987</p>
      <p class="text-gray-600 text-sm">Quran Hadits IX, SKI VII</p>
    </div>

    <!-- Tentang guru -->
    <div class="md:w-2/3 md:pl-8 mt-6 md:mt-0">
      <h5 class="font-semibold text-gray-700 mb-2">About</h5>
      <p class="text-sm text-gray-500 leading-relaxed">
        Guru Quran Hadits di MTsN 3 Rokan Hulu, Wali kelas IX 8. 
        Mulai mengajar di MTsN 3 Rokan Hulu semenjak 2020, sebelumnya mengajar 
        Quran Hadits di MIN Pasir Pengaraian.
      </p>

      <!-- Kontak -->
      <div class="flex flex-wrap gap-4 mt-8">
        <div class="flex items-center bg-green-100 rounded-xl px-4 py-3">
          <div class="bg-green-100 rounded-full p-3">
            <i class="fa-solid fa-phone text-green-600 text-xl"></i>
          </div>
          <p class="ml-3 text-gray-700">081234567890</p>
        </div>

        <div class="flex items-center bg-green-100 rounded-xl px-4 py-3">
          <div class="bg-green-100 rounded-full p-3">
            <i class="fa-solid fa-envelope text-green-600 text-xl"></i>
          </div>
          <p class="ml-3 text-gray-700">contoh@gmail.com</p>
        </div>

        <button class="flex items-center bg-green-200 hover:bg-green-300 text-gray-800 font-medium rounded-xl px-5 py-3 transition-all">
          <i class="fa-solid fa-pen-to-square text-lg mr-2"></i>
          edit profile
        </button>
      </div>
    </div>
  </div>
</div>
@endsection
