<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreeDOS Applicant Management System - Statistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/ThreeDOS.jpg') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* =========================
            GLOBAL RESET
        ========================= */
        :root {
            --threedos-purple: #7F4797;
            --threedos-purple-light: #9A6BB2;
            --bg-dark: #19191C;
            --bg-card: #252429;
            --text-main: #F4F4F4;
            --text-muted: #B0B0B0;
            --border-color: #565657;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-card) 100%);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* =========================
            DASHBOARD LAYOUT
        ========================= */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* =========================
            SIDEBAR - (ØªÙ… ØªØ¹Ø¯ÙŠÙ„ Ø§Ù„Ù€ position Ù‡Ù†Ø§)
        ========================= */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, var(--bg-card) 0%, var(--bg-dark) 100%);
            border-right: 1px solid var(--border-color);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            position: fixed;
            /* ØªÙ… Ø§Ù„ØªØºÙŠÙŠØ± Ù…Ù† sticky Ù„Ù€ fixed Ù„Ø«Ø¨Ø§Øª Ø§Ù„Ù‚Ø§Ø¦Ù…Ø© */
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            flex-shrink: 0;
            z-index: 1100;
            /* Ø£Ø¹Ù„Ù‰ Ù…Ù† Ø§Ù„Ù€ overlay */
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .logo h2 {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--threedos-purple-light), var(--threedos-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            border-radius: 0.75rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-item:hover {
            background: rgba(127, 71, 151, 0.15);
            color: var(--threedos-purple-light);
            transform: translateX(4px);
        }

        .nav-item.active {
            background: rgba(127, 71, 151, 0.25);
            color: var(--threedos-purple-light);
            border-left: 3px solid var(--threedos-purple);
        }

        .nav-item .icon {
            font-size: 1.25rem;
        }

        /* ==================== HEADER & LOGO ==================== */
        .header-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .logo-container {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            animation: logoFloat 3s ease-in-out infinite;
            border: 3px solid rgba(255, 255, 255, 0.1);
        }

        @keyframes logoFloat {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(1deg);
            }
        }

        .logo-img {
            width: 85%;
            height: 85%;
            object-fit: contain;
            border-radius: 12px;
            position: relative;
            z-index: 2;
        }

        .logo-fallback {
            font-size: 3rem;
            color: white;
            position: relative;
            z-index: 2;
        }

        .org-title {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .org-name {
            font-size: 2.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, var(--threedos-purple-light) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1;
        }

        .org-subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 400;
            letter-spacing: 2px;
            text-transform: uppercase;
            position: relative;
            padding: 0 10px;
        }

        /* =========================
            MAIN CONTENT - (ØªÙ… Ø¥Ø¶Ø§ÙØ© margin Ù„ØªØ¹ÙˆÙŠØ¶ Ø§Ù„Ù€ sidebar Ø§Ù„Ù€ fixed)
        ========================= */
        .main-content {
            padding: 2rem;
            flex-grow: 1;
            width: 100%;
            min-width: 0;
            margin-left: 280px;
            /* Ù„ØªØ±Ùƒ Ù…Ø³Ø§Ø­Ø© Ù„Ù„Ù‚Ø§Ø¦Ù…Ø© Ø§Ù„Ø¬Ø§Ù†Ø¨ÙŠØ© */
        }

        /* =========================
            HEADER
        ========================= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 2rem;
            gap: 1rem;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.2;
        }

        /* =========================
            STATS CARDS
        ========================= */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--bg-card), var(--bg-dark));
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 32px rgba(0, 0, 0, 0.35);
            border-color: var(--threedos-purple);
        }

        .stat-card h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--text-main);
        }

        .stat-card p {
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* =========================
            TABLE
        ========================= */
        .table-container {
            width: 100%;
            overflow-x: auto;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
            background: var(--bg-card);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
            border: none;
            margin: 0;
            border-radius: 0;
        }

        thead {
            background: #343338;
        }

        thead th {
            padding: 1rem;
            text-align: left;
            color: var(--text-main);
            font-weight: 600;
            white-space: nowrap;
        }

        tbody td {
            padding: 1rem;
            border-top: 1px solid var(--border-color);
            color: var(--text-main);
        }

        tbody tr:hover {
            background: rgba(127, 71, 151, 0.1);
        }

        /* =========================
            CHARTS CONTAINER
        ========================= */
        .chart-container {
            display: flex;
            justify-content: center;
            align-items: stretch;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .chart-card {
            flex: 1;
            min-width: 300px;
            min-height: 400px;
            background: linear-gradient(135deg, var(--bg-card), var(--bg-dark));
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }

        .chart-title {
            text-align: center;
            margin-bottom: 1rem;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .chart-wrapper {
            width: 100%;
            height: 300px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chart-wrapper canvas {
            width: 100% !important;
            height: 100% !important;
        }

        /* =========================
            BUTTONS
        ========================= */
        button {
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-refresh,
        .btn-export {
            background: rgba(127, 71, 151, 0.15);
            border: 1px solid var(--threedos-purple);
            color: var(--threedos-purple-light);
        }

        .btn-refresh:hover,
        .btn-export:hover {
            background: var(--threedos-purple);
            color: #fff;
            transform: translateY(-2px);
        }

        /* =========================
            SIDEBAR TOGGLE & OVERLAY
        ========================= */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-main);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            margin-right: 1rem;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            /* ØªØ­Øª Ø§Ù„Ù€ sidebar */
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* =========================
            LOADER
        ========================= */
        #global-loader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-card) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }

        #global-loader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .global-loader-logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: var(--bg-card);
            box-shadow: 0 0 0 4px rgba(127, 71, 151, 0.2), 0 0 40px rgba(127, 71, 151, 0.4);
            overflow: hidden;
            animation: logoPulse 2s infinite ease-in-out;
        }

        .global-loader-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @keyframes logoPulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 4px rgba(127, 71, 151, 0.2);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 8px rgba(127, 71, 151, 0.3);
            }
        }

        .global-loader-text {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-main);
            background: linear-gradient(90deg, #9A6BB2, #FF6B8B, #9A6BB2);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: textShimmer 3s linear infinite;
        }

        .global-loader-bar {
            width: 200px;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .global-loader-bar::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 40%;
            background: #7F4797;
            border-radius: 4px;
            box-shadow: 0 0 10px #7F4797;
            animation: barLoading 1.5s ease-in-out infinite;
        }

        @keyframes textShimmer {
            to {
                background-position: 200% center;
            }
        }

        @keyframes barLoading {
            0% {
                left: -40%;
            }

            50% {
                left: 40%;
                width: 60%;
            }

            100% {
                left: 100%;
                width: 40%;
            }
        }

        /* =========================
            RESPONSIVE BREAKPOINTS
        ========================= */
        @media (max-width: 1024px) {
            .menu-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                /* Ø¥Ù„ØºØ§Ø¡ Ø§Ù„Ù€ margin ÙÙŠ Ø§Ù„Ù…ÙˆØ¨Ø§ÙŠÙ„ */
            }

            .chart-card {
                flex: 1 1 100%;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1.5rem;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 1rem;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .header>div {
                width: 100%;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .btn-refresh,
            .btn-export {
                flex: 1;
                text-align: center;
                font-size: 0.85rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .chart-wrapper {
                height: 250px;
            }

            .chart-card {
                padding: 1rem;
                min-height: auto;
            }
        }
    </style>
</head>

<body>
    <div id="global-loader">
        <div class="global-loader-logo">
            <img src="{{ asset('img/ThreeDOS.jpg') }}" alt="ThreeDOS Logo">
        </div>
        <div class="global-loader-text">ThreeDOS</div>
        <div class="global-loader-bar"></div>
    </div>

    <div class="dashboard-container">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <aside class="sidebar" id="sidebar">
            <div class="header-logo">
                <div class="logo-container">
                    <img src="{{ asset('img/ThreeDOS.jpg') }}" alt="ThreeDOS Logo" class="logo-img" id="logoImage"
                        onerror="this.style.display='none'; document.getElementById('logoFallback').style.display='block';">
                    <div class="logo-fallback" id="logoFallback" style="display: none;">
                        <i class="fas fa-users-crown"></i>
                    </div>
                </div>
                <div class="org-title">
                    <div class="org-name">ThreeDOS</div>
                    <div class="org-subtitle">Academic Councils</div>
                </div>
            </div>
            <nav class="nav-menu">
                <a href="dashboard.html" class="nav-item" data-view="applicants">
                    <span class="icon">ðŸ‘¥</span>
                    <span>Applicants</span>
                </a>
                <a href="calendar.html" class="nav-item">
                    <span class="icon">ðŸ“…</span>
                    <span>Calendar</span>
                </a>
                <a href="statistics_page.html" class="nav-item active" data-view="statistics">
                    <span class="icon">ðŸ“Š</span>
                    <span>Statistics</span>
                </a>
                <a href="RegistrationForm.html" class="nav-item">
                    <span class="icon">âž•</span>
                    <span>New Registration</span>
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header">
                <div style="display:flex; align-items:center;">
                    <button class="menu-toggle" id="menuToggle">â˜°</button>
                    <h1>Statistics Overview</h1>
                </div>
                <div>
                    <button class="btn-refresh" onclick="loadStatistics()">ðŸ”„ Refresh</button>
                    <button class="btn-export" onclick="exportCSV()">ðŸ“¥ Export CSV</button>
                </div>
            </header>

            <div class="stats-grid" id="stats-grid"></div>

            <div class="chart-container">
                <div class="chart-card">
                    <div class="chart-title">Rating Charts</div>
                    <div class="chart-wrapper">
                        <canvas id="ratingChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-title">Level Chart</div>
                    <div class="chart-wrapper">
                        <canvas id="levelChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-title">Council Distribution</div>
                    <div class="chart-wrapper">
                        <canvas id="councilChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-title">Event Type Distribution</div>
                    <div class="chart-wrapper">
                        <canvas id="eventTypeChart"></canvas>
                    </div>
                </div>
            </div>
            <br>

            <div class="table-container">
                <table id="stats-table">
                    <thead>
                        <tr>
                            <th>Level</th>
                            <th>Council</th>
                            <th>Total Applicants</th>
                            <th>Pending</th>
                            <th>Accepted</th>
                            <th>Backup (B)</th>
                            <th>Rejected</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        // ==========================================
        // MOBILE SIDEBAR TOGGLE
        // ==========================================
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const menuToggle = document.getElementById('menuToggle');

        function openSidebar() {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (menuToggle) menuToggle.addEventListener('click', openSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);

        // ==========================================
        // STATISTICS LOGIC (UNCHANGED)
        // ==========================================
        const API_URL = '{{ url('api/registrations') }}';
        const TOKEN = localStorage.getItem('usher_token');
        if (!TOKEN) window.location.href = 'register.html';

        let ratingChart, levelChart, councilChart, eventTypeChart;
        let allApplicants = [];

        async function loadStatistics() {
            const grid = document.getElementById('stats-grid');
            const tableBody = document.querySelector('#stats-table tbody');

            grid.innerHTML = '<div>Loading statistics...</div>';
            tableBody.innerHTML = '';

            try {
                // Fetch ALL applicants
                allApplicants = [];
                let cursor = null;
                let hasMore = true;

                while (hasMore) {
                    let url = `${API_URL}?limit=500`;
                    if (cursor) url += `&cursor=${cursor}`;

                    const res = await fetch(url, { headers: { 'X-Token': TOKEN } });
                    if (res.status === 401) window.location.href = 'register.html';

                    const data = await res.json();
                    if (data.status !== 'success') {
                        alert(data.message);
                        return;
                    }

                    allApplicants = allApplicants.concat(data.data.applicants);
                    hasMore = data.data.has_more;
                    cursor = data.data.next_cursor;

                    grid.innerHTML = `<div>Loading... (${allApplicants.length} records)</div>`;
                }

                const applicants = allApplicants;

                // Quick stats cards
                const ratingMap = { Pending: 0, Acceptance: 0, B: 0, Rejection: 0 };
                const eventTypeMap = {};

                applicants.forEach(a => {
                    ratingMap[a.rating || 'Pending'] = (ratingMap[a.rating || 'Pending'] || 0) + 1;
                    const et = a.event_type || 'Interview';
                    eventTypeMap[et] = (eventTypeMap[et] || 0) + 1;
                });

                grid.innerHTML = `
                    <div class="stat-card"><h2>${applicants.length}</h2><p>Total Applicants</p></div>
                    <div class="stat-card"><h2>${ratingMap.Acceptance}</h2><p>Accepted</p></div>
                    <div class="stat-card"><h2>${ratingMap.B}</h2><p>Backup (B)</p></div>
                    <div class="stat-card"><h2>${ratingMap.Rejection}</h2><p>Rejected</p></div>
                    <div class="stat-card"><h2>${ratingMap.Pending}</h2><p>Pending</p></div>
                `;

                // Aggregations
                const tableMap = {};
                const levelMap = {};
                const councilMap = {};

                applicants.forEach(a => {
                    const lvl = a.level || 'Unknown';
                    const c = a.council || 'None';
                    const r = a.rating || 'Pending';

                    const key = `${lvl}_${c}`;
                    if (!tableMap[key]) {
                        tableMap[key] = { total: 0, Pending: 0, Acceptance: 0, B: 0, Rejection: 0, level: lvl, council: c };
                    }
                    tableMap[key].total++;
                    tableMap[key][r]++;

                    if (!levelMap[lvl]) levelMap[lvl] = { Pending: 0, Acceptance: 0, B: 0, Rejection: 0 };
                    levelMap[lvl][r]++;

                    if (!councilMap[c]) councilMap[c] = { Pending: 0, Acceptance: 0, B: 0, Rejection: 0 };
                    councilMap[c][r]++;
                });

                // Table
                tableBody.innerHTML = '';
                Object.values(tableMap).forEach(row => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.level}</td>
                        <td>${row.council}</td>
                        <td>${row.total}</td>
                        <td>${row.Pending}</td>
                        <td>${row.Acceptance}</td>
                        <td>${row.B}</td>
                        <td>${row.Rejection}</td>
                    `;
                    tableBody.appendChild(tr);
                });

                // Charts
                renderCharts(ratingMap, levelMap, councilMap, eventTypeMap);

            } catch (err) {
                console.error(err);
                alert('Failed to load statistics');
            } finally {
                hideLoader();
            }
        }

        function renderCharts(ratingMap, levelMap, councilMap, eventTypeMap) {
            // Rating Pie
            if (ratingChart) ratingChart.destroy();
            ratingChart = new Chart(document.getElementById('ratingChart'), {
                type: 'pie',
                data: {
                    labels: Object.keys(ratingMap),
                    datasets: [{
                        data: Object.values(ratingMap),
                        backgroundColor: ['#F0AD4E', '#5CB85C', '#5BC0DE', '#D9534F']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });

            // Level Bar
            if (levelChart) levelChart.destroy();
            levelChart = new Chart(document.getElementById('levelChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(levelMap),
                    datasets: [
                        { label: 'Pending', data: Object.values(levelMap).map(l => l.Pending), backgroundColor: '#F0AD4E' },
                        { label: 'Accepted', data: Object.values(levelMap).map(l => l.Acceptance), backgroundColor: '#5CB85C' },
                        { label: 'Backup', data: Object.values(levelMap).map(l => l.B), backgroundColor: '#5BC0DE' },
                        { label: 'Rejected', data: Object.values(levelMap).map(l => l.Rejection), backgroundColor: '#D9534F' }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } }
            });

            // Council Bar
            if (councilChart) councilChart.destroy();
            councilChart = new Chart(document.getElementById('councilChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(councilMap),
                    datasets: [
                        { label: 'Pending', data: Object.values(councilMap).map(c => c.Pending), backgroundColor: '#F0AD4E' },
                        { label: 'Accepted', data: Object.values(councilMap).map(c => c.Acceptance), backgroundColor: '#5CB85C' },
                        { label: 'Backup', data: Object.values(councilMap).map(c => c.B), backgroundColor: '#5BC0DE' },
                        { label: 'Rejected', data: Object.values(councilMap).map(c => c.Rejection), backgroundColor: '#D9534F' }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } }
            });

            // Event Type Pie
            if (eventTypeChart) eventTypeChart.destroy();
            eventTypeChart = new Chart(document.getElementById('eventTypeChart'), {
                type: 'pie',
                data: {
                    labels: Object.keys(eventTypeMap),
                    datasets: [{
                        data: Object.values(eventTypeMap),
                        backgroundColor: ['#F0AD4E', '#5CB85C', '#5BC0DE', '#D9534F']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });
        }

        function hideLoader() {
            const loader = document.getElementById('global-loader');
            if (loader) {
                loader.classList.add('hidden');
                setTimeout(() => loader.style.display = 'none', 500);
            }
        }

        function exportCSV() {
            if (!allApplicants.length) return alert('No data to export.');
            const headers = ['ID', 'Name', 'Email', 'Phone', 'College', 'Level', 'Council', 'Rating'];
            const rows = allApplicants.map(a => [a.id, a.name, a.email, a.phone, a.college, a.level, a.council, a.rating || 'Pending']);
            const csv = [headers.join(','), ...rows.map(r => r.join(','))].join('\n');
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `ThreeDOS_Statistics_${new Date().toLocaleDateString()}.csv`;
            a.click();
        }

        document.addEventListener('DOMContentLoaded', loadStatistics);
    </script>
</body>

</html>
