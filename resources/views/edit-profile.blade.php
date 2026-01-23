<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Life Media</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body>
<div class="main-container">
    <div class="sidebar">
        <div>
            <div class="logo">
                <img src="{{ asset('images/logo2.png') }}" alt="Life Media Logo">
            </div>
            <div class="menu">
                <div class="menu-title">MAIN MENU</div>
                <ul>
                    <li><a href="/dashboard"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>Dashboard</a></li>
                    <li class="presensi-menu">
                        <a href="javascript:void(0)" onclick="togglePresensiDropdown(event)">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            Presensi
                            <svg class="dropdown-arrow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6,9 12,15 18,9"/></svg>
                        </a>
                        <ul class="submenu" id="presensi-submenu">
                            <li><a href="/presensi/kantor">Kantor</a></li>
                            <li><a href="/presensi/luar-kantor">Luar Kantor</a></li>
                        </ul>
                    </li>
                    <li><a href="/lembur"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>Lembur</a></li>
                    <li><a href="/rekap-keseluruhan"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/></svg>Rekap Keseluruhan</a></li>
                    <div class="logout" style="margin:16px 0 0 0;">
                        <form method="POST" action="{{ route('logout') }}" style="display:flex;align-items:center;gap:8px;">
                            @csrf
                            <button type="submit" style="background:none;border:none;color:inherit;display:flex;align-items:center;gap:8px;cursor:pointer;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7"/><path d="M3 21V3a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v4"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </ul>
            </div>
        </div>
    </div>
    <div class="content-area">
        <div class="header">
            <div class="header-left">
                <div class="header-logo">
                    <img src="{{ asset('images/logo2.png') }}" alt="Life Media Logo">
                </div>
            </div>
            <div class="header-icons">
                <div id="notifWrapper" style="position:relative;display:inline-block;">
                    <a href="#" id="notifBell" onclick="toggleNotifDropdown(event)" title="Notifikasi">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        <span id="notifBadge" style="position:absolute;top:-4px;right:-4px;background:#dc2626;color:#fff;border-radius:999px;padding:0 6px;font-size:10px;line-height:18px;height:18px;display:none;">0</span>
                    </a>
                    <div id="notifDropdown" style="display:none;position:absolute;right:0;top:28px;background:#ffffff;border:1px solid #e5e7eb;border-radius:10px;min-width:280px;box-shadow:0 12px 24px rgba(0,0,0,0.12);z-index:50;">
                        <div style="padding:10px 12px;border-bottom:1px solid #f3f4f6;font-weight:700;color:#111827;">Notifikasi</div>
                        <div id="notifList" style="max-height:320px;overflow:auto"></div>
                    </div>
                </div>
                <a href="/profile">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>
                </a>
            </div>
        </div>
