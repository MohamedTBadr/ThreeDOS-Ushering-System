<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreeDOS - Logs Viewer</title>
    <link rel="icon" type="image/png" href="{{ asset('img/ThreeDOS.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* =========================
            GLOBAL VARIABLES (From Dashboard)
        ========================= */
        :root {
            --primary: #7F4797;
            --primary-dark: #6A3A82;
            --primary-light: #9A6BB2;
            --success: #10b981;
            --success-bg: rgba(16, 185, 129, 0.1);
            --danger: #ef4444;
            --danger-bg: rgba(239, 68, 68, 0.1);
            --warning: #f59e0b;
            --warning-bg: rgba(245, 158, 11, 0.1);
            --info: #3b82f6;
            --info-bg: rgba(59, 130, 246, 0.1);
            --debug: #8b5cf6;
            --debug-bg: rgba(139, 92, 246, 0.1);
            --bg-dark: #19191C;
            --bg-card: #252429;
            --dark-lighter: #343338;
            --text-main: #F4F4F4;
            --text-secondary: #B0B0B0;
            --text-muted: #6b6b76;
            --border: #565657;
            --border-light: #36363f;
            --shadow: rgba(0, 0, 0, 0.35);
            --sidebar-width: 280px;
            --glow: rgba(127, 71, 151, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-card) 100%);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* =========================
            DASHBOARD LAYOUT
        ========================= */
        .dashboard-container {
            display: grid;
            grid-template-columns: var(--sidebar-width) 1fr;
            min-height: 100vh;
            width: 100%;
        }

        /* =========================
            SIDEBAR (Verified Dashboard Styling)
        ========================= */
        .sidebar {
            background: linear-gradient(180deg, var(--bg-card) 0%, var(--bg-dark) 100%);
            border-right: 1px solid var(--border);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .header-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1rem;
        }

        .logo-container {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .logo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .org-name {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .org-subtitle {
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
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
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-item:hover {
            background: rgba(127, 71, 151, 0.15);
            color: var(--primary-light);
            transform: translateX(4px);
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(127, 71, 151, 0.25), rgba(106, 58, 130, 0.15));
            color: var(--primary-light);
            border-left: 3px solid var(--primary);
        }

        .nav-item .icon {
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
        }

        /* Sidebar Stats (Dashboard Style) */
        .stats-summary {
            background: rgba(127, 71, 151, 0.08);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-top: auto;
        }

        .stats-summary h3 {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-value {
            font-weight: 700;
            color: var(--text-main);
        }

        .close-sidebar-btn {
            display: none;
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
            z-index: 1002;
        }

        /* =========================
            MAIN CONTENT
        ========================= */
        .main-content {
            padding: 2rem;
            width: 100%;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            background: var(--bg-card);
            padding: 1.5rem;
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 4px 15px var(--glow);
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--text-main), var(--primary-light));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-main);
            font-size: 1.5rem;
            cursor: pointer;
            margin-right: 0.5rem;
        }

        .file-info {
            background: rgba(159, 122, 234, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 30px;
            border: 1px solid var(--primary);
            color: var(--primary);
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Logs Container */
        .logs-container {
            display: flex;
            gap: 2rem;
            height: calc(100vh - 200px);
        }

        /* Files List */
        .files-list {
            width: 300px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow-y: auto;
            padding: 1.5rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
        }

        .files-list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .files-list-header h3 {
            color: var(--text-secondary);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .file-count {
            background: var(--bg-dark);
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .search-bar {
            margin-bottom: 1rem;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            background: var(--bg-dark);
            border: 1px solid var(--border);
            border-radius: 30px;
            color: var(--text-main);
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--glow);
        }

        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .file-item {
            padding: 1rem;
            cursor: pointer;
            border-radius: 12px;
            color: var(--text-secondary);
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            border: 1px solid transparent;
        }

        .file-item i {
            color: var(--primary);
            font-size: 1rem;
            opacity: 0.7;
        }

        .file-item:hover {
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-main);
            border-color: var(--border-light);
            transform: translateX(5px);
        }

        .file-item.active {
            background: linear-gradient(135deg, rgba(159, 122, 234, 0.15), rgba(128, 90, 213, 0.05));
            color: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(159, 122, 234, 0.2);
        }

        .file-item.active i {
            opacity: 1;
        }

        .file-name {
            flex: 1;
            font-weight: 500;
        }

        /* Log Viewer */
        .log-viewer {
            flex: 1;
            background: #0a0a0c;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.5rem;
            overflow-y: auto;
            font-family: 'Fira Code', 'Courier New', Courier, monospace;
            font-size: 0.9rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .log-entry {
            padding: 0.8rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            transition: background 0.2s ease;
            border-radius: 8px;
        }

        .log-entry:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .log-time {
            color: var(--text-muted);
            min-width: 160px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .log-time i {
            font-size: 0.7rem;
            color: var(--primary);
        }

        .log-level {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            min-width: 80px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .log-level.INFO {
            background: var(--info-bg);
            color: var(--info);
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .log-level.ERROR {
            background: var(--danger-bg);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .log-level.WARNING {
            background: var(--warning-bg);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .log-level.SUCCESS {
            background: var(--success-bg);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .log-level.DEBUG {
            background: var(--debug-bg);
            color: var(--debug);
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        .log-message {
            color: #e0e0e0;
            word-break: break-all;
            flex: 1;
            line-height: 1.5;
        }

        .log-context {
            margin-top: 0.8rem;
            background: rgba(0, 0, 0, 0.4);
            padding: 0.8rem;
            border-radius: 8px;
            font-size: 0.8rem;
            color: var(--text-secondary);
            white-space: pre-wrap;
            border-left: 2px solid var(--primary);
            font-family: 'Fira Code', monospace;
        }
		
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
            
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 900;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                width: 280px;
                transform: translateX(-100%);
                box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .menu-toggle {
                display: block;
            }

            .close-sidebar-btn {
                display: block;
            }

            .logs-container {
                flex-direction: column;
                height: auto;
            }

            .files-list {
                width: 100%;
                height: 300px;
            }

            .log-viewer {
                height: 500px;
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
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="dashboard-container">
        <!-- SIDEBAR (Matches Dashboard with Stats) -->
        @include('layouts.partials.sidebar')

        <!-- MAIN CONTENT (User Custom Logs Layout) -->
        <main class="main-content">
            <div class="header">
                <div class="header-left">
                    <button class="menu-toggle" aria-label="Toggle sidebar"><i class="fas fa-bars"></i></button>
                    <div class="header-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h1>System Logs</h1>
                </div>
                <div class="file-info" id="current-file-name">
                    <i class="fas fa-file-alt"></i>
                    <span>Select a file</span>
                </div>
            </div>

            <div class="logs-container">
                <div class="files-list">
                    <div class="files-list-header">
                        <h3><i class="fas fa-folder-open" style="margin-right: 0.5rem;"></i>Log Files</h3>
                        <span class="file-count" id="file-count">0</span>
                    </div>
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" id="file-search" placeholder="Search files..." onkeyup="filterFiles()">
                    </div>
                    <div id="files-list">
                        <div style="text-align:center; padding: 2rem; color: var(--text-muted);" id="files-loading">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p style="margin-top: 1rem;">Loading files...</p>
                        </div>
                    </div>
                </div>

                <div class="log-viewer" id="log-viewer">
                    <div style="text-align:center; padding: 3rem; color: var(--text-muted);">
                        <i class="fas fa-file-alt fa-3x" style="margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p>Select a log file to view content</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
        // ==========================================
        // LOGS LOGIC
        // ==========================================
        const API_LOGS = '..//api/logs-data';
        const token = localStorage.getItem('usher_token');
        const userCouncil = localStorage.getItem('user_council');

        // Check Auth
        if (!token) {
            window.location.href = '/register';
        }
        if (userCouncil !== 'Backend Development') {
            alert('Access Denied. Only Backend Development council members can view logs.');
            window.location.href = 'dashboard.html';
        }

        let currentFiles = [];
        let currentLogs = [];

        async function fetchLogFiles() {
            try {
                const response = await fetch(`${API_LOGS}?list=1`, {
                    headers: { 'X-Token': token }
                });
                const result = await response.json();

                if (result.status === 'success') {
                    currentFiles = result.data;
                    renderFilesList(result.data);
                    updateStats(result.data);
                } else {
                    document.getElementById('files-list').innerHTML = `<p style="color:var(--danger); padding:1rem;">Error: ${result.message}</p>`;
                }
            } catch (error) {
                console.error('Error fetching files:', error);
                document.getElementById('files-loading').innerHTML = 'Failed to load files';
            }
        }

        function renderFilesList(files) {
            const container = document.getElementById('files-list');
            document.getElementById('file-count').textContent = files.length;

            if (files.length === 0) {
                container.innerHTML = '<div style="padding:2rem; text-align:center; color:var(--text-muted);"><i class="fas fa-folder-open fa-2x" style="margin-bottom:1rem;"></i><p>No logs found.</p></div>';
                return;
            }

            container.innerHTML = files.map(file => `
                <div class="file-item" onclick="loadLogFile('${file}', this)">
                    <i class="fas fa-file-code"></i>
                    <span class="file-name">${file}</span>
                    <i class="fas fa-chevron-right" style="font-size:0.8rem; opacity:0.5;"></i>
                </div>
            `).join('');

            // Auto-load first file
            if (files.length > 0) {
                const firstItem = container.querySelector('.file-item');
                loadLogFile(files[0], firstItem);
            }
        }

        async function loadLogFile(filename, element) {
            // Update active state
            document.querySelectorAll('.file-item').forEach(el => el.classList.remove('active'));
            if (element) element.classList.add('active');

            document.getElementById('current-file-name').innerHTML = `<i class="fas fa-file-alt"></i><span>${filename}</span>`;

            const viewer = document.getElementById('log-viewer');
            viewer.innerHTML = '<div style="text-align:center; padding:3rem;"><i class="fas fa-spinner fa-spin fa-2x"></i><p style="margin-top:1rem;">Loading content...</p></div>';

            try {
                const response = await fetch(`${API_LOGS}?view=1&file=${filename}`, {
                    headers: { 'X-Token': token }
                });
                const result = await response.json();

                if (result.status === 'success') {
                    currentLogs = result.data;
                    renderLogContent(result.data);
                    updateDetailedStats(result.data);
                } else {
                    viewer.innerHTML = `<p style="color:var(--danger); padding:2rem;">Error: ${result.message}</p>`;
                }
            } catch (error) {
                viewer.innerHTML = `<p style="color:var(--danger); padding:2rem;">Error loading file.</p>`;
            }
        }

        function renderLogContent(logs) {
            const container = document.getElementById('log-viewer');
            container.innerHTML = '';

            if (logs.length === 0) {
                container.innerHTML = '<div style="padding:2rem; text-align:center; color:var(--text-muted);"><i class="fas fa-file-excel fa-2x" style="margin-bottom:1rem;"></i><p>File is empty.</p></div>';
                return;
            }

            logs.forEach(log => {
                const entryDiv = document.createElement('div');
                entryDiv.className = 'log-entry';

                const time = log.time || 'Unknown Time';
                const level = log.level || 'INFO';
                const message = log.message || JSON.stringify(log);
                const ip= log.ip ;

                let contextHtml = '';
                if (log.context && (Array.isArray(log.context) || typeof log.context === 'object') && Object.keys(log.context).length > 0) {
                    contextHtml = `<div class="log-context"><i class="fas fa-code" style="margin-right:0.5rem;"></i>${JSON.stringify(log.context, null, 2)}</div>`;
                }

entryDiv.innerHTML = `
    <div class="log-time" style="display:flex; align-items:center; font-size:13px; color:#9ca3af; margin-bottom:6px; letter-spacing:0.3px; font-family:'Inter',system-ui,-apple-system,sans-serif;">
        <i class="far fa-clock" style="margin-right:6px; font-size:12px; color:#6b7280;"></i>
        ${time}
    </div>
    <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
        <div style="font-size:13px; color:#d1d5db; background:#1f2937; padding:4px 10px; border-radius:20px; font-family:'SF Mono',Monaco,monospace; letter-spacing:0.2px; border:1px solid #374151;">
            <span style="font-weight:500; color:#9ca3af; margin-right:4px;">??</span> ${ip}
        </div>
<div class="log-level" style="
    font-weight: 700;
    font-size: 12px;
    padding: 4px 12px 4px 10px;
    border-radius: 100px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: ${level.toLowerCase() === 'error' 
        ? '#7f1d1d'  /* deep red - highest severity */
        : level.toLowerCase() === 'critical' 
        ? '#7f2e1d'  /* deep orange-red - second highest */
        : level.toLowerCase() === 'warn'
        ? '#431407'  /* warm orange-brown */
        : '#0a2e1a'};  /* info/other - green */
    
    color: ${level.toLowerCase() === 'error' ? '#ffffff'
           : level.toLowerCase() === 'critical' ? '#ffffff'
           : level.toLowerCase() === 'warn' ? '#ffedd5'
           : '#dcfce7'};
    
    border: 1px solid ${level.toLowerCase() === 'error'
        ? '#ef4444'  /* bright red border */
        : level.toLowerCase() === 'critical'
        ? '#f97316'  /* bright orange border */
        : level.toLowerCase() === 'warn'
        ? '#f97316'  /* orange border */
        : '#14532d'};
    
    box-shadow: ${level.toLowerCase() === 'error'
        ? '0 0 0 2px rgba(239, 68, 68, 0.4), 0 2px 4px rgba(0, 0, 0, 0.4)'  /* strong red glow */
        : level.toLowerCase() === 'critical'
        ? '0 0 0 2px rgba(249, 115, 22, 0.4), 0 2px 4px rgba(0, 0, 0, 0.3)'  /* orange glow */
        : level.toLowerCase() === 'warn'
        ? '0 2px 4px rgba(0, 0, 0, 0.2)'
        : '0 2px 4px rgba(0, 0, 0, 0.1)'};
    
    text-shadow: ${level.toLowerCase() === 'error' || level.toLowerCase() === 'critical'
        ? '0 1px 2px rgba(0, 0, 0, 0.5)'
        : 'none'};
    
    display: inline-flex;
    align-items: center;
    gap: 6px;
    position: relative;
    
    /* Different outline colors for error and critical */
    ${level.toLowerCase() === 'error' ? 
        'outline: 2px solid rgba(239, 68, 68, 0.3);' : 
      level.toLowerCase() === 'critical' ? 
        'outline: 2px solid rgba(249, 115, 22, 0.3);' : ''}
    ">
    
    ${level.toLowerCase() === 'error' ? '?? ' : 
      level.toLowerCase() === 'critical' ? '?? ' : 
      level.toLowerCase() === 'warn' ? '?? ' : ''}${level.toUpperCase()}
    
    ${level.toLowerCase() === 'error' ? 
        '<span style="margin-left: 4px; font-size: 14px;">??</span>' : 
      level.toLowerCase() === 'critical' ? 
        '<span style="margin-left: 4px; font-size: 14px;">?</span>' : ''}
</div>


    </div>
    <div style="flex:1;">
        <div class="log-message" style="font-size:14px; color:#e5e7eb; line-height:1.6; margin-bottom:10px; padding:10px 12px; background:#111827; border-radius:8px; border-left:3px solid ${level === 'error' ? '#ef4444' : level === 'warn' ? '#f97316' : '#10b981'}; font-family:'Inter',system-ui,-apple-system,sans-serif; border:1px solid #1f2937; border-left-width:3px;">
            <span style="font-weight:500; color:#9ca3af; margin-right:6px;">??</span> ${message}
        </div>
        ${contextHtml ? `<div style="margin-top:8px; padding:8px 12px; background:#111827; border-radius:6px; font-size:13px; color:#d1d5db; border:1px solid #1f2937;">
            <span style="font-weight:500; color:#9ca3af; margin-right:6px;">??</span> ${contextHtml}
        </div>` : ''}
    </div>
`;

                container.appendChild(entryDiv);
            });
        }

        function filterFiles() {
            const searchTerm = document.getElementById('file-search').value.toLowerCase();
            const filtered = currentFiles.filter(file =>
                file.toLowerCase().includes(searchTerm)
            );

            const container = document.getElementById('files-list');
            container.innerHTML = filtered.map(file => `
                <div class="file-item" onclick="loadLogFile('${file}', this)">
                    <i class="fas fa-file-code"></i>
                    <span class="file-name">${file}</span>
                    <i class="fas fa-chevron-right" style="font-size:0.8rem; opacity:0.5;"></i>
                </div>
            `).join('');
        }

        function updateStats(files) {
            document.getElementById('total-logs').textContent = files.length;
        }

        function updateDetailedStats(logs) {
            const errorCount = logs.filter(log => log.level === 'ERROR').length;
            document.getElementById('error-count').textContent = errorCount;
        }

        // Initialize
        fetchLogFiles();
    </script>
</body>

</html>








