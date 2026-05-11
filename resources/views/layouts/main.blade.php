<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuruDocs - @yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/css/all.min.css" />
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50">

    {{-- LOADING SCREEN --}}
    <div id="loader"
         class="fixed inset-0 z-[9999] bg-white flex flex-col items-center justify-center transition-all duration-300 hidden">

        <lottie-player
            src="{{ asset('lottie/Loading Dots.json') }}"
            background="transparent"
            speed="1"
            style="width: 400px; height: 400px;"
            loop
            autoplay>
        </lottie-player>

    </div>

    {{-- ===== NAVBAR (selalu di atas) ===== --}}
    <header class="fixed top-0 left-0 right-0 z-50 bg-[#1f331d] text-white shadow-md h-[70px] flex items-center px-6">
        @include('components.navbar')
    </header>

    {{-- ===== WRAPPER SIDEBAR & CONTENT ===== --}}
    <div class="flex"> {{-- padding top = tinggi navbar --}}
        
        {{-- ===== SIDEBAR ===== --}}
        <aside class="sidebar w-64 bg-white text-[#1f331d] min-h-screen border-r border-gray-200">
            @include('components.sidebar')
        </aside>

        {{-- ===== KONTEN UTAMA ===== --}}
        <main class="content flex-1 p-6">
            @yield('content')
        </main>
    </div>

<script>
    const loader = document.getElementById('loader');
    let loadingTimer = null;

    // saat halaman selesai load
    window.addEventListener('load', function () {
        loader.classList.add('opacity-0');

        setTimeout(() => {
            loader.style.display = 'none';
        }, 300);
    });

    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function (e) {

            const href = this.getAttribute('href');

            // abaikan link internal / blank
            if (!href || href.startsWith('#') || this.target === '_blank') {
                return;
            }

            // reset loader state
            clearTimeout(loadingTimer);

            // tunggu dulu sebelum tampilkan loader
            loadingTimer = setTimeout(() => {
                loader.style.display = 'flex';
                loader.classList.remove('opacity-0');
            }, 10000); // ⬅️ threshold (ubah sesuai kebutuhan)

        });
    });
</script>   
</body>
</html>
