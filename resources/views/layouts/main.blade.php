@extends('adminlte::page')

@section('title', 'GuruDocs')

@section('content_header')
    <h1>@yield('title')</h1>
@stop

@section('content')

    {{-- LOADER (opsional, kalau mau tetap dipakai) --}}
    <div id="loader"
         class="fixed inset-0 z-[9999] bg-white flex flex-col items-center justify-center hidden">

        <lottie-player
            src="{{ asset('lottie/Loading Dots.json') }}"
            background="transparent"
            speed="1"
            style="width: 300px; height: 300px;"
            loop
            autoplay>
        </lottie-player>

    </div>

    @yield('content')

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmLogout(event) {
  event.preventDefault();

  Swal.fire({
    title: 'Yakin ingin logout?',
    text: "Kamu akan keluar dari sistem",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, logout',
    cancelButtonText: 'Batal',
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('logout-form').submit();
    }
  });
}
</script>
@stop