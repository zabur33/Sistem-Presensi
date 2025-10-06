<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Life Media</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js'])
    @endif
</head>
<body>
<div class="main-container">
    <div class="sidebar">
        @include('admin.partials.sidebar')
    </div>
    <div class="content-area">
        <div class="header">
            <div class="header-left">
                <div class="header-logo">
                    <img src="{{ asset('images/logo2.png') }}" alt="Life Media Logo">
                </div>
            </div>
            <div class="header-icons" style="margin-left:auto; display:flex; align-items:center; gap:12px;">
                <a href="/admin/profile" aria-label="Kelola Profile" title="Kelola Profile" 
                   style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:50%;border:2px solid #ffffff;background:transparent;color:#ffffff;transition:background-color .2s, color .2s;">
                    <!-- Match user icon: head + shoulders only (no outer ring) -->
                    <svg fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24" width="20" height="20">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M5.5 21a7.5 7.5 0 0 1 13 0"/>
                    </svg>
                </a>
            </div>
        </div>
        <div class="dashboard-content">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
