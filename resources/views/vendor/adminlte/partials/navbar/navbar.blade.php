@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Language Switcher --}}
        <li class="nav-item d-flex align-items-center pr-3 mr-2 border-right">
            <a href="{{ route('lang.switch', 'id') }}" class="nav-link px-1 {{ app()->getLocale() == 'id' ? 'text-success font-weight-bold' : 'text-muted' }}" style="font-size: 14px;">ID</a>
            <span class="text-muted text-xs mx-1">|</span>
            <a href="{{ route('lang.switch', 'en') }}" class="nav-link px-1 {{ app()->getLocale() == 'en' ? 'text-success font-weight-bold' : 'text-muted' }}" style="font-size: 14px;">EN</a>
        </li>

        {{-- Dark/Light Mode Toggle --}}
        <li class="nav-item">
            <a href="#" id="dark-mode-toggle" class="nav-link" style="cursor: pointer;">
                <i id="dark-mode-icon" class="fas fa-moon"></i>
            </a>
        </li>

        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

</nav>
