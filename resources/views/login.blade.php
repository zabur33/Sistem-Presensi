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
            @csrf
            <div class="logo">
                <!-- Logo SVG atau gambar -->
                <img src="{{ asset('images/logo.jpg') }}" alt="Life Media Logo">
            </div>
            <div class="desc"> Jika belum memiliki akun, hubungi admin!</div>
            <div class="input-group">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="M22 6l-10 7L2 6"/></svg>
                <input type="email" name="email" placeholder="Enter email" value="{{ old('email') }}" required>
            </div>
            <div class="input-group">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            @if ($errors->any())
                <div class="error" style="color:#e63946; margin-bottom:10px;">
                    {{ $errors->first() }}
                </div>
            @endif
            <button class="login-btn" type="submit">LOGIN</button>
        </form>
    </div>
</div>
</body>
</html>
