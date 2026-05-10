<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuruDocs - Dashboard Guru</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50">

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

</body>
</html>
