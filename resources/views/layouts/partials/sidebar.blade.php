<aside class="sidebar" id="sidebar">
    <div class="header-logo">
        <div class="logo-container">
            <img src="{{ asset('img/ThreeDOS.jpg') }}" alt="ThreeDOS" class="logo-img">
        </div>
        <div>
            <div class="org-name">ThreeDOS'26</div>
            <div class="org-subtitle">Ushering System</div>
        </div>
    </div>
    <nav class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">📊 Dashboard</a>
        <a href="{{ route('statistics') }}" class="nav-item {{ request()->routeIs('statistics') ? 'active' : '' }}">📈 Statistics</a>
        <a href="{{ route('calendar') }}" class="nav-item {{ request()->routeIs('calendar') ? 'active' : '' }}">📅 Calendar</a>
        <a href="{{ route('usher.stats') }}" class="nav-item {{ request()->routeIs('usher.stats') ? 'active' : '' }}">👥 Usher Stats</a>
        @if(auth()->check() && auth()->user()->council === 'Backend Development')
        <a href="{{ route('logs.index') }}" class="nav-item {{ request()->routeIs('logs.*') ? 'active' : '' }}">📋 Logs</a>
        @endif
        <a href="#" onclick="event.preventDefault(); localStorage.clear(); window.location='{{ route('login') }}'" class="nav-item">🚪 Logout</a>
    </nav>
</aside>
