<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Life Media</title>
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
            <a href="/dashboard" class="logo">
                <img src="{{ asset('images/logo2.png') }}" alt="Life Media Logo">
            </a>
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
                        <form method="POST" action="{{ route('logout') }}" style="display:flex;align-items:center;gap:8px;" onsubmit="console.log('Logout form submitted');">
                            @csrf
                            <button type="submit" style="display:flex;align-items:center;gap:8px;background:none;border:none;color:inherit;cursor:pointer;padding:0;">
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
                <button class="mobile-menu-btn" id="uMobileMenuBtn" aria-label="Buka menu">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <a href="/dashboard" class="header-logo">
                    <img src="{{ asset('images/logo2.png') }}" alt="Life Media Logo">
                </a>
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
        <div class="mobile-drawer" id="uMobileDrawer" aria-hidden="true">
            <div class="drawer-backdrop" id="uDrawerBackdrop"></div>
            <div class="drawer-panel">
                <button class="drawer-close" id="uDrawerClose" aria-label="Tutup menu">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
                <div class="sidebar">
                    <div>
                        <a href="/dashboard" class="logo">
                            <img src="{{ asset('images/logo2.png') }}" alt="Life Media Logo">
                        </a>
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
                                    <form method="POST" action="{{ route('logout') }}" style="display:flex;align-items:center;gap:8px;" onsubmit="console.log('Logout form submitted');">
                                        @csrf
                                        <button type="submit" style="display:flex;align-items:center;gap:8px;background:none;border:none;color:inherit;cursor:pointer;padding:0;">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7"/><path d="M3 21V3a 2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v4"/></svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<div class="profile-content">
    <div class="page-title">
        <h1>Profile</h1>
    </div>
    @if(session('success'))
        <div style="margin:12px 0;padding:12px 16px;border-radius:12px;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;font-weight:600;display:flex;gap:10px;align-items:center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @elseif(session('error'))
        <div style="margin:12px 0;padding:12px 16px;border-radius:12px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;font-weight:600;display:flex;gap:10px;align-items:center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="profile-container">
                <!-- User Information Form -->
                <div class="profile-form-section">
                    <div class="section-header">
                        <h2>User Information</h2>
                    </div>
                    
                    <form id="profileForm" class="profile-form">
                        <div class="form-group">
                            <label for="name">Name*</label>
                            <input type="text" id="name" name="name" value="{{ auth()->user()->name ?? '' }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address*</label>
                            <input type="email" id="email" name="email" value="{{ auth()->user()->email ?? '' }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="••••••••" readonly>
                        </div>

                        

                        <div class="form-group">
                            <label for="nip">Nomor Induk Pegawai</label>
                            <input type="text" id="nip" name="nip" value="{{ optional(auth()->user()->employee)->nip ?? '' }}" placeholder="NIP" readonly>
                        </div>

                        <div class="form-group">
                            <label for="gender">Jenis Kelamin</label>
                            <select id="gender" name="gender" disabled>
                                <option value="">Pilih</option>
                                <option value="L" {{ (optional(auth()->user()->employee)->gender ?? '')==='L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ (optional(auth()->user()->employee)->gender ?? '')==='P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="birth_date">Tanggal Lahir</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ optional(auth()->user()->employee)->birth_date ?? '' }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <input type="text" id="address" name="address" value="{{ optional(auth()->user()->employee)->address ?? '' }}" placeholder="Alamat lengkap" readonly>
                        </div>

                        <div class="form-group">
                            <label for="position">Jabatan</label>
                            <input type="text" id="position" name="position" value="{{ optional(auth()->user()->employee)->position ?? '' }}" placeholder="Contoh: Staff IT" readonly>
                        </div>

                        <div class="form-group">
                            <label for="phone">No. Telepon</label>
                            <input type="text" id="phone" name="phone" value="{{ optional(auth()->user()->employee)->phone ?? '' }}" placeholder="08xxxxxxxxxx" readonly>
                        </div>

                        <div class="form-group">
                            <label for="division">Divisi</label>
                            <input type="text" id="division" name="division" value="{{ optional(auth()->user()->employee)->division ?? '' }}" placeholder="Contoh: Teknologi Informasi" readonly>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-edit" onclick="window.location.href='/edit-profile'">Edit</button>
                        </div>
                    </form>
                </div>

                <!-- Profile Picture Section -->
                <div class="profile-picture-section">
                    <div class="profile-picture-container">
                        <div class="profile-picture">
                            <img src="{{ optional(auth()->user()->employee)->avatar_url ? asset('storage/'.optional(auth()->user()->employee)->avatar_url) : asset('images/profile-placeholder.jpg') }}" alt="Profile Picture" id="profileImage">
                            <div class="upload-overlay" id="uploadOverlay" style="display: none;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                                <span>Change Photo</span>
                            </div>
                            <input type="file" id="profileImageInput" accept="image/*" style="display: none;">
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
        <button class="modal-btn" onclick="closeSuccessModal()">OK</button>
    </div>
</div>

<script>
let isEditing = false;

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

// ===== Notifikasi Overtime (user) - Global on profile =====
let notifTimer=null;
function startNotifPolling(){
    fetchAndRenderNotif();
    if (notifTimer) clearInterval(notifTimer);
    notifTimer = setInterval(fetchAndRenderNotif, 30000);
}
function toggleNotifDropdown(e){
    if(e){
        e.preventDefault();
        e.stopPropagation();
    }
    const dd=document.getElementById('notifDropdown');
    if(!dd) return;
    const is=dd.style.display==='block';
    dd.style.display=is?'none':'block';
    if(!is){
        try{ localStorage.setItem('overtime_last_seen', new Date().toISOString()); }catch{}
        updateBadge([]);
    }
}
function fetchAndRenderNotif(){
    fetch("{{ route('user.overtime.notifications') }}", { headers:{'Accept':'application/json'} })
        .then(r=>r.json()).then(items=>{ renderNotif(items); updateBadge(items); }).catch(()=>{});
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
window.addEventListener('load', function(){
    startNotifPolling();
});

// Close notification dropdown when clicking outside
document.addEventListener('click', function(event){
    const wrapper = document.getElementById('notifWrapper');
    const dropdown = document.getElementById('notifDropdown');
    if(!wrapper || !dropdown) return;
    if(!wrapper.contains(event.target)){
        dropdown.style.display = 'none';
    }
});

// Toggle edit mode
function toggleEdit() {
    isEditing = !isEditing;
    const inputs = document.querySelectorAll('#profileForm input');
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const uploadOverlay = document.getElementById('uploadOverlay');

    inputs.forEach(input => {
        if (input.id !== 'password') {
            input.readOnly = !isEditing;
        }
    });

    if (isEditing) {
        editBtn.style.display = 'none';
        saveBtn.style.display = 'block';
        uploadOverlay.style.display = 'flex';
        document.querySelector('.profile-picture').classList.add('editable');
    } else {
        editBtn.style.display = 'block';
        saveBtn.style.display = 'none';
        uploadOverlay.style.display = 'none';
        document.querySelector('.profile-picture').classList.remove('editable');
    }
}

// Save profile
async function saveProfile() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (!name || !email) {
        alert('Please fill in all required fields');
        return;
    }

    try {
        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        
        if (password) {
            formData.append('password', password);
            formData.append('password_confirmation', password);
        }

        // Add file if selected
        const fileInput = document.getElementById('profileImageInput');
        if (fileInput.files[0]) {
            formData.append('avatar', fileInput.files[0]);
        }

        const response = await fetch('{{ route("profile.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            // Update profile display
            document.getElementById('profileName').textContent = name;
            
            // Toggle back to view mode
            toggleEdit();
            
            // Show success modal
            document.getElementById('successModal').style.display = 'flex';
        } else {
            alert(result.message || 'Error updating profile');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error updating profile. Please try again.');
    }
}

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
    if (isEditing) {
        document.getElementById('profileImageInput').click();
    }
});

// (Forgot password removed)

// Success modal
function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
}

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const successModal = document.getElementById('successModal');
    if (event.target === successModal) {
        closeSuccessModal();
    }
});

// Form submission
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    saveProfile();
});

// Mobile drawer functionality
(function(){
    const drawer = document.getElementById('uMobileDrawer');
    const openBtn = document.getElementById('uMobileMenuBtn');
    const closeBtn = document.getElementById('uDrawerClose');
    const backdrop = document.getElementById('uDrawerBackdrop');
    
    function open(){ 
        if(drawer){ 
            drawer.classList.add('open'); 
            document.body.style.overflow='hidden'; 
        } 
    }
    
    function close(){ 
        if(drawer){ 
            drawer.classList.remove('open'); 
            document.body.style.overflow=''; 
        } 
    }
    
    if(openBtn) openBtn.addEventListener('click', open);
    if(closeBtn) closeBtn.addEventListener('click', close);
    if(backdrop) backdrop.addEventListener('click', close);
})();
</script>
</body>
</html>
