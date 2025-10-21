<div class="sidebar" id="sidebar">
  <div>
    <div class="profile-section text-center pt-4 pb-0">
      <img class="ms-4 w-20 h-20 mx-auto rounded-full"
        src="https://www.freeiconspng.com/uploads/png-file-png-file-png-file-png-file-png-file-27.png" alt="Profile">
      <p class="mt-3 fw-semibold bg-black/60 text-white py-1 w-full text-left block mb-0 ps-4">
        M. Zidan Zaki
      </p>
    </div>

    <nav class="mt-3">
      <a href="{{ route('dashboardguru') }}" class="{{ request()->routeIs('dashboardguru') ? 'active' : '' }}">
        <i class="fa-solid fa-house me-2"></i>Dashboard
      </a>

      <a href="{{ route('dokumenguru') }}" class="{{ request()->routeIs('dokumenguru') ? 'active' : '' }}">
        <i class="fa-regular fa-file-lines me-2"></i>Dokumen
      </a>

      <a href="{{ route('tugasguru') }}" class="{{ request()->routeIs('tugasguru') ? 'active' : '' }}">
        <i class="fa-solid fa-puzzle-piece me-2"></i>Tugas
      </a>

      <a href="{{ route('historyguru') }}" class="{{ request()->routeIs('historyguru') ? 'active' : '' }}">
        <i class="fa-solid fa-clock-rotate-left me-2"></i>History
      </a>

      <a href="{{ route('profilguru') }}" class="{{ request()->routeIs('profilguru') ? 'active' : '' }}">
        <i class="fa-solid fa-gear me-2"></i>Profil
      </a>
    </nav>
  </div>
</div>
