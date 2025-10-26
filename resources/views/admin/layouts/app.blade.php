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
                <div id="adminNotifWrapper" style="position:relative;display:inline-block;">
                    <a href="#" id="adminNotifBell" onclick="toggleAdminNotifDropdown(event)" title="Notifikasi Pengaduan">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:24px;height:24px;color:#fff">
                            <path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <span id="adminNotifBadge" style="position:absolute;top:-6px;right:-6px;background:#dc2626;color:#fff;border-radius:999px;padding:0 6px;font-size:10px;line-height:18px;height:18px;display:none;">0</span>
                    </a>
                    <div id="adminNotifDropdown" style="display:none;position:absolute;right:0;top:28px;background:#ffffff;border:1px solid #e5e7eb;border-radius:10px;min-width:300px;box-shadow:0 12px 24px rgba(0,0,0,0.12);z-index:50;">
                        <div style="padding:10px 12px;border-bottom:1px solid #f3f4f6;font-weight:700;color:#111827;">Notifikasi Pengaduan</div>
                        <div id="adminNotifList" style="max-height:320px;overflow:auto"></div>
                    </div>
                </div>
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
<script>
// ==== Admin Complaint Notifications ====
let adminNotifTimer = null;
function toggleAdminNotifDropdown(e){
    e.preventDefault();
    const dd = document.getElementById('adminNotifDropdown');
    if(!dd) return;
    const is = dd.style.display === 'block';
    dd.style.display = is ? 'none' : 'block';
    if(!is){
        localStorage.setItem('admin_complaint_last_seen', new Date().toISOString());
        updateAdminBadge([]);
    }
}
function fetchAdminComplaints(){
    return fetch("{{ route('admin.complaints.notifications') }}", { headers:{'Accept':'application/json'} })
        .then(r=> r.ok ? r.json() : []).catch(()=>[]);
}
function renderAdminComplaints(items){
    const list = document.getElementById('adminNotifList'); if(!list) return;
    if(!items || items.length===0){ list.innerHTML = '<div style="padding:12px;color:#6b7280;">Belum ada pengaduan</div>'; return; }
    list.innerHTML = items.map(it=>{
        const name = (it.user_name || 'Pegawai');
        const time = (it.created_at||'').replace('T',' ').slice(0,16);
        const msg  = (it.message||'-');
        const status = (it.status||'baru');
        const color = status==='baru' ? '#1d4ed8' : '#065f46';
        const badgeBg = status==='baru' ? '#dbeafe' : '#ecfdf5';
        return `<div style="padding:12px;border-bottom:1px solid #f3f4f6;">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
                <div style="font-weight:700;color:#111827;">${name}</div>
                <span style="font-size:11px;color:#6b7280;">${time}</span>
            </div>
            <div style="margin-top:6px;color:#374151;">${msg}</div>
            <span style="margin-top:8px;display:inline-block;padding:2px 8px;border-radius:999px;font-size:12px;font-weight:700;background:${badgeBg};color:${color};text-transform:capitalize">${status}</span>
        </div>`;
    }).join('');
}
function updateAdminBadge(items){
    const badge = document.getElementById('adminNotifBadge'); if(!badge) return;
    const lastSeen = new Date(localStorage.getItem('admin_complaint_last_seen') || 0).getTime();
    const cnt = (items||[]).filter(it=> new Date(it.created_at).getTime() > lastSeen).length;
    if(cnt>0){ badge.style.display='inline-block'; badge.textContent=cnt; } else { badge.style.display='none'; }
}
function fetchAndRenderAdminComplaints(){
    fetchAdminComplaints().then(items=>{ renderAdminComplaints(items); updateAdminBadge(items); }).catch(()=>{});
}
function startAdminNotif(){
    fetchAndRenderAdminComplaints();
    if (adminNotifTimer) clearInterval(adminNotifTimer);
    adminNotifTimer = setInterval(fetchAndRenderAdminComplaints, 30000);
}
window.addEventListener('load', startAdminNotif);
</script>
</body>
</html>
