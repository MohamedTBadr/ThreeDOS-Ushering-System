<style>
/* ====================================
   UNIFIED THREEDOS SIDEBAR STYLES
==================================== */
.dashboard-container {
    transition: grid-template-columns 0.3s ease !important;
}

.dashboard-container.sidebar-closed {
    grid-template-columns: 0px 1fr !important;
}

.sidebar {
    background: linear-gradient(180deg, #252429 0%, #19191C 100%) !important;
    border-right: 1px solid var(--border, #565657) !important;
    padding: 2rem 1.25rem !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 1.5rem !important;
    position: sticky !important;
    top: 0 !important;
    height: 100vh !important;
    overflow-y: auto !important;
    z-index: 1000 !important;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), margin-left 0.3s ease, opacity 0.3s ease !important;
    width: 280px !important;
    flex-shrink: 0 !important;
    box-sizing: border-box !important;
}

.sidebar.closed {
    margin-left: -280px !important;
    opacity: 0 !important;
    pointer-events: none !important;
    transform: translateX(-100%) !important;
}

.header-logo {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    text-align: center !important;
    gap: 0.85rem !important;
    padding-bottom: 0.5rem !important;
}

.logo-container {
    width: 76px !important;
    height: 76px !important;
    border-radius: 18px !important;
    overflow: hidden !important;
    border: 2px solid rgba(127, 71, 151, 0.35) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4), 0 0 15px rgba(127, 71, 151, 0.2) !important;
    transition: transform 0.3s ease !important;
}

.logo-container:hover {
    transform: scale(1.04) !important;
    border-color: var(--primary-light, #9A6BB2) !important;
}

.logo-img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
}

