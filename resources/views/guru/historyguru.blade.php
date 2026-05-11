@extends('layouts.main')
@section('title', 'History')
@section('content')
<div class="p-6">
  <h3 class="fw-bold mb-6 text-[#1f331d] text-2xl">History</h3>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- 🧾 HISTORY DOKUMEN -->
    <div class="shadow-md lg:col-span-2 bg-white rounded-2xl p-6">
      <h3 class="fw-bold mb-4 text-[#1f331d] text-2xl">History Dokumen</h3>

      <!-- Hari Ini -->
      <div class="mb-6">
        <h4 class="text-gray-600 font-semibold mb-3">Hari ini:</h4>
        <div class="space-y-3">
          <!-- Card Dokumen -->
          <div class="flex items-center justify-between bg-[#98c29a] rounded-xl px-4 py-3 shadow-sm">
            <div class="flex items-center gap-4">
              <img src="https://www.iconpacks.net/icons/1/free-document-icon-901-thumb.png" alt="dokumen" class="w-10 h-14 object-cover rounded">
              <div>
                <p class="font-medium text-[#1f331d]">Template RPP Quran Hadits</p>
                <p class="text-gray-700 text-sm">Senin, 20 September 2025, 08:00</p>
              </div>
            </div>
            <i class="fa-solid fa-ellipsis-vertical text-[#1f331d]"></i>
          </div>

          <div class="flex items-center justify-between bg-[#98c29a] rounded-xl px-4 py-3 shadow-sm">
            <div class="flex items-center gap-4">
              <img src="https://www.iconpacks.net/icons/1/free-document-icon-901-thumb.png" alt="dokumen" class="w-10 h-14 object-cover rounded">
              <div>
                <p class="font-medium text-[#1f331d]">Template RPP Quran Hadits</p>
                <p class="text-gray-700 text-sm">Senin, 20 September 2025, 08:00</p>
              </div>
            </div>
            <i class="fa-solid fa-ellipsis-vertical text-[#1f331d]"></i>
          </div>

          <div class="flex items-center justify-between bg-[#98c29a] rounded-xl px-4 py-3 shadow-sm">
            <div class="flex items-center gap-4">
              <img src="https://www.iconpacks.net/icons/1/free-document-icon-901-thumb.png" alt="dokumen" class="w-10 h-14 object-cover rounded">
              <div>
                <p class="font-medium text-[#1f331d]">Template RPP Quran Hadits</p>
                <p class="text-gray-700 text-sm">Senin, 20 September 2025, 08:00</p>
              </div>
            </div>
            <i class="fa-solid fa-ellipsis-vertical text-[#1f331d]"></i>
          </div>
        </div>
      </div>

      <!-- Kemarin -->
      <div>
        <h4 class="text-gray-600 font-semibold mb-3">Kemarin:</h4>
        <div class="space-y-3">
          <div class="flex items-center justify-between bg-[#98c29a] rounded-xl px-4 py-3 shadow-sm">
            <div class="flex items-center gap-4">
              <img src="https://www.iconpacks.net/icons/1/free-document-icon-901-thumb.png" alt="dokumen" class="w-10 h-14 object-cover rounded">
              <div>
                <p class="font-medium text-[#1f331d]">Template RPP Quran Hadits</p>
                <p class="text-gray-700 text-sm">Minggu, 19 September 2025, 08:00</p>
              </div>
            </div>
            <i class="fa-solid fa-ellipsis-vertical text-[#1f331d]"></i>
          </div>

          <div class="flex items-center justify-between bg-[#98c29a] rounded-xl px-4 py-3 shadow-sm">
            <div class="flex items-center gap-4">
              <img src="https://www.iconpacks.net/icons/1/free-document-icon-901-thumb.png" alt="dokumen" class="w-10 h-14 object-cover rounded">
              <div>
                <p class="font-medium text-[#1f331d]">Template RPP Quran Hadits</p>
                <p class="text-gray-700 text-sm">Minggu, 19 September 2025, 08:00</p>
              </div>
            </div>
            <i class="fa-solid fa-ellipsis-vertical text-[#1f331d]"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- 🕒 HISTORY LOGIN -->
    <div class="shadow-md bg-white rounded-2xl p-6">
      <h3 class="fw-bold mb-4 text-[#1f331d] text-2xl">History Login</h3>

      <div class="space-y-6">
        <!-- Tanggal 1 -->
        <div>
          <p class="text-gray-600 font-semibold mb-3">20 September 2025</p>
          <div class="space-y-2">
            <div class="bg-[#98c29a] rounded-xl px-4 py-2 flex justify-between items-center text-sm text-[#1f331d]">
              <span>Login sistem, perangkat: desktop 2319</span>
              <span>22:00</span>
            </div>
            <div class="bg-[#98c29a] rounded-xl px-4 py-2 flex justify-between items-center text-sm text-[#1f331d]">
              <span>Login sistem, perangkat: desktop 2319</span>
              <span>22:00</span>
            </div>
          </div>
        </div>

        <!-- Tanggal 2 -->
        <div>
          <p class="text-gray-600 font-semibold mb-3">19 September 2025</p>
          <div class="space-y-2">
            <div class="bg-[#98c29a] rounded-xl px-4 py-2 flex justify-between items-center text-sm text-[#1f331d]">
              <span>Login sistem, perangkat: desktop 2319</span>
              <span>22:00</span>
            </div>
            <div class="bg-[#98c29a] rounded-xl px-4 py-2 flex justify-between items-center text-sm text-[#1f331d]">
              <span>Login sistem, perangkat: desktop 2319</span>
              <span>22:00</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
