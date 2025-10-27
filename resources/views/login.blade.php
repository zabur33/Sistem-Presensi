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
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="transform: rotate(0deg);">
                    <rect x="3" y="11" width="18" height="11" rx="2" />
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>
                <input id="password" type="password" name="password" placeholder="Enter password" required>
                <button type="button" id="togglePassword" aria-label="Tampilkan/Sembunyikan password" style="background:none;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:6px;margin-left:6px;">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
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
<script>
// Toggle show/hide password
(function(){
    const input = document.getElementById('password');
    const btn = document.getElementById('togglePassword');
    const eye = document.getElementById('eyeIcon');
    if(btn && input && eye){
        btn.addEventListener('click', function(){
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            // swap icon between eye and eye-off
            eye.innerHTML = isHidden
                ? '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20C5 20 1 12 1 12a21.8 21.8 0 0 1 5.06-6.94M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.74 21.74 0 0 1-3.18 4.49"/><line x1="1" y1="1" x2="23" y2="23"/>'
                : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        });
    }
})();
</script>
</body>
</html>
