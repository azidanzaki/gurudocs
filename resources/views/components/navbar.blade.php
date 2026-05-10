<nav class="navbar flex items-center justify-between px-6 py-3 bg-white fixed top-0 left-0 w-full z-50"
  style="background: #1f331d;">
  <!-- Kiri: Tombol toggle sidebar -->
  <button id="toggleSidebar" class="text-[#1f331d] text-2xl md:hidden">
    <i class="fa-solid fa-bars"></i>
  </button>

  <!-- Tengah: Judul halaman -->
  <h1 class="text-lg font-semibold text-white">GuruDocs</h1>

  <!-- Kanan: Profil mini -->
  <div class="relative">
    <div class="flex items-center space-x-2 cursor-pointer" id="profileMenuButton">
      <span class="text-white font-medium hidden sm:inline">M. Zidan Zaki</span>
      <div class="bg-green-100 rounded-full p-3">
        <i class="fa-solid fa-user text-green-600 text-2xl"></i>
      </div>
      <!-- Ikon panah dropdown -->
      <i id="arrowIcon" class="fa-solid fa-chevron-down text-white text-sm transition-transform duration-300"></i>
    </div>

    <!-- Dropdown Menu -->
    <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg py-2">
      <!-- Link ke Profil -->
      <a href="http://127.0.0.1:8000/profilguru" 
         class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
        <i class="fa-solid fa-user mr-2 text-green-600"></i> Profil
      </a>
      <hr class="my-1 border-gray-200">
      <form action="/logout" method="POST">
        @csrf <!-- jika pakai Laravel -->
        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
          <i class="fa-solid fa-right-from-bracket mr-2 text-red-500"></i> Logout
        </button>
      </form>
    </div>
  </div>
</nav>

<script>
  const profileMenuButton = document.getElementById('profileMenuButton');
  const dropdownMenu = document.getElementById('dropdownMenu');
  const arrowIcon = document.getElementById('arrowIcon');

  // Toggle dropdown & panah
  profileMenuButton.addEventListener('click', () => {
    dropdownMenu.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotate-180'); // efek panah berputar ke atas saat terbuka
  });

  // Klik di luar dropdown untuk menutup
  document.addEventListener('click', (e) => {
    if (!profileMenuButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
      dropdownMenu.classList.add('hidden');
      arrowIcon.classList.remove('rotate-180');
    }
  });
</script>
