@extends('layouts.main')
@section('title', 'Tugas')
@section('content')
<div class="p-6">
  <h3 class="fw-bold mb-6 text-[#1f331d] text-2xl">Daftar Tugas</h3>

  <!-- 🔍 Filter dan Pencarian -->
  <div class="flex flex-wrap gap-4 items-center mb-8">
    <button class="bg-[#98c29a] hover:bg-[#7da97e] text-[#1f331d] px-5 py-2 rounded-md flex items-center gap-2 text-sm">
      Add Filter
      <i class="fa-solid fa-chevron-down text-xs"></i>
    </button>

    <div class="flex items-center bg-[#f8f8f8] rounded-md px-4 py-2 flex-1 min-w-[250px]">
      <i class="fa-solid fa-magnifying-glass text-gray-500 mr-3"></i>
      <input type="text" placeholder="Cari Tugas" class="w-full bg-transparent outline-none text-gray-700 text-sm">
    </div>
  </div>

  <!-- 📋 Tabel Tugas -->
  <div class="bg-white shadow-md rounded-2xl p-6">
    <div class="grid grid-cols-4 font-semibold text-[#1f331d] border-b pb-3 mb-3">
      <span>Judul</span>
      <span>Batas Waktu</span>
      <span>Status</span>
      <span>Keterangan</span>
    </div>

    <!-- 🟡 Baris 1 -->
    <div class="grid grid-cols-4 items-center bg-[#98c29a] rounded-xl px-4 py-3 mb-3 text-sm text-[#1f331d]">
      <span>RPP Quran Hadits</span>
      <span>24 September, 00:00</span>
      <span>
        <span class="bg-yellow-400 text-black px-3 py-1 rounded-full text-xs font-semibold">Belum Dikirim</span>
      </span>
      <div class="flex justify-between items-center">
        <span>kirim dokumen!!</span>
        <button class="border border-[#1f331d] px-4 py-1 rounded-full text-sm hover:bg-[#1f331d] hover:text-white transition">View Details</button>
      </div>
    </div>

    <!-- 🟢 Baris 2 -->
    <div class="grid grid-cols-4 items-center bg-[#98c29a] rounded-xl px-4 py-3 mb-3 text-sm text-[#1f331d]">
      <span>Silabus Quran Hadits</span>
      <span>24 September, 00:00</span>
      <span>
        <span class="bg-[#1f331d] text-white px-3 py-1 rounded-full text-xs font-semibold">Diterima</span>
      </span>
      <div class="flex justify-between items-center">
        <span>Dokumen diterima</span>
        <button class="border border-[#1f331d] px-4 py-1 rounded-full text-sm hover:bg-[#1f331d] hover:text-white transition">View Details</button>
      </div>
    </div>

    <!-- 🔴 Baris 3 -->
    <div class="grid grid-cols-4 items-center bg-[#98c29a] rounded-xl px-4 py-3 mb-3 text-sm text-[#1f331d]">
      <span>Promes Quran Hadits</span>
      <span>24 September, 00:00</span>
      <span>
        <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-semibold">Ditolak</span>
      </span>
      <div class="flex justify-between items-center">
        <span>Kirim ulang revisi!!</span>
        <button class="border border-[#1f331d] px-4 py-1 rounded-full text-sm hover:bg-[#1f331d] hover:text-white transition">View Details</button>
      </div>
    </div>
  </div>
</div>
@endsection
