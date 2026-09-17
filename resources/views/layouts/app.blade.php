<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ThreeDOS Ushering System')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/ThreeDOS.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        * { scrollbar-width: thin; scrollbar-color: #7F4797 #19191C; }
        *::-webkit-scrollbar { width: 8px; height: 8px; }
        *::-webkit-scrollbar-track { background: #19191C; border-radius: 8px; }
        *::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #7F4797, #6A3A82); border-radius: 8px; border: 2px solid #19191C; }
        *::-webkit-scrollbar-thumb:hover { background: #9A6BB2; }
        body { font-family:'Inter',sans-serif; background:linear-gradient(135deg,var(--bg-dark) 0%,var(--bg-card) 100%); color:var(--text-main); min-height:100vh; }
        .dashboard-container { display:grid; grid-template-columns:var(--sidebar-width) 1fr; min-height:100vh; }
        .sidebar { background:linear-gradient(180deg,var(--bg-card) 0%,var(--bg-dark) 100%); border-right:1px solid var(--border); padding:2rem 1.5rem; display:flex; flex-direction:column; gap:2rem; position:sticky; top:0; height:100vh; overflow-y:auto; z-index:100; }
        .sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:99; }
        .sidebar-overlay.active { display:block; }
        .menu-toggle { display:none; background:none; border:none; color:var(--text-main); font-size:1.4rem; cursor:pointer; padding:6px; }
        @media (max-width:1024px){ .dashboard-container{grid-template-columns:1fr} .sidebar{position:fixed; left:0; top:0; transform:translateX(-100%); transition:transform .3s ease; width:var(--sidebar-width); } .sidebar.active{transform:translateX(0);} .menu-toggle{display:inline-flex;} }
        .main-content { padding:2rem; min-width:0; overflow-x:hidden; }
    </style>
    @stack('styles')
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
    <script src="{{ asset('js/sidebar.js') }}"></script>
    @stack('scripts')
</body>
</html>
