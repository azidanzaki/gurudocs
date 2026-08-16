@php( $logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout') )
@php( $profile_url = View::getSection('profile_url') ?? config('adminlte.profile_url', 'logout') )

@if (config('adminlte.usermenu_profile_url', false))
    @php( $profile_url = Auth::user()->adminlte_profile_url() )
@endif

@if (config('adminlte.use_route_url', false))
    @php( $profile_url = $profile_url ? route($profile_url) : '' )
    @php( $logout_url = $logout_url ? route($logout_url) : '' )
@else
    @php( $profile_url = $profile_url ? url($profile_url) : '' )
    @php( $logout_url = $logout_url ? url($logout_url) : '' )
@endif

<style>
    /* Modern User Dropdown Styles */
    .modern-user-dropdown {
        border-radius: 16px !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1) !important;
        padding: 8px !important;
        min-width: 260px;
        margin-top: 10px !important;
        animation: fadeInDropdown 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    @keyframes fadeInDropdown {
        0% { opacity: 0; transform: translateY(-10px) scale(0.95); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    
    .modern-user-dropdown .user-header-modern {
        display: flex;
        align-items: center;
        padding: 12px 12px 16px;
        margin-bottom: 8px;
        border-bottom: 1px solid #f1f3f5;
    }
    
    .user-header-modern img {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    
    .user-header-info {
        margin-left: 14px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .user-header-info .user-name {
        font-weight: 700;
        color: #2c3e50;
        font-size: 1.05rem;
        line-height: 1.2;
    }
    
    .user-header-info .user-role {
        font-size: 0.85rem;
        color: #868e96;
        margin-top: 4px;
        font-weight: 500;
    }
    
    .modern-dropdown-item {
        display: flex;
        align-items: center;
        padding: 10px 14px;
        color: #495057;
        font-weight: 500;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-decoration: none !important;
        margin-bottom: 4px;
    }
    
    .modern-dropdown-item i {
        width: 24px;
        font-size: 1.1rem;
        color: #adb5bd;
        transition: color 0.2s ease;
    }
    
    .modern-dropdown-item:hover {
        background-color: #f8f9fa;
        color: #228be6;
        transform: translateX(2px);
    }
    
    .modern-dropdown-item:hover i {
        color: #228be6;
    }
    
    .modern-dropdown-item.logout-item {
        margin-top: 8px;
        border-top: 1px solid #f1f3f5;
        border-radius: 0 0 10px 10px;
        padding-top: 12px;
    }
    
    .modern-dropdown-item.logout-item:hover {
        background-color: #fff5f5;
        color: #fa5252;
    }
    
    .modern-dropdown-item.logout-item:hover i {
        color: #fa5252;
    }
    
    /* Navbar Toggler Modern */
    .user-menu .dropdown-toggle {
        display: flex;
        align-items: center;
        padding-right: 0.8rem !important;
        padding-left: 0.8rem !important;
        transition: all 0.2s ease;
    }
    
    .user-menu .dropdown-toggle:hover {
        background-color: rgba(0,0,0,0.03);
        border-radius: 8px;
    }
    
    .user-menu .dropdown-toggle img {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        margin-left: 10px; /* Changed from margin-right */
        object-fit: cover;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .user-menu .dropdown-toggle span {
        font-weight: 600;
        color: #495057;
    }
</style>

<li class="nav-item dropdown user-menu">

    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <span @if(config('adminlte.usermenu_image')) class="d-none d-md-inline" @endif>
            {{ Auth::user()->name }}
        </span>
        @if(config('adminlte.usermenu_image'))
            <img src="{{ Auth::user()->adminlte_image() }}" alt="{{ Auth::user()->name }}">
        @endif
    </a>

    <ul class="dropdown-menu dropdown-menu-right modern-user-dropdown">

        <li class="user-header-modern">
            @if(config('adminlte.usermenu_image'))
                <img src="{{ Auth::user()->adminlte_image() }}" alt="{{ Auth::user()->name }}">
            @endif
            <div class="user-header-info">
                <span class="user-name">{{ Auth::user()->name }}</span>
                @if(config('adminlte.usermenu_desc'))
                    <span class="user-role">{{ Auth::user()->adminlte_desc() }}</span>
                @endif
            </div>
        </li>

        @each('adminlte::partials.navbar.dropdown-item', $adminlte->menu("navbar-user"), 'item')

        @hasSection('usermenu_body')
            <li><hr class="dropdown-divider my-1"></li>
            <li class="px-2 py-1">
                @yield('usermenu_body')
            </li>
            <li><hr class="dropdown-divider my-1"></li>
        @endif

        @if($profile_url)
        <li>
            <a href="{{ $profile_url }}" class="modern-dropdown-item">
                <i class="far fa-user-circle"></i>
                <span>Profil Saya</span>
            </a>
        </li>
        @endif

        <li>
            <a href="#" class="modern-dropdown-item logout-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Keluar</span>
            </a>
            
            <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
                @if(config('adminlte.logout_method'))
                    {{ method_field(config('adminlte.logout_method')) }}
                @endif
                {{ csrf_field() }}
            </form>
        </li>
    </ul>
</li>
