<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Life Media</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js'])
    @endif
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
                    <li><a href="/dashboard" class="active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>Dashboard</a></li>
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
                </ul>
            </div>
        </div>
        <div class="logout">
            <form method="POST" action="{{ route('logout') }}" style="display:flex;align-items:center;gap:8px;">
                @csrf
                <button type="submit" style="display:flex;align-items:center;gap:8px;background:none;border:none;color:inherit;cursor:pointer;">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7"/><path d="M3 21V3a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v4"/></svg>
                    Logout
                </button>
            </form>
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
        <div class="dashboard-content">
            <div class="profile-section">
                <img class="profile-pic" src="https://randomuser.me/api/portraits/men/.." alt="Profile">
                <div class="profile-info">
                    <div class="name">Ilham Wahyudi</div>
                    <div class="role">Staff IT</div>
                </div>
            </div>
            <div class="charts">
                <div class="chart-box">
                    <div class="chart-title">Grafik Presensi</div>
                    <canvas id="chartPresensi" class="chart-canvas"></canvas>
                </div>
                <div class="chart-box">
                    <div class="chart-title">Keaktifan Kerja</div>
                    <canvas id="chartAktif" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
 </div>
 <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
 <script>
 // ===== Notifikasi Overtime (user) - Global =====
 let notifTimer=null;
 function startNotifPolling(){
     fetchAndRenderNotif();
     if (notifTimer) clearInterval(notifTimer);
     notifTimer = setInterval(fetchAndRenderNotif, 30000);
 }
 function toggleNotifDropdown(e){ e.preventDefault(); const dd=document.getElementById('notifDropdown'); if(!dd) return; const is=dd.style.display==='block'; dd.style.display=is?'none':'block'; if(!is){ localStorage.setItem('overtime_last_seen', new Date().toISOString()); updateBadge([]); } }
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
 
 // Start polling on load
 window.addEventListener('load', startNotifPolling);
 </script>
<script>
    // Data contoh; nanti bisa diganti dari backend
    const labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'];
    const presensiData = [8, 7, 9, 8, 7];
    const aktifData = [70, 65, 85, 80, 75];

    const ctx1 = document.getElementById('chartPresensi');
    if (ctx1) {
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Jam Hadir',
                    data: presensiData,
                    backgroundColor: '#7ec6d3',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    const ctx2 = document.getElementById('chartAktif');
    if (ctx2) {
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Skor Aktivitas (%)',
                    data: aktifData,
                    backgroundColor: '#e76f51',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                scales: {
                    y: { beginAtZero: true, max: 100 }
                }
            }
        });
    }

// Dropdown functionality
function togglePresensiDropdown(event) {
    event.preventDefault();
    event.stopPropagation();
    
    const submenu = document.getElementById('presensi-submenu');
    const arrow = document.querySelector('.presensi-menu .dropdown-arrow');
    
    if (submenu && arrow) {
        const isVisible = submenu.classList.contains('show');
        
        if (isVisible) {
            // Hide submenu
            submenu.classList.remove('show');
            arrow.classList.remove('rotated');
            // Save state to localStorage
            localStorage.setItem('presensi-dropdown-collapsed', 'true');
        } else {
            // Show submenu
            submenu.classList.add('show');
            arrow.classList.add('rotated');
            // Save state to localStorage
            localStorage.setItem('presensi-dropdown-collapsed', 'false');
        }
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.presensi-menu')) {
        const submenu = document.querySelector('.presensi-menu .submenu');
        const arrow = document.querySelector('.presensi-menu .dropdown-arrow');
        if (submenu) {
            submenu.classList.remove('show');
            localStorage.setItem('presensi-dropdown-collapsed', 'true');
        }
        if (arrow) arrow.classList.remove('rotated');
    }
});

// Initialize dropdown state on dashboard
window.addEventListener('DOMContentLoaded', function() {
    const submenu = document.getElementById('presensi-submenu');
    const arrow = document.querySelector('.presensi-menu .dropdown-arrow');
    
    if (submenu && arrow) {
        // Check localStorage for dropdown state
        const isCollapsed = localStorage.getItem('presensi-dropdown-collapsed') === 'true';
        if (!isCollapsed) {
            submenu.classList.add('show');
            arrow.classList.add('rotated');
        }
    }
});
</script>
</body>
</html> 