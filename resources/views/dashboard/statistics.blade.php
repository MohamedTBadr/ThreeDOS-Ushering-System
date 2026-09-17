<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreeDOS Applicant Management System - Statistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .dashboard-container { display: grid; grid-template-columns: 280px 1fr; min-height: 100vh; width: 100%; transition: grid-template-columns .3s ease; }
        .dashboard-container.sidebar-closed { grid-template-columns: 0 1fr; }
        .main-content { padding: 2rem; min-width: 0; width: 100%; overflow-x: hidden; }

        .header { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:1.75rem; gap:1rem; }
        .header h1 { font-size:1.75rem; font-weight:700; color:var(--text-main); line-height:1.2; display:flex; align-items:center; gap:.6rem; }
        .header-left { display:flex; align-items:center; gap:.75rem; }
        .header-actions { display:flex; align-items:center; gap:.6rem; flex-wrap:wrap; }

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

        .menu-toggle { display:inline-flex; align-items:center; justify-content:center; width:42px; height:42px; border-radius:12px; background:rgba(127,71,151,.14); border:1px solid rgba(127,71,151,.35); color:var(--text-main); font-size:1.2rem; cursor:pointer; transition:all .2s; flex-shrink:0; }
        .menu-toggle:hover { background: var(--threedos-purple); color:#fff; transform:translateY(-1px); box-shadow:0 6px 18px rgba(127,71,151,.35); }
        .sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1500; backdrop-filter:blur(4px); }
        .sidebar-overlay.active{ display:block; }

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
            .dashboard-container{ grid-template-columns:1fr; }
            .chart-card { flex: 1 1 100%; }
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
            /* === Global Scrollbar (ThreeDOS theme) === */
        * { scrollbar-width: thin; scrollbar-color: #7F4797 #19191C; }
        *::-webkit-scrollbar { width: 8px; height: 8px; }
        *::-webkit-scrollbar-track { background: #19191C; border-radius: 8px; }
        *::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #7F4797, #6A3A82); border-radius: 8px; border: 2px solid #19191C; }
        *::-webkit-scrollbar-thumb:hover { background: #9A6BB2; }
        *::-webkit-scrollbar-corner { background: #19191C; }
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

        @include('layouts.partials.sidebar')

        <main class="main-content">
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" aria-label="Toggle sidebar"><i class="fas fa-bars"></i></button>
                    <h1><i class="fas fa-chart-line" style="color:var(--threedos-purple-light)"></i> Statistics Overview</h1>
                </div>
                <div class="header-actions">
                    <button class="btn-refresh" onclick="loadStatistics()"><i class="fas fa-sync"></i> Refresh</button>
                    <button class="btn-export" onclick="exportCSV()"><i class="fas fa-download"></i> Export CSV</button>
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
                            <th><i class="fas fa-layer-group"></i> Level</th>
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

    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>

        // ==========================================
        // STATISTICS LOGIC (UNCHANGED)
        // ==========================================
        const API_URL = '/api/registrations';
        const TOKEN = localStorage.getItem('usher_token');
        if (!TOKEN) window.location.href = '/register';

        let ratingChart, levelChart, councilChart, eventTypeChart;
        let allApplicants = @json($applicants);

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
                    if (res.status === 401) window.location.href = '/register';

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





