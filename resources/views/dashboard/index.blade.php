<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreeDOS Applicant Management System - Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('img/ThreeDOS.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* =========================
            GLOBAL RESET
        ========================= */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* =========================
            THREEDOS BRAND PALETTE
        ========================= */
        :root {
            --primary: #7F4797;
            --primary-dark: #6A3A82;
            --primary-light: #9A6BB2;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --bg-dark: #19191C;
            --bg-card: #252429;
            --dark-lighter: #343338;
            --text-main: #F4F4F4;
            --text-secondary: #B0B0B0;
            --border: #565657;
            --shadow: rgba(0, 0, 0, 0.35);
            --sidebar-width: 280px;
        }

        /* =========================
            BODY & GLOBAL
        ========================= */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-card) 100%);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* =========================
            DASHBOARD LAYOUT (FIXED)
        ========================= */
        .dashboard-container {
            display: grid;
            grid-template-columns: var(--sidebar-width) 1fr;
            min-height: 100vh;
            width: 100%;
            width: 100%;
        }
        .applicants-table-container { overflow-x: auto; -webkit-overflow-scrolling: touch; }

        /* =========================
            SIDEBAR (MODERN FIXED STICKY)
        ========================= */
        .sidebar {
            background: linear-gradient(180deg, #252429 0%, #19191C 100%);
            border-right: 1px solid var(--border);
            padding: 2rem 1.25rem;
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
            gap: 0.85rem;
            padding-bottom: 0.5rem;
        }

        .logo-container {
            width: 76px;
            height: 76px;
            border-radius: 18px;
            overflow: hidden;
            border: 2px solid rgba(127, 71, 151, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4), 0 0 15px rgba(127, 71, 151, 0.2);
            transition: transform 0.3s ease;
        }

        .logo-container:hover {
            transform: scale(1.04);
            border-color: var(--primary-light);
        }

        .logo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .org-name {
            font-size: 1.45rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #9A6BB2, #7F4797);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .org-subtitle {
            font-size: 0.72rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-top: 3px;
            font-weight: 600;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem 1.15rem;
            border-radius: 12px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 0.92rem;
        }

        .nav-item:hover {
            background: rgba(127, 71, 151, 0.15);
            color: var(--primary-light);
            transform: translateX(4px);
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(127, 71, 151, 0.35) 0%, rgba(106, 58, 130, 0.2) 100%);
            color: #ffffff;
            font-weight: 600;
            box-shadow: inset 0 0 0 1px rgba(154, 107, 178, 0.35), 0 4px 12px rgba(127, 71, 151, 0.25);
            border-left: 4px solid var(--primary);
        }

        .nav-item .icon,
        .nav-item i {
            font-size: 1.15rem;
            width: 20px;
            text-align: center;
        }

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
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-value {
            font-weight: 700;
            color: var(--text);
        }

        .close-sidebar-btn {
            display: none;
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-secondary);
            font-size: 1.2rem;
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            z-index: 1002;
        }

        .close-sidebar-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.4);
        }

        /* =========================
            MAIN CONTENT (FIXED MIN-WIDTH)
        ========================= */
        .main-content {
            padding: 2rem;
            width: 100%;
            min-width: 0;
            /* Ensures grid item doesn't overflow */
            overflow-x: hidden;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .header h1 {
            font-size: 1.85rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
        }

        /* =========================
            BUTTONS SYSTEM & SHAPES
        ========================= */
        button, .btn, .btn-primary, .btn-refresh, .btn-clear-filters, .btn-logout, .btn-page, .btn-action-view, .btn-action-edit {
            padding: 0.65rem 1.25rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            text-decoration: none;
            line-height: 1.2;
            outline: none;
        }

        button:active, .btn:active, .btn-primary:active, .btn-action-view:active, .btn-action-edit:active {
            transform: scale(0.97);
        }

        /* Primary Action Button */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #ffffff;
            border: 1px solid rgba(154, 107, 178, 0.35);
            box-shadow: 0 4px 14px rgba(127, 71, 151, 0.35);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(127, 71, 151, 0.5);
            transform: translateY(-2px);
        }

        /* Refresh Button */
        .btn-refresh {
            background: rgba(127, 71, 151, 0.15);
            border: 1px solid var(--primary-light);
            color: var(--primary-light);
        }

        .btn-refresh:hover {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(127, 71, 151, 0.4);
            transform: translateY(-2px);
        }

        /* Clear Filters Button */
        .btn-clear-filters {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .btn-clear-filters:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        /* Logout Button */
        .btn-logout {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
        }

        .btn-logout:hover {
            background: var(--danger);
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(239, 68, 68, 0.4);
            border-color: var(--danger);
            transform: translateY(-2px);
        }

        /* Action Buttons in Table */
        .btn-action-view {
            background: rgba(127, 71, 151, 0.18);
            border: 1px solid rgba(154, 107, 178, 0.4);
            color: #d8b4fe;
            padding: 0.45rem 0.85rem;
            font-size: 0.8rem;
            border-radius: 9px;
        }

        .btn-action-view:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary-light);
            box-shadow: 0 4px 12px rgba(127, 71, 151, 0.4);
            transform: translateY(-2px);
        }

        .btn-action-edit {
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.35);
            color: #93c5fd;
            padding: 0.45rem 0.85rem;
            font-size: 0.8rem;
            border-radius: 9px;
        }

        .btn-action-edit:hover {
            background: var(--info);
            color: #ffffff;
            border-color: var(--info);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            transform: translateY(-2px);
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

        /* ---------- NICE WELCOME DIV ---------- */
        #welcome-message {
            background: linear-gradient(145deg, rgba(127, 71, 151, 0.18) 0%, rgba(106, 58, 130, 0.08) 100%);
            border: 1px solid rgba(127, 71, 151, 0.35);
            border-radius: 24px;
            padding: 1.25rem 1.75rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.3);
            transition: all 0.2s ease;
        }

        .welcome-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(145deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
            flex-shrink: 0;
        }

        .welcome-info {
            flex: 1;
        }

        .welcome-greeting {
            font-size: 0.85rem;
            color: var(--text-secondary);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 0.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .welcome-name {
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 0.4rem;
            color: white;
            letter-spacing: -0.01em;
        }

        .welcome-details {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            align-items: center;
            font-size: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--text-secondary);
            background: rgba(255, 255, 255, 0.03);
            padding: 0.3rem 0.9rem;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .detail-item i {
            font-style: normal;
            font-size: 1.2rem;
        }

        .detail-item span {
            font-weight: 500;
            color: #e0e0e0;
        }

        /* filter & table layout */
        .filters-section {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.85rem;
            margin-bottom: 2rem;
            background: rgba(37, 36, 41, 0.6);
            padding: 1.15rem 1.25rem;
            border-radius: 16px;
            border: 1px solid var(--border);
            backdrop-filter: blur(8px);
        }

        .search-box {
            display: flex;
            align-items: center;
            background: var(--bg-dark);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.65rem 1rem;
            flex: 1;
            min-width: 250px;
            transition: all 0.25s ease;
        }

        .search-box:focus-within {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(127, 71, 151, 0.25);
        }

        .search-box i {
            color: var(--text-secondary);
        }

        .search-box input {
            background: transparent;
            border: none;
            outline: none;
            color: var(--text-main);
            width: 100%;
            margin-left: 0.6rem;
            font-size: 0.92rem;
        }

        .filter-select {
            background: var(--bg-dark);
            border: 1px solid var(--border);
            color: var(--text-main);
            border-radius: 12px;
            padding: 0.65rem 1rem;
            cursor: pointer;
            outline: none;
            min-width: 145px;
            font-size: 0.9rem;
            transition: all 0.25s ease;
        }

        .filter-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(127, 71, 151, 0.25);
        }

        .applicants-table-container {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 2rem;
            border-radius: 1rem;
            border: 1px solid var(--border);
            background: var(--bg-card);
        }

        .applicants-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        .applicants-table thead {
            background: var(--bg-card);
        }

        .applicants-table th,
        .applicants-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
        }

        .applicants-table th {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
        }

        .applicants-table tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .rating-badge {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .rating-pending {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .rating-acceptance {
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .rating-b {
            background: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .rating-rejection {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* =========================
            CUSTOM PAGINATION STYLING
        ========================= */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 1.5rem;
        }

        .custom-pagination .pagination-list {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
        }

        .custom-pagination .page-item {
            display: inline-flex;
        }

        .custom-pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.55rem 0.95rem;
            border-radius: 10px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-main);
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .custom-pagination .page-link:hover {
            background: rgba(127, 71, 151, 0.2);
            border-color: var(--primary-light);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(127, 71, 151, 0.25);
        }

        .custom-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-color: var(--primary-light);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(127, 71, 151, 0.4);
        }

        .custom-pagination .page-item.disabled .page-link {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* =========================
            RESPONSIVE & MOBILE (FIXED SIDEBAR)
        ========================= */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-main);
            font-size: 1.5rem;
            cursor: pointer;
            margin-right: 0.5rem;
            padding: 0.5rem;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1500;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

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
                z-index: 2000;
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
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .filters-section {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box,
            .filter-select,
            .btn-clear-filters {
                width: 100%;
            }

            #welcome-message {
                flex-direction: column;
                align-items: flex-start;
                padding: 1.25rem;
            }

            .welcome-avatar {
                width: 60px;
                height: 60px;
                font-size: 1.7rem;
            }

            .welcome-name {
                font-size: 1.5rem;
                font-weight: 700;
                color: #fff;
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
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="dashboard-container">
        @include('layouts.partials.sidebar')

        <main class="main-content">
            @php
                $currentUser = session('user');
                $userName = $currentUser ? ($currentUser->username ?? $currentUser['username'] ?? 'User') : 'User';
                $userCouncil = $currentUser ? ($currentUser->council ?? $currentUser['council'] ?? 'Academic Council') : 'Academic Council';
                if (empty($userCouncil) || $userCouncil === 'null') {
                    $userCouncil = 'Academic Council';
                }
                $userRole = $currentUser ? ($currentUser->role ?? $currentUser['role'] ?? 'Member') : 'Member';

                $userInitials = 'US';
                if ($userName && is_string($userName)) {
                    $nameParts = explode(' ', trim($userName));
                    if (count($nameParts) > 1) {
                        $userInitials = strtoupper(mb_substr($nameParts[0], 0, 1) . mb_substr(end($nameParts), 0, 1));
                    } else {
                        $userInitials = strtoupper(mb_substr($userName, 0, 2));
                    }
                }
            @endphp
            <!-- ===== NICE WELCOME DIV with Name, Council, Role ===== -->
            <div id="welcome-message">
                <div class="welcome-avatar" id="welcome-avatar">{{ $userInitials }}</div>
                <div class="welcome-info">
                    <div class="welcome-greeting">
                        <span><i class="fas fa-hand-sparkles"></i> WELCOME BACK</span>
                    </div>
                    <div class="welcome-name" id="welcome-name">{{ $userName }}</div>
                    <div class="welcome-details">
                        <div class="detail-item">
                            <i class="fas fa-university"></i> <span id="welcome-council">{{ $userCouncil }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-user-tag"></i> <span id="welcome-role">{{ $userRole }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <header class="header">
                <div style="display:flex; align-items:center;">
                    <button class="menu-toggle" aria-label="Toggle sidebar"><i class="fas fa-bars"></i></button>
                    <h1><i class="fas fa-users-cog"></i> Applicant Management</h1>
                </div>
                <div class="header-actions">
                    <button class="btn-refresh" onclick="loadApplicants()"><i class="fas fa-sync"></i> Refresh</button>
                    {{-- <button class="btn-logout" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</button> --}}
                </div>
            </header>

            <form method="GET" action="{{ route('dashboard') }}" class="filters-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email...">
                </div>
                <select name="level" class="filter-select">
                    <option value="">All Levels</option>
                    <option value="Level 1" {{ request('level')=='Level 1' ? 'selected' : '' }}>Level 1</option>
                    <option value="Level 2" {{ request('level')=='Level 2' ? 'selected' : '' }}>Level 2</option>
                    <option value="Level 3" {{ request('level')=='Level 3' ? 'selected' : '' }}>Level 3</option>
                    <option value="Level 4" {{ request('level')=='Level 4' ? 'selected' : '' }}>Level 4</option>
                </select>
                <select name="rating" class="filter-select">
                    <option value="">All Ratings</option>
                    <option value="Pending" {{ request('rating')=='Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Acceptance" {{ request('rating')=='Acceptance' ? 'selected' : '' }}>Acceptance</option>
                    <option value="B" {{ request('rating')=='B' ? 'selected' : '' }}>B (Backup)</option>
                    <option value="Rejection" {{ request('rating')=='Rejection' ? 'selected' : '' }}>Rejection</option>
                </select>
                <select name="event_type" class="filter-select">
                    <option value="">All Event Types</option>
                    <option value="Online" {{ request('event_type')=='Online' ? 'selected' : '' }}>Online</option>
                    <option value="Offline" {{ request('event_type')=='Offline' ? 'selected' : '' }}>Offline</option>
                </select>
                <button type="submit" class="btn-primary"><i class="fas fa-filter"></i> Filter</button>
                <a href="{{ route('dashboard') }}" class="btn-clear-filters"><i class="fas fa-eraser"></i> Clear</a>
            </form>

            <div class="applicants-table-container">
                <table class="applicants-table" id="applicants-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> Name</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-phone"></i> Phone</th>
                            <th><i class="fas fa-university"></i> College</th>
                            <th><i class="fas fa-layer-group"></i> Level</th>
                            <th><i class="fas fa-sitemap"></i> Preference</th>
                            <th><i class="fas fa-star"></i> Rating</th>
                            <th><i class="fas fa-hands-helping"></i> Ushered By</th>
                            <th><i class="fas fa-calendar-check"></i> Event Type</th>
                            <th><i class="fas fa-user-check"></i> Interviewer</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody id="applicants-tbody">
                        @forelse($applicants as $app)
                        <tr>
                            <td>{{ $app->name }}</td>
                            <td>{{ $app->email }}</td>
                            <td>{{ $app->phone }}</td>
                            <td>{{ $app->college }}</td>
                            <td>{{ $app->level }}</td>
                            <td>{{ $app->council ?? '' }}</td>
                            <td><span class="rating-badge rating-{{ strtolower($app->rating ?? 'pending') }}">{{ $app->rating ?? 'Pending' }}</span></td>
                            <td>{{ $app->ushered_by ?? 'NA' }}</td>
                            <td>{{ $app->event_type ?? 'Interview' }}</td>
                            <td>{{ $app->interviewed_by ?? 'NA' }}</td>
                            <td>
                                <div style="display:flex;gap:0.4rem;align-items:center;">
                                    <a href="{{ route('applicants.show', $app->id) }}" class="btn-action-view"><i class="fas fa-eye"></i> View</a>
                                    {{-- <a href="{{ route('applicants.show', $app->id) }}" class="btn-action-edit"><i class="fas fa-edit"></i> Edit</a> --}}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="11" style="text-align:center;padding:2rem"><i class="fas fa-inbox"></i> No applicants found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $applicants->links('vendor.pagination.custom') }}
            </div>
        </main>
    </div>

    <script src="{{ asset('js/sidebar.js') }}"></script>
    <!-- Dashboard data now server-side via Blade, no API fetch -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Sidebar handled by public/js/sidebar.js (toggle/overlay/ESC/close) — no duplicate here

            // ===============================
            // Welcome Message
            // ===============================
            function populateWelcomeMessage() {
                const nameEl = document.getElementById('welcome-name');
                const councilEl = document.getElementById('welcome-council');
                const roleEl = document.getElementById('welcome-role');
                const avatarEl = document.getElementById('welcome-avatar');

                let userName = (nameEl && nameEl.textContent.trim()) ? nameEl.textContent.trim() : (localStorage.getItem('user_name') || 'User');
                let userCouncil = (councilEl && councilEl.textContent.trim()) ? councilEl.textContent.trim() : (localStorage.getItem('user_council') || 'Academic Council');
                let userRole = (roleEl && roleEl.textContent.trim()) ? roleEl.textContent.trim() : (localStorage.getItem('user_role') || 'Member');

                if (!userCouncil || userCouncil === "null") {
                    userCouncil = "Academic Council";
                }

                if (nameEl) nameEl.textContent = userName;
                if (councilEl) councilEl.textContent = userCouncil;
                if (roleEl) roleEl.textContent = userRole;

                // SPECIAL ACCESS: Logs for Backend Team
                if (userCouncil === 'Backend Development') {
                    const nav = document.querySelector('.nav-menu');
                    if (nav && !nav.querySelector('a[href="/logs"]')) {
                        const logsLink = document.createElement('a');
                        logsLink.href = '/logs';
                        logsLink.className = 'nav-item';
                        logsLink.innerHTML = '<i class="fas fa-file-alt icon"></i> <span>System Logs</span>';
                        nav.appendChild(logsLink);
                    }
                }
            }

            populateWelcomeMessage();
            setTimeout(() => {
                const loader = document.getElementById('global-loader');
                if (loader && !loader.classList.contains('hidden')) {
                    loader.classList.add('hidden');
                    setTimeout(() => loader.style.display = 'none', 500);
                }
            }, 1500);

            // ===============================================
            // REAL-TIME ASYNC SEARCH & FILTERS (NO SUBMIT BTN NEEDED)
            // ===============================================
            const filterForm = document.querySelector('.filters-section');
            const searchInput = filterForm ? filterForm.querySelector('input[name="search"]') : null;
            const filterSelects = filterForm ? filterForm.querySelectorAll('.filter-select') : [];
            const tbody = document.getElementById('applicants-tbody');
            const paginationContainer = document.querySelector('.pagination');

            let debounceTimer = null;

            function performAsyncFilter() {
                if (!filterForm) return;

                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                
                // Remove empty values for clean URL
                for (const [key, value] of Array.from(params.entries())) {
                    if (!value) params.delete(key);
                }

                const fetchUrl = filterForm.action + (params.toString() ? '?' + params.toString() : '');

                if (tbody) {
                    tbody.style.opacity = '0.5';
                    tbody.style.transition = 'opacity 0.2s ease';
                }

                fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const newTbody = doc.getElementById('applicants-tbody');
                    const newPagination = doc.querySelector('.pagination');

                    if (newTbody && tbody) {
                        tbody.innerHTML = newTbody.innerHTML;
                        tbody.style.opacity = '1';
                    }

                    if (newPagination && paginationContainer) {
                        paginationContainer.innerHTML = newPagination.innerHTML;
                    }

                    window.history.pushState(null, '', fetchUrl);
                })
                .catch(err => {
                    console.error('[ThreeDOS] Async search error:', err);
                    if (tbody) tbody.style.opacity = '1';
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(performAsyncFilter, 250);
                });
            }

            filterSelects.forEach(select => {
                select.addEventListener('change', performAsyncFilter);
            });

            if (filterForm) {
                filterForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    performAsyncFilter();
                });
            }
        });

        // ===============================
        // Logout
        // ===============================
        function logout() {
            if (confirm("Are you sure you want to logout?")) {
                localStorage.removeItem('usher_token');
                localStorage.removeItem('user_name');
                localStorage.removeItem('user_council');
                localStorage.removeItem('user_role');
                window.location.href = '/register';
            }
        }
    </script>

</body>

</html>









