<div
  class="sidebar fixed top-0 left-0 h-full w-40 bg-[#1f331d] text-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 z-50"
  id="sidebar">

  <!-- SCROLL AREA -->
  <div class="h-full overflow-y-auto">

    <!-- Profile -->
    <div class="profile-section text-center border-b border-white/10 pb-4">
      <div class="ms-4 bg-green-100 rounded-full w-16 h-16 flex items-center justify-center">
        <i class="fa-solid fa-user text-green-600 text-2xl"></i>
      </div>

      <p class="mt-3 font-semibold bg-black/20 text-white py-2 w-full text-left ps-4">
        M. Zidan Zaki
      </p>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-2 space-y-3 pb-10">

      <!-- Dashboard -->
      <a href="{{ route('dashboardguru') }}"
        class="nav-link {{ request()->routeIs('dashboardguru') ? 'active' : '' }}">
        <i class="fa-solid fa-house me-2"></i>
        Dashboard
      </a>

      <!-- Title -->
      <p class="sidebar-title text-[9px] font-light tracking-[2px] text-gray-400 px-4 mt-8 mb-3 pb-1 border-b border-white/10">
        Dokumen Administratif
      </p>

      <a href="#" class="nav-link">
        <i class="fa-solid fa-book-open me-2"></i>
        Perangkat Pembelajaran
      </a>

      <a href="#" class="nav-link">
        <i class="fa-regular fa-folder-open me-2"></i>
        Dokumen Administratif Lainnya
      </a>

      <p class="sidebar-title text-[9px] font-light tracking-[2px] text-gray-400 px-4 mt-8 mb-3 pb-1 border-b border-white/10">
        Dokumen Non Administratif
      </p>

      <a href="#" class="nav-link">
        <i class="fa-regular fa-file-lines me-2"></i>
        Dokumen
      </a>

      <p class="sidebar-title text-[9px] font-light tracking-[2px] text-gray-400 px-4 mt-8 mb-3 pb-1 border-b border-white/10">
        Akun
      </p>

      <a href="{{ route('profilguru') }}"
        class="nav-link {{ request()->routeIs('profilguru') ? 'active' : '' }}">
        <i class="fa-solid fa-user-gear me-2"></i>
        Profile
      </a>

      <a href="#" class="nav-link text-red-300 hover:text-white hover:bg-red-500/20">
        <i class="fa-solid fa-right-from-bracket me-2"></i>
        Logout
      </a>

    </nav>
  </div>
</div>