<div class="profile-content">
    <div class="page-title">
        <h1>Edit Profile</h1>
    </div>
    @if(session('success') || session('error'))
        <div style="margin-bottom:16px;position:relative;">
            <div style="padding:14px 16px;border-radius:14px;display:flex;gap:10px;align-items:center;font-weight:600;
                {{ session('success') ? 'background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;' : '' }}
                {{ session('error') ? 'background:#fef2f2;border:1px solid #fecaca;color:#991b1b;' : '' }}">
                @if(session('success'))
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                    <span>{{ session('success') }}</span>
                @else
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    <span>{{ session('error') }}</span>
                @endif
            </div>
        </div>
    @endif

    <div class="profile-container">
                <!-- User Information Form -->
                <div class="profile-form-section">
                    <div class="section-header">
                        <h2>User Information</h2>
                        <p style="margin-top:6px;color:#6b7280;font-size:14px;">Data identitas dikunci oleh sistem. Anda hanya dapat mengganti kata sandi dan foto profil.</p>
                    </div>

                    <form id="editProfileForm" class="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name*</label>
                            <input type="text" id="name" name="name" value="{{ auth()->user()->name ?? '' }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address*</label>
                            <input type="email" id="email" name="email" value="{{ auth()->user()->email ?? '' }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <div style="display:flex; gap:16px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:6px;">
                                    <input type="radio" name="gender" value="L" {{ (optional(auth()->user()->employee)->gender ?? '')==='L' ? 'checked' : '' }} disabled> Laki-laki
                                </label>
                                <label style="display:flex; align-items:center; gap:6px;">
                                    <input type="radio" name="gender" value="P" {{ (optional(auth()->user()->employee)->gender ?? '')==='P' ? 'checked' : '' }} disabled> Perempuan
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Enter new password (leave blank to keep current)">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ optional(auth()->user()->employee)->phone ?? '' }}" placeholder="Enter phone number" disabled>
                        </div>

                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" id="position" name="position" value="{{ optional(auth()->user()->employee)->position ?? '' }}" placeholder="Enter position" disabled>
                        </div>

                        <div class="form-actions">
                            <a href="/profile" id="btnCancel" class="btn-cancel" role="button">Cancel</a>
                            <button type="submit" class="btn-save">Save</button>
                        </div>
                    </form>
                </div>

                <!-- Profile Picture Section -->
                <div class="profile-picture-section">
                    <div class="profile-picture-container">
                        <div class="profile-picture editable">
                            <img src="{{ optional(auth()->user()->employee)->avatar_url ? asset('storage/'.optional(auth()->user()->employee)->avatar_url) : asset('images/profile-placeholder.jpg') }}" alt="Profile Picture" id="profileImage">
                            <label class="upload-overlay" for="profileImageInput">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                                <span>Change Photo</span>
                            </label>
                            <input type="file" id="profileImageInput" name="avatar" accept="image/*" style="display: none;" form="editProfileForm">
                        </div>
                        <div class="profile-info">
                            <h3 id="profileName">{{ auth()->user()->name ?? '' }}</h3>
                            <div id="profileRole" class="role">{{ optional(auth()->user()->employee)->position ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div id="forgotPasswordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reset Password</h3>
            <span class="close" onclick="closeForgotPasswordModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Enter your email address and we'll send you a link to reset your password.</p>
            <div class="form-group">
                <label for="resetEmail">Email Address</label>
                <input type="email" id="resetEmail" placeholder="Enter your email">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeForgotPasswordModal()">Cancel</button>
            <button class="btn-primary" onclick="sendResetLink()">Send Reset Link</button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <svg class="success-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4"/>
                <circle cx="12" cy="12" r="10"/>
            </svg>
            <h3>Profile Updated!</h3>
        </div>
        <div class="modal-body">
            <p>Your profile information has been successfully updated.</p>
        </div>
        <button class="modal-btn" onclick="redirectToProfile()">OK</button>
    </div>
</div>

<!-- Confirm Cancel Modal -->
<div id="confirmCancelModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Discard Changes?</h3>
            <span class="close" onclick="closeConfirmCancelModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to discard your changes? All unsaved changes will be lost.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeConfirmCancelModal()">Keep Editing</button>
            <button class="btn-primary" onclick="confirmCancel()">Discard Changes</button>
        </div>
    </div>
</div>

<script>
// Store original values for comparison
let originalValues = { password: '', password_confirmation: '', avatar: '' };

// Dropdown functionality
function togglePresensiDropdown(event) {
    event.preventDefault();
    event.stopPropagation();

    const submenu = document.getElementById('presensi-submenu');
    const arrow = document.querySelector('.presensi-menu .dropdown-arrow');

    if (submenu && arrow) {
        const isVisible = submenu.classList.contains('show');

        if (isVisible) {
            submenu.classList.remove('show');
            arrow.classList.remove('rotated');
            localStorage.setItem('presensi-dropdown-collapsed', 'true');
        } else {
            submenu.classList.add('show');
            arrow.classList.add('rotated');
            localStorage.setItem('presensi-dropdown-collapsed', 'false');
        }
    }
    return false;
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.presensi-menu')) {
        const submenu = document.querySelector('.presensi-menu .submenu');
        const arrow = document.querySelector('.presensi-menu .dropdown-arrow');
        if (submenu && submenu.classList.contains('show')) {
            submenu.classList.remove('show');
            localStorage.setItem('presensi-dropdown-collapsed', 'true');
        }
        if (arrow) arrow.classList.remove('rotated');
    }
});

// ===== Notifikasi Overtime (user) - Global on edit profile =====
let notifTimer=null;
function startNotifPolling(){
    fetchAndRenderNotif();
    if (notifTimer) clearInterval(notifTimer);
    notifTimer = setInterval(fetchAndRenderNotif, 30000);
}
function toggleNotifDropdown(e){ e.preventDefault(); const dd=document.getElementById('notifDropdown'); if(!dd) return; const is=dd.style.display==='block'; dd.style.display=is?'none':'block'; if(!is){ localStorage.setItem('overtime_last_seen', new Date().toISOString()); updateBadge([]); } }
function fetchAndRenderNotif(){
    fetch("{{ route('user.overtime.notifications') }}", { headers:{'Accept':'application/json'} })
        .then(r=>r.json()).then(items=>{ renderNotif(items); updateBadge(items); }).catch(()=>{}});
}
function renderNotif(items){
    const list = document.getElementById('notifList'); if(!list) return;
    if(!items || items.length===0){ list.innerHTML = '<div style="padding:12px;color:#6b7280;">Belum ada notifikasi</div>'; return; }
    list.innerHTML = items.map(it=>{
        const status = it.status==='approved'?'Disetujui':'Ditolak';
        const color = it.status==='approved'?'#065f46':'#991b1b';
        const badgeBg = it.status==='approved'?'#ecfdf5':'#fef2f2';
        const time = (it.updated_at||'').replace('T',' ').slice(0,16);
        return `<div style="padding:12px;border-bottom:1px solid #f3f4f6;">
            <div style=\"display:flex;justify-content:space-between;align-items:center;gap:8px;\">
                <div style=\"font-weight:700;color:#111827;\">Lembur ${status}</div>
                <span style=\"font-size:11px;color:#6b7280;\">${time}</span>
            </div>
            <div style=\"margin-top:6px;color:#374151;\">${(it.reason||'-')}</div>
            <span style=\"margin-top:8px;display:inline-block;padding:2px 8px;border-radius:999px;font-size:12px;font-weight:700;background:${badgeBg};color:${color};\">${status}</span>
        </div>`;
    }).join('');
}
function updateBadge(items){
    const badge = document.getElementById('notifBadge'); if(!badge) return;
    const lastSeen = new Date(localStorage.getItem('overtime_last_seen') || 0).getTime();
    const cnt = (items||[]).filter(it=> new Date(it.updated_at).getTime() > lastSeen).length;
    if(cnt>0){ badge.style.display='inline-block'; badge.textContent=cnt; } else { badge.style.display='none'; }
}

