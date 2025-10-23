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
                   style="display:grid; place-items:center; width:40px; height:40px; border-radius:50%; border:2px solid #ffffff; background:transparent; color:#ffffff; padding:0; margin:0; text-decoration:none;">
                    <span style="display:block; font-size:20px; line-height:1; transform:translateY(1px);">👤</span>
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
