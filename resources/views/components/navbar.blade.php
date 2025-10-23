<nav class="navbar flex items-center justify-between px-6 py-3 bg-white fixed top-0 left-0 w-full z-50" style="background: #1f331d;">
  <!-- Kiri: Tombol toggle sidebar -->
  <button id="toggleSidebar" class="text-[#1f331d] text-2xl md:hidden">
    <i class="fa-solid fa-bars"></i>
  </button>

  <!-- Tengah: Judul halaman -->
  <h1 class="text-lg font-semibold text-white">GuruDocs</h1>

  <!-- Kanan: Profil mini -->
  <div class="relative">
    <div class="flex items-center space-x-3 cursor-pointer" id="profileMenuButton">
      <span class="text-white font-medium hidden sm:inline">M. Zidan Zaki</span>
      <img
        src="https://www.freeiconspng.com/uploads/png-file-png-file-png-file-png-file-png-file-27.png"
        alt="User Avatar"
        class="w-9 h-9 rounded-full border border-gray-300"
      />
    </div>

    <!-- Dropdown Menu -->
    <div
      id="dropdownMenu"
      class="hidden absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg py-2"
    >
      <a
        href="#"
        class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
      >
        Profil
      </a>
      <hr class="my-1 border-gray-200">
      <form action="/logout" method="POST">
        <!-- Tambahkan CSRF token jika pakai Laravel -->
        <button
          type="submit"
          class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
        >
          Logout
        </button>
      </form>
    </div>
  </div>
</nav>

<script>
  // Script toggle dropdown
  const profileMenuButton = document.getElementById('profileMenuButton');
  const dropdownMenu = document.getElementById('dropdownMenu');

  profileMenuButton.addEventListener('click', () => {
    dropdownMenu.classList.toggle('hidden');
  });

  // Klik di luar dropdown untuk menutup
  document.addEventListener('click', (e) => {
    if (!profileMenuButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
      dropdownMenu.classList.add('hidden');
    }
  });
</script>
