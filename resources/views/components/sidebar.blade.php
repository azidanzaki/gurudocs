<div
  class="sidebar fixed top-0 left-0 h-full w-60 bg-[#1f331d] text-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 z-50"
  id="sidebar">
  <div>
    <div class="profile-section text-center">
      <div class="ms-4 bg-green-100 rounded-full w-16 h-16 flex items-center justify-center">
        <i class="fa-solid fa-user text-green-600 text-2xl"></i>
      </div>
      <p class="mt-3 font-semibold bg-black/20 text-white py-2 w-full text-left ps-4">
        M. Zidan Zaki
      </p>

    </div>

    <nav class="mt-6 space-y-2">
      <a href="{{ route('dashboardguru') }}" class="nav-link {{ request()->routeIs('dashboardguru') ? 'active' : '' }}">
        <i class="fa-solid fa-house me-2"></i>Dashboard
      </a>

      <a href="{{ route('dokumenguru') }}" class="nav-link {{ request()->routeIs('dokumenguru') ? 'active' : '' }}">
        <i class="fa-regular fa-file-lines me-2"></i>Dokumen
      </a>





      <a href="{{ route('tugasguru') }}" class="nav-link {{ request()->routeIs('tugasguru') ? 'active' : '' }}">
        <i class="fa-solid fa-puzzle-piece me-2"></i>Tugas
      </a>

      <a href="{{ route('historyguru') }}" class="nav-link {{ request()->routeIs('historyguru') ? 'active' : '' }}">
        <i class="fa-solid fa-clock-rotate-left me-2"></i>History
      </a>

      <a href="{{ route('profilguru') }}" class="nav-link {{ request()->routeIs('profilguru') ? 'active' : '' }}">
        <i class="fa-solid fa-gear me-2"></i>Profil
      </a>
    </nav>
  </div>
</div>