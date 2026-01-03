<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User - Life Media</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js'])
    @endif
    <style>
    @media (max-width: 600px){
        .mobile-drawer .sidebar svg{width:22px!important;height:22px!important}
        .mobile-drawer .sidebar img{max-width:140px;height:auto}
        .mobile-drawer .sidebar a{padding:10px 16px}
        .mobile-drawer .sidebar .menu-title{margin-left:16px}
    }
    @media (min-width: 601px){
        .mobile-drawer{display:none!important}
    }
    /* Safety: drawer should not catch events unless open */
    .mobile-drawer{pointer-events:none}
    .mobile-drawer .drawer-panel{pointer-events:auto}
    .mobile-drawer.open{pointer-events:auto}
    /* Force-hide drawer and backdrop unless open */
    .mobile-drawer:not(.open){display:none!important}
    .mobile-drawer:not(.open) .drawer-backdrop{display:none!important}
    /* Footer styling */
    .footer{margin:20px 0 4px;padding:12px 0;text-align:center;color:#9ca3af;font-size:12px}
    </style>
    @stack('head')
</head>
<body>
<div class="main-container">
    <div class="sidebar">
        @include('user.partials.sidebar')
    </div>
    <div class="content-area">
        <div class="header">
            <div class="header-left">
                <button class="mobile-menu-btn" id="uMobileMenuBtn" aria-label="Buka menu">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <a href="/dashboard" class="header-logo" aria-label="Kembali ke Dashboard">
                    <img src="{{ asset('images/logo2.png') }}" alt="Life Media Logo">
                </a>
            </div>
            <div class="header-icons" style="margin-left:auto; display:flex; align-items:center; gap:12px;">
                <div id="notifWrapper" style="position:relative;display:inline-block;">
                    <a href="#" id="notifBell" onclick="toggleNotifDropdown(event)" title="Notifikasi" aria-label="Notifikasi"
                       style="display:inline-flex; align-items:center; justify-content:center; color:#ffffff;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <span id="notifBadge" style="position:absolute;top:-4px;right:-4px;background:#e11d48;color:#fff;border-radius:999px;padding:0 6px;font-size:10px;line-height:18px;height:18px;display:none;">0</span>
                    </a>
                    <div id="notifDropdown" style="display:none;position:absolute;right:0;top:28px;background:#fff;border:1px solid #e5e7eb;border-radius:8px;min-width:260px;box-shadow:0 10px 20px rgba(0,0,0,0.08);z-index:50;">
                        <div id="notifList" style="max-height:300px;overflow:auto"></div>
                    </div>
                </div>
                <a href="/profile" aria-label="Kelola Profile" title="Kelola Profile"
                   style="display:inline-flex; align-items:center; justify-content:center; background:transparent; color:#ffffff; padding:0; margin:0; text-decoration:none;">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M5.5 21a7.5 7.5 0 0 1 13 0"/>
                    </svg>
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
                    @include('user.partials.sidebar')
                </div>
            </div>
        </div>
        <div class="dashboard-content">
            @yield('content')
        </div>
    </div>
</div>
<script>
let notifTimer=null;
function startNotifPolling(){
    fetchAndRenderNotif();
    if (notifTimer) clearInterval(notifTimer);
    notifTimer = setInterval(fetchAndRenderNotif, 30000);
}
function toggleNotifDropdown(e){ e.preventDefault(); e.stopPropagation(); const dd=document.getElementById('notifDropdown'); if(!dd) return; const is=dd.style.display==='block'; dd.style.display=is?'none':'block'; if(!is){ localStorage.setItem('overtime_last_seen', new Date().toISOString()); updateBadge([]); } }
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
            <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
                <div style="font-weight:700;color:#111827;">Lembur ${status}</div>
                <span style="font-size:11px;color:#6b7280;">${time}</span>
            </div>
            <div style="margin-top:6px;color:#374151;">${(it.reason||'-')}</div>
            <span style="margin-top:8px;display:inline-block;padding:2px 8px;border-radius:999px;font-size:12px;font-weight:700;background:${badgeBg};color:${color};">${status}</span>
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
</script>
<script>
(function(){
    const drawer = document.getElementById('uMobileDrawer');
    const openBtn = document.getElementById('uMobileMenuBtn');
    const closeBtn = document.getElementById('uDrawerClose');
    const backdrop = document.getElementById('uDrawerBackdrop');
    function open(){ if(drawer){ drawer.classList.add('open'); document.body.style.overflow='hidden'; } }
    function close(){ if(drawer){ drawer.classList.remove('open'); document.body.style.overflow=''; } }
    if(openBtn) openBtn.addEventListener('click', open);
    if(closeBtn) closeBtn.addEventListener('click', close);
    if(backdrop) backdrop.addEventListener('click', close);
    // Auto-close when resizing to desktop
    const mq = window.matchMedia('(min-width: 601px)');
    function onChange(){ if(mq.matches){ close(); } }
    try{ mq.addEventListener('change', onChange); }catch{ window.addEventListener('resize', onChange); }
    // Force close on first load if desktop
    if (mq.matches) { close(); }
})();
</script>
<script>
// Close notif dropdown when clicking anywhere outside
document.addEventListener('click', function(e){
    const wrap = document.getElementById('notifWrapper');
    const dd = document.getElementById('notifDropdown');
    if(!dd) return;
    if(!wrap || !wrap.contains(e.target)){
        dd.style.display = 'none';
    }
});
</script>
<script>
// Generic handler for Presensi submenu inside sidebar (works in drawer and desktop)
function togglePresensiDropdown(event){
    if(event){ event.preventDefault(); event.stopPropagation(); }
    const root = (event && event.target) ? event.target.closest('.presensi-menu') : document.querySelector('.presensi-menu');
    if(!root) return false;
    const submenu = root.querySelector('.submenu');
    const arrow = root.querySelector('.dropdown-arrow');
    const isVisible = submenu && submenu.classList.contains('show');
    if(submenu){ submenu.classList.toggle('show', !isVisible); }
    if(arrow){ arrow.classList.toggle('rotated', !isVisible); }
    try{ localStorage.setItem('presensi-dropdown-collapsed', isVisible ? 'true' : 'false'); }catch{}
    return false;
}
// restore state on load
window.addEventListener('load', function(){
    try{
        const collapsed = localStorage.getItem('presensi-dropdown-collapsed') === 'true';
        document.querySelectorAll('.presensi-menu').forEach(root=>{
            const submenu = root.querySelector('.submenu');
            const arrow = root.querySelector('.dropdown-arrow');
            if(submenu){ submenu.classList.toggle('show', !collapsed); }
            if(arrow){ arrow.classList.toggle('rotated', !collapsed); }
        });
    }catch{}
});
</script>
</body>
</html>
