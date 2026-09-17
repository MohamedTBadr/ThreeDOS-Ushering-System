<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ThreeDOS Ushering System')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/ThreeDOS.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        :root {
            --primary: #7F4797;
            --primary-dark: #6A3A82;
            --primary-light: #9A6BB2;
            --bg-dark: #19191C;
            --bg-card: #252429;
            --text-main: #F4F4F4;
            --text-secondary: #B0B0B0;
            --border: #565657;
            --sidebar-width: 280px;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:linear-gradient(135deg,var(--bg-dark) 0%,var(--bg-card) 100%); color:var(--text-main); min-height:100vh; }
        .dashboard-container { display:grid; grid-template-columns:var(--sidebar-width) 1fr; min-height:100vh; }
        .sidebar { background:linear-gradient(180deg,var(--bg-card) 0%,var(--bg-dark) 100%); border-right:1px solid var(--border); padding:2rem 1.5rem; display:flex; flex-direction:column; gap:2rem; position:sticky; top:0; height:100vh; overflow-y:auto; }
        @media (max-width:768px){ .dashboard-container{grid-template-columns:1fr} .sidebar{display:none} .sidebar.mobile-open{display:flex; position:fixed; inset:0; z-index:999} }
    </style>
</head>
<body>
    <div class="dashboard-container">
        @hasSection('sidebar')
            @yield('sidebar')
        @else
            @include('layouts.partials.sidebar')
        @endif
        <main class="main-content">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
