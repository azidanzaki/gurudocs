document.addEventListener('DOMContentLoaded', () => {
  // ======================
  // SIDEBAR
  // ======================
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('toggleSidebar');
  const closeBtn = document.getElementById('closeSidebar');

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('show');
    });
  }

  if (closeBtn && sidebar) {
    closeBtn.addEventListener('click', () => {
      sidebar.classList.remove('show');
    });
  }

  // ======================
  // LOADER
  // ======================
  const loader = document.getElementById('loader');
  let loadingTimer = null;

  if (loader) {
    window.addEventListener('load', function () {
      loader.classList.add('opacity-0');

      setTimeout(() => {
        loader.style.display = 'none';
      }, 300);
    });
  }

  document.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', function (e) {

      const href = this.getAttribute('href');

      if (!href || href.startsWith('#') || this.target === '_blank') {
        return;
      }

      clearTimeout(loadingTimer);

      loadingTimer = setTimeout(() => {
        if (loader) {
          loader.style.display = 'flex';
          loader.classList.remove('opacity-0');
        }
      }, 2000);
    });
  });

  // ======================
  // LOGOUT SWEETALERT
  // ======================
  window.confirmLogout = function (event) {
    event.preventDefault();

    if (typeof Swal === 'undefined') {
      console.error('SweetAlert belum di-load');
      return;
    }

    Swal.fire({
      title: 'Yakin ingin logout?',
      text: "Kamu akan keluar dari sistem",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, logout',
      cancelButtonText: 'Batal',

      customClass: {
        popup: 'glass-swal',
        title: 'swal-title',
        htmlContainer: 'swal-text',
        confirmButton: 'swal-confirm',
        cancelButton: 'swal-cancel'
      },

      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.getElementById('logout-form');
        if (form) form.submit();
      }
    });
  };
});