// start polling on load
window.addEventListener('load', startNotifPolling);

// Profile image upload
document.getElementById('profileImageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profileImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Profile picture click handler
document.querySelector('.profile-picture').addEventListener('click', function() {
    document.getElementById('profileImageInput').click();
});

// Check if form has changes
function hasChanges() {
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    const avatar = document.getElementById('profileImageInput').value;
    return password !== '' || passwordConfirmation !== '' || avatar !== '';
}

// Cancel edit with confirmation if changes exist
function cancelEdit() {
    if (hasChanges()) {
        document.getElementById('confirmCancelModal').style.display = 'flex';
    } else {
        window.location.href = '/profile';
    }
}

// Confirm cancel modal functions
function closeConfirmCancelModal() {
    document.getElementById('confirmCancelModal').style.display = 'none';
}

function confirmCancel() {
    // Close modal first
    const modal = document.getElementById('confirmCancelModal');
    if (modal) modal.style.display = 'none';
    // Remove beforeunload guard so navigation isn't blocked
    if (typeof beforeUnloadHandler === 'function') {
        window.removeEventListener('beforeunload', beforeUnloadHandler);
    }
    // Navigate back to profile
    window.location.href = '/profile';
}

// Form validation
function validateForm() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;

    if (password && password !== confirmPassword) {
        alert('Password and confirm password do not match');
        return false;
    }

    if (password && password.length < 6) {
        alert('Password must be at least 6 characters long');
        return false;
    }

    return true;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Save profile
function saveProfile() {
    if (!validateForm()) {
        return;
    }

    // Submit form normally
    document.getElementById('editProfileForm').submit();
}

// Redirect to profile page after success
function redirectToProfile() {
    window.location.href = '/profile';
}

// Forgot password modal
function showForgotPasswordModal() {
    document.getElementById('forgotPasswordModal').style.display = 'flex';
}

function closeForgotPasswordModal() {
    document.getElementById('forgotPasswordModal').style.display = 'none';
    document.getElementById('resetEmail').value = '';
}

function sendResetLink() {
    const email = document.getElementById('resetEmail').value;
    if (!email) {
        alert('Please enter your email address');
        return;
    }

    if (!isValidEmail(email)) {
        alert('Please enter a valid email address');
        return;
    }

    // Here you would typically send the reset link
    alert('Password reset link has been sent to your email');
    closeForgotPasswordModal();
}

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const forgotModal = document.getElementById('forgotPasswordModal');
    const successModal = document.getElementById('successModal');
    const confirmModal = document.getElementById('confirmCancelModal');

    if (event.target === forgotModal) {
        closeForgotPasswordModal();
    }
    if (event.target === successModal) {
        redirectToProfile();
    }
    if (event.target === confirmModal) {
        closeConfirmCancelModal();
    }
});

// Form submission
// Allow normal form submission to backend for avatar upload

// Ensure Cancel button click is captured even if form intercepts events
const btnCancel = document.getElementById('btnCancel');
if (btnCancel) {
    btnCancel.addEventListener('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        if (typeof beforeUnloadHandler === 'function') {
            window.removeEventListener('beforeunload', beforeUnloadHandler);
        }
        // If there are changes, show our confirm modal; otherwise navigate
        if (hasChanges()) {
            document.getElementById('confirmCancelModal').style.display = 'flex';
        } else {
            window.location.href = '/profile';
        }
    });
}

// Warn user before leaving page if there are unsaved changes
function beforeUnloadHandler(e){
    if (hasChanges()) {
        e.preventDefault();
        e.returnValue = '';
        return '';
    }
}
window.addEventListener('beforeunload', beforeUnloadHandler);
</script>
</body>
</html>
