<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Life Media</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/js/app.js'])
    @endif
</head>
<body>
<div class="container">
    <div class="left">
        <img src="{{ asset('images/bg-login.jpg') }}" alt="Background Login">
    </div>
    <div class="right">
        <form class="login-box" method="POST" action="/login">
            <div class="logo">
                <!-- Logo SVG atau gambar -->
                <img src="{{ asset('images/logo.jpg') }}" alt="Life Media Logo">
            </div>
            <!-- <div class="desc">Jika belum punya akun hubungi Admin</div> -->
             <div class="desc">woyyyyyyyyyy</div>
            <div class="input-group">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>
                <input type="text" name="username" placeholder="Enter username..." required>
            </div>
            <div class="input-group">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input type="password" name="password" placeholder="Enter password..." required>
            </div>
            <button class="login-btn" type="submit">LOGIN</button>
        </form>
    </div>
</div>
</body>
</html> 