<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css','resources/css/dashboard.css','resources/js/app.js'])
    @else
        <link href="{{ asset('build/assets/app-hAZc3-CV.css') }}" rel="stylesheet">
        <link href="{{ asset('build/assets/dashboard-CILs4XdD.css') }}" rel="stylesheet">
        <script src="{{ asset('build/assets/app-C0G0cght.js') }}" defer></script>
    @endif

    <style>
        :root{
            --brand:#cc5b60;
            --brand-dark:#a8444c;
            --sidebar-bg:#fff3ee;
            --sidebar-border:#f0d4c9;
            --muted:#6b7280;
            --text:#0f172a;
        }
        .main-container{display:grid;grid-template-columns:260px 1fr;min-height:100vh;background:#fafafa}
        /* Sidebar */
        .sidebar{background:var(--sidebar-bg);border-right:1px solid var(--sidebar-border);display:flex;flex-direction:column}
        .logo{display:flex;align-items:center;gap:10px;padding:14px 18px}
        .logo .brand-name{font-weight:800;color:#c0505a}
        .menu{padding:6px 0}
        .menu-title{color:var(--brand);font-weight:800;font-size:12px;letter-spacing:.08em;padding:0 18px;margin:6px 0 8px}
        .sidebar ul{list-style:none;margin:0;padding:0}
        .sidebar a{display:flex;align-items:center;gap:12px;padding:10px 18px;color:#2b2b2b;text-decoration:none;border-radius:10px}
        .sidebar a:hover{background:#ffe6de}
        .sidebar a.active{background:var(--brand);color:#fff}
        .submenu{display:none;padding-left:36px}
        .submenu.show{display:block}
        .dropdown-arrow{transition:transform .2s ease}
        .dropdown-arrow.rotated{transform:rotate(180deg)}
        .logout{margin-top:auto;padding:14px 18px;color:#c7c7c7}
        .logout button{background:none;border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px;width:100%;display:flex;align-items:center;gap:10px;color:#6b7280}
        /* Header */
        .header{position:sticky;top:0;z-index:20;background:linear-gradient(90deg,var(--brand-dark),var(--brand));color:#fff;padding:10px 20px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 2px 8px rgba(0,0,0,.08)}
        .header-left{display:flex;align-items:center;gap:10px}
        .header-title{font-weight:800}
        .header-icons a{color:#fff;margin-left:16px;display:inline-flex}
        /* Content */
        .dashboard-wrap{padding:18px;max-width:1200px;margin:0 auto}
        .grid{display:grid;gap:18px}
        .grid-4{grid-template-columns:repeat(4,minmax(0,1fr))}
        .grid-2{grid-template-columns:repeat(2,minmax(0,1fr))}
        .stat-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px}
        .stat-value{font-weight:800;font-size:26px;color:var(--text)}
        .stat-label{color:var(--muted);font-size:13px;margin-top:6px}
        .accent-green{color:#16a34a}.accent-blue{color:#2563eb}.accent-red{color:#dc2626}
        .card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px}
        .card-header{font-weight:700;color:var(--text);margin-bottom:10px}
        .chart-container{height:320px;position:relative}
        .footer{margin:20px 0 4px;padding:12px 0;text-align:center;color:#9ca3af;font-size:12px}
        @media (max-width:1024px){.main-container{grid-template-columns:1fr}.sidebar{position:fixed;inset:0 auto 0 0;width:260px;transform:translateX(-100%);transition:transform .25s}.sidebar.active{transform:translateX(0)}.grid-4{grid-template-columns:repeat(2,1fr)}}
        @media (max-width:640px){.grid-4,.grid-2{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div>
            <div class="logo">
                <img src="{{ asset('images/logo2.png') }}" alt="Life Media" class="h-10">
            </div>
            <div class="menu">
                <div class="menu-title">MAIN MENU</div>
                <ul>
                    <li><a href="/dashboard" class="active">Dashboard</a></li>
                    <li class="presensi-menu">
                        <a href="#" onclick="togglePresensiDropdown(event)" style="justify-content:space-between;display:flex;align-items:center;">
                            <span style="display:flex;align-items:center;gap:12px;">Presensi</span>
                            <svg class="dropdown-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <ul class="submenu" id="presensi-submenu">
                            <li><a href="/presensi/kantor">Kantor</a></li>
                            <li><a href="/presensi/luar-kantor">Luar Kantor</a></li>
                        </ul>
                    </li>
                    <li><a href="/lembur">Lembur</a></li>
                    <li><a href="/rekap-keseluruhan">Rekap Keseluruhan</a></li>
                </ul>
            </div>
        </div>
        <div class="logout">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/><path d="M7 21a4 4 0 01-4-4V7a4 4 0 014-4h6a4 4 0 014 4v2"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Content -->
    <div class="content-area">
        <div class="header">
            <div class="header-left">
                <img src="{{ asset('images/logo2.png') }}" class="h-7" alt="Life Media"/>
            </div>
            <div class="header-icons">
                <a href="#" title="Notifikasi"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg></a>
                <a href="/profile" title="Profil"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg></a>
            </div>
        </div>

        <div class="dashboard-wrap">
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:4px 0 14px;">Dashboard</h2>

            <!-- Stats -->
            <div class="grid grid-4">
                <div class="stat-card"><div class="stat-value" id="total-work-days">0</div><div class="stat-label">Total Hari Kerja</div></div>
                <div class="stat-card"><div class="stat-value accent-green" id="present-this-month">0 Hari</div><div class="stat-label">Kehadiran Bulan Ini</div></div>
                <div class="stat-card"><div class="stat-value accent-blue" id="wfh-outdoor">0 Hari</div><div class="stat-label">WFH / Dinas Luar</div></div>
                <div class="stat-card"><div class="stat-value accent-red" id="late-count">0x</div><div class="stat-label">Keterlambatan</div></div>
            </div>

            <!-- Charts -->
            <div class="grid grid-2" style="margin-top:18px;">
                <div class="card"><div class="card-header">Grafik Kehadiran</div><div class="chart-container"><canvas id="attendanceChart"></canvas></div></div>
                <div class="card"><div class="card-header">Mode Kerja</div><div class="chart-container"><canvas id="modeChart"></canvas></div></div>
            </div>

            <!-- Status Today -->
            <div class="card" style="margin-top:18px;">
                <div class="card-header">Status Hari Ini</div>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="padding:8px;border-radius:999px;background:#e8faf3;color:#16a34a;display:inline-flex;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <div class="text-sm" id="todayLocation" style="font-weight:700;color:#111827;">-</div>
                        <div class="text-xs" id="todayTimes" style="color:#6b7280;">Check-in: — | Check-out: —</div>
                    </div>
                    <button id="checkoutBtn" style="margin-left:auto;padding:8px 12px;border-radius:8px;background:#10b981;color:#fff;display:none;">Check Out</button>
                </div>
            </div>

            <div class="footer">© {{ date('Y') }} Life Media. All rights reserved.</div>
        </div>
    </div>
</div>

<script>
function togglePresensiDropdown(event){
    event.preventDefault();
    const submenu=document.getElementById('presensi-submenu');
    const arrow=document.querySelector('.presensi-menu .dropdown-arrow');
    if(!submenu||!arrow) return; const show=!submenu.classList.contains('show');
    submenu.classList.toggle('show',show); arrow.classList.toggle('rotated',show);
}

document.addEventListener('DOMContentLoaded', async () => {
    const barCtx=document.getElementById('attendanceChart');
    const bar=new Chart(barCtx,{type:'bar',data:{labels:['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],datasets:[
        {label:'Dinas',data:[3,2,2,1,2,1,2,2,1,2,2,1],backgroundColor:'#f59e0b',borderRadius:6,barPercentage:.7},
        {label:'Hadir',data:[19,17,22,20,19,21,22,20,21,19,20,18],backgroundColor:'#10b981',borderRadius:6,barPercentage:.7},
        {label:'WFH',data:[5,7,4,5,6,5,4,6,5,7,6,7],backgroundColor:'#3b82f6',borderRadius:6,barPercentage:.7}
    ]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}},scales:{y:{beginAtZero:true,ticks:{stepSize:5},max:25},x:{grid:{display:false}}}}});

    const pieCtx=document.getElementById('modeChart');
    const pie=new Chart(pieCtx,{type:'pie',data:{labels:['Dinas','WFH','Hadir'],datasets:[{data:[15,25,60],backgroundColor:['#f59e0b','#3b82f6','#10b981'],borderWidth:0}]} ,options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'right'}}}});

    try{
        const res=await fetch('/api/dashboard/metrics',{headers:{'Accept':'application/json'}});
        if(res.ok){
            const data=await res.json();
            document.getElementById('total-work-days').textContent = data.stats?.total_work_days ?? 0;
            document.getElementById('present-this-month').textContent = `${data.stats?.days_present ?? 0} Hari`;
            document.getElementById('wfh-outdoor').textContent = `${data.stats?.remote_days ?? 0} Hari`;
            document.getElementById('late-count').textContent = `${data.stats?.late_days ?? 0}x`;
            if(data.today_status){
                document.getElementById('todayLocation').textContent = data.today_status.location || '-';
                document.getElementById('todayTimes').textContent = `Check-in: ${data.today_status.time_in ?? '—'} | Check-out: ${data.today_status.time_out ?? '—'}`;
                const btn=document.getElementById('checkoutBtn'); if(data.today_status.can_checkout) btn.style.display='inline-flex';
            }
            if(data.monthly_data){
                const dinas=new Array(12).fill(0), hadir=new Array(12).fill(0), wfh=new Array(12).fill(0);
                data.monthly_data.forEach(m=>{const i=(m.month||1)-1; hadir[i]=+m.present||0; dinas[i]=+m.dinas||0; wfh[i]=+m.wfh||0;});
                bar.data.datasets[0].data=dinas; bar.data.datasets[1].data=hadir; bar.data.datasets[2].data=wfh; bar.update();
            }
            if(data.mode_summary){ pie.data.datasets[0].data=[data.mode_summary.dinas||0,data.mode_summary.wfh||0,data.mode_summary.hadir||0]; pie.update(); }
        }
    }catch(e){ console.warn('API metrics tidak tersedia:', e.message); }
});
</script>
</body>
</html>
