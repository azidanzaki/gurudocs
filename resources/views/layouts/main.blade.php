<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuruDocs - Dashboard Guru</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/css/all.min.css" />


    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex">
    {{-- Tombol burger menu (untuk tampilan mobile opsional) --}}
    <button id="toggleSidebar" class="burger-btn hidden">☰</button>

    {{-- Sidebar --}}
    <aside class="w-64 text-white min-h-screen">
        @include('components.sidebar')
    </aside>

    {{-- Konten utama --}}
    <main class="flex-1 p-6">
        @yield('content')
    </main>
</body>


</html>