.org-name {
    font-size: 1.45rem !important;
    font-weight: 700 !important;
    letter-spacing: -0.02em !important;
    background: linear-gradient(135deg, #9A6BB2, #7F4797) !important;
    -webkit-background-clip: text !important;
    background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
}

.org-subtitle {
    font-size: 0.72rem !important;
    color: var(--text-secondary, #B0B0B0) !important;
    text-transform: uppercase !important;
    letter-spacing: 1.2px !important;
    margin-top: 3px !important;
    font-weight: 600 !important;
}

.nav-menu {
    display: flex !important;
    flex-direction: column !important;
    gap: 0.5rem !important;
    flex: 1 !important;
}

.nav-item {
    display: flex !important;
    align-items: center !important;
    gap: 0.85rem !important;
    padding: 0.85rem 1.15rem !important;
    border-radius: 12px !important;
    color: var(--text-secondary, #B0B0B0) !important;
    text-decoration: none !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    font-weight: 500 !important;
    font-size: 0.92rem !important;
    line-height: 1.2 !important;
}

.nav-item:hover {
    background: rgba(127, 71, 151, 0.15) !important;
    color: var(--primary-light, #9A6BB2) !important;
    transform: translateX(4px) !important;
}

.nav-item.active {
    background: linear-gradient(135deg, rgba(127, 71, 151, 0.35) 0%, rgba(106, 58, 130, 0.2) 100%) !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    box-shadow: inset 0 0 0 1px rgba(154, 107, 178, 0.35), 0 4px 12px rgba(127, 71, 151, 0.25) !important;
    border-left: 4px solid var(--primary, #7F4797) !important;
}

.nav-item-logout {
    margin-top: auto !important;
    background: rgba(239, 68, 68, 0.12) !important;
    color: #f87171 !important;
    border: 1px solid rgba(239, 68, 68, 0.3) !important;
}

.nav-item-logout:hover {
    background: #ef4444 !important;
    color: #ffffff !important;
    box-shadow: 0 6px 18px rgba(239, 68, 68, 0.4) !important;
    transform: translateY(-2px) !important;
}

.nav-item .icon,
.nav-item i {
    font-size: 1.15rem !important;
    width: 20px !important;
    text-align: center !important;
}

.close-sidebar-btn {
    display: flex !important;
    position: absolute !important;
    top: 1rem !important;
    right: 1rem !important;
    background: rgba(255, 255, 255, 0.08) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: var(--text-secondary, #B0B0B0) !important;
    font-size: 1.2rem !important;
    cursor: pointer !important;
    width: 34px !important;
    height: 34px !important;
    border-radius: 10px !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.2s ease !important;
    z-index: 1002 !important;
}

.close-sidebar-btn:hover {
    background: rgba(239, 68, 68, 0.2) !important;
    color: #ef4444 !important;
    border-color: rgba(239, 68, 68, 0.4) !important;
}

.menu-toggle {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 42px !important;
    height: 42px !important;
    border-radius: 12px !important;
    background: rgba(127,71,151,0.14) !important;
    border: 1px solid rgba(127,71,151,0.35) !important;
    color: #F4F4F4 !important;
    font-size: 1.2rem !important;
    cursor: pointer !important;
}

@media (max-width: 1024px) {
    .dashboard-container {
        grid-template-columns: 1fr !important;
    }
    .sidebar {
        position: fixed !important;
        left: 0 !important;
        top: 0 !important;
        bottom: 0 !important;
        transform: translateX(-100%) !important;
        z-index: 2000 !important;
        box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5) !important;
    }
    .sidebar.active {
        transform: translateX(0) !important;
    }
}
</style>

<aside class="sidebar" id="sidebar">
    <button class="close-sidebar-btn" aria-label="Close sidebar"><i class="fas fa-times"></i></button>
    <div class="header-logo">
        <div class="logo-container">
            <img src="{{ asset('img/ThreeDOS.jpg') }}" alt="ThreeDOS" class="logo-img" onerror="this.style.display='none'">
        </div>
        <div>
            <div class="org-name">ThreeDOS'26</div>
            <div class="org-subtitle">Academic Councils</div>
        </div>
    </div>
    <nav class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-users icon"></i> <span>Applicants</span>
        </a>
        <a href="{{ route('calendar') }}" class="nav-item {{ request()->routeIs('calendar') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt icon"></i> <span>Calendar</span>
        </a>
        <a href="{{ route('statistics') }}" class="nav-item {{ request()->routeIs('statistics') ? 'active' : '' }}">
            <i class="fas fa-chart-bar icon"></i> <span>Statistics</span>
        </a>
        <a href="{{ route('usher.stats') }}" class="nav-item {{ request()->routeIs('usher.stats') ? 'active' : '' }}">
            <i class="fas fa-medal icon"></i> <span>Usher Stats</span>
        </a>
        <a href="{{ route('interviewer.stats') }}" class="nav-item {{ request()->routeIs('interviewer.stats') ? 'active' : '' }}">
            <i class="fas fa-user-check icon"></i> <span>Interviewer Stats</span>
        </a>
        <a href="{{ route('registration.form') }}" class="nav-item {{ request()->routeIs('registration.form') ? 'active' : '' }}">
            <i class="fas fa-user-plus icon"></i> <span>New Registration</span>
        </a>
        {{-- <a href="{{ route('logs.index') }}" class="nav-item {{ request()->routeIs('logs.*') ? 'active' : '' }}" id="sidebar-logs-link" style="display:none">
            <i class="fas fa-file-alt icon"></i> <span>System Logs</span>
        </a> --}}
        <a href="#" onclick="event.preventDefault(); localStorage.clear(); sessionStorage.clear(); window.location='{{ route('login') }}'" class="nav-item nav-item-logout">
            <i class="fas fa-sign-out-alt icon"></i> <span>Logout</span>
        </a>
    </nav>
</aside>
<script>
// Show logs link only for Backend Development council (localStorage or session)
(function(){
    const council = localStorage.getItem('user_council');
    if (council === 'Backend Development') {
        const el = document.getElementById('sidebar-logs-link');
        if (el) el.style.display = 'flex';
    }
})();
</script>
