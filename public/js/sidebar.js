document.addEventListener('DOMContentLoaded', () => {
    let overlay = document.querySelector('.sidebar-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        overlay.id = 'sidebarOverlay';
        document.body.appendChild(overlay);
    }
    const sidebar = document.querySelector('.sidebar');
    const container = document.querySelector('.dashboard-container');
    if (!sidebar) return;

    function isMobile() { return window.innerWidth <= 1024; }

    function openSidebar() {
        if (isMobile()) {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            sidebar.classList.remove('closed');
            sidebar.classList.remove('active');
            if (container) container.classList.remove('sidebar-closed');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    function closeSidebar() {
        if (isMobile()) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        } else {
            sidebar.classList.add('closed');
            if (container) container.classList.add('sidebar-closed');
        }
    }

    window.toggleSidebar = function() {
        if (isMobile()) {
            if (sidebar.classList.contains('active')) closeSidebar();
            else openSidebar();
        } else {
            if (sidebar.classList.contains('closed')) openSidebar();
            else closeSidebar();
        }
    };
    window.openSidebar = openSidebar;
    window.closeSidebar = closeSidebar;

    // Delegated click for any current/future menu-toggle or close button
    document.addEventListener('click', (e) => {
        const toggle = e.target.closest('.menu-toggle');
        if (toggle) {
            e.preventDefault();
            e.stopPropagation();
            window.toggleSidebar();
            return;
        }
        const closer = e.target.closest('.close-sidebar-btn');
        if (closer) {
            e.preventDefault();
            e.stopPropagation();
            closeSidebar();
        }
    });

    overlay.addEventListener('click', closeSidebar);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeSidebar(); });
    sidebar.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', () => { if (isMobile()) closeSidebar(); });
    });
    // Handle resize: clear mobile overlay when going to desktop
    window.addEventListener('resize', () => {
        if (!isMobile()) {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            // keep closed state if user closed on desktop, otherwise ensure visible
            if (!sidebar.classList.contains('closed')) {
                sidebar.classList.remove('active');
            }
        } else {
            // on mobile, remove desktop closed state
            if (sidebar.classList.contains('closed')) {
                sidebar.classList.remove('closed');
                if (container) container.classList.remove('sidebar-closed');
            }
        }
    });
});
