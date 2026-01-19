<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Keseluruhan - Life Media</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="{{ asset('css/rekap.css') }}">
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
                    <li><a href="/rekap-keseluruhan" class="active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/></svg>Rekap Keseluruhan</a></li>
                    <div class="logout" style="margin:16px 0 0 0;">
                        <form method="POST" action="{{ route('logout') }}" style="display:flex;align-items:center;gap:8px;">
                            @csrf
                            <button type="submit" style="display:flex;align-items:center;gap:8px;background:none;border:none;color:inherit;cursor:pointer;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7"/><path d="M3 21V3a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v4"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </ul>
            </div>
        </div>
        <div>
        </div>
    </div>
    <div class="content-area">
        <div class="header">
            <div class="header-left">
                <button class="mobile-menu-btn" id="uMobileMenuBtn" aria-label="Buka menu">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
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
        <div class="mobile-drawer" id="uMobileDrawer" aria-hidden="true">
            <div class="drawer-backdrop" id="uDrawerBackdrop"></div>
            <div class="drawer-panel">
                <button class="drawer-close" id="uDrawerClose" aria-label="Tutup menu">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
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
                                    <ul class="submenu" id="presensi-submenu-drawer">
                                        <li><a href="/presensi/kantor">Kantor</a></li>
                                        <li><a href="/presensi/luar-kantor">Luar Kantor</a></li>
                                    </ul>
                                </li>
                                <li><a href="/lembur"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>Lembur</a></li>
                                <li><a href="/rekap-keseluruhan" class="active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/></svg>Rekap Keseluruhan</a></li>
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
            </div>
        </div>
        <div class="rekap-content">
            <div class="page-title">
                <h1>Rekap Keseluruhan</h1>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="search-filter">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search">
                        <svg class="search-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                    </div>
                    <button class="filter-btn" onclick="toggleFilterModal()">Filter</button>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-container">
                <table class="rekap-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kedatangan</th>
                            <th>Kepulangan</th>
                            <th>Total Jam Kerja</th>
                            <th>Keterangan</th>
                            <th>Tipe Presensi</th>
                            <th>Lokasi</th>
                            <th>Kegiatan</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody id="rekapTableBody">
                        @php
                            $i = 1;
                        @endphp
                        @forelse(($rows ?? []) as $r)
                            @php
                                $tgl = $r->date ? \Carbon\Carbon::parse($r->date)->format('d/m/Y') : '-';
                                $in  = $r->time_in ? \Carbon\Carbon::parse($r->time_in)->format('H:i') : '-';
                                $out = $r->time_out ? \Carbon\Carbon::parse($r->time_out)->format('H:i') : '-';
                                $dur = $r->duration_minutes ?? null;
                                if ($dur !== null) {
                                    $jam = intdiv($dur, 60);
                                    $menit = $dur % 60;
                                    $durasiTxt = $jam > 0 ? ($jam.' jam'.($menit>0?' '.$menit.' menit':'')) : ($menit.' menit');
                                } else {
                                    $durasiTxt = '-';
                                }
                            @endphp
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $tgl }}</td>
                                <td>{{ $in }}</td>
                                <td>{{ $out }}</td>
                                <td>{{ $durasiTxt }}</td>
                                <td>{{ $r->status ?? '-' }}</td>
                                <td>
                                    @php $lt = $r->location_type ?? null; @endphp
                                    {{ $lt === 'luar_kantor' ? 'Luar Kantor' : ($lt === 'kantor' ? 'Dalam Kantor' : '-') }}
                                </td>
                                <td>
                                    @if(($r->location_type ?? null) === 'luar_kantor')
                                        {{ $r->location_text ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ ($r->location_type === 'luar_kantor') ? ($r->activity_text ?? '-') : '-' }}</td>
                                <td>
                                    @if(($r->location_type ?? null) === 'luar_kantor' && !empty($r->photo_path))
                                        <a href="{{ asset('storage/'.$r->photo_path) }}" target="_blank">Lihat</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align:center;">Belum ada data presensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <button class="page-btn" onclick="previousPage()" id="prevBtn">Previous</button>
                <span class="page-info" id="pageInfo">Page 1 of 1</span>
                <button class="page-btn" onclick="nextPage()" id="nextBtn">Next</button>
            </div>
        </div>
    </div>
    </div>

<!-- Filter Modal -->
<div id="filterModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Filter Data</h3>
            <span class="close" onclick="toggleFilterModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="filter-group">
                <label for="dateFrom">Dari Tanggal:</label>
                <input type="date" id="dateFrom" name="dateFrom">
            </div>
            <div class="filter-group">
                <label for="dateTo">Sampai Tanggal:</label>
                <input type="date" id="dateTo" name="dateTo">
            </div>
            <div class="filter-group">
                <label for="statusFilter">Status:</label>
                <select id="statusFilter" name="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="Hadir">Hadir</option>
                    <option value="Terlambat">Terlambat</option>
                    <option value="Pulang Cepat">Pulang Cepat</option>
                    <option value="Tidak Hadir">Tidak Hadir</option>
                    <option value="Lembur">Lembur</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="resetFilter()">Reset</button>
            <button class="btn-primary" onclick="applyFilter()">Terapkan</button>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let itemsPerPage = 10;
let filteredData = [];
let allData = [];

// Dropdown functionality
function togglePresensiDropdown(event) {
    event.preventDefault();
    event.stopPropagation();
    const root = event.target.closest('.presensi-menu');
    if(!root) return false;
    const submenu = root.querySelector('.submenu');
    const arrow = root.querySelector('.dropdown-arrow');
    if (submenu) {
        const isVisible = submenu.classList.contains('show');
        submenu.classList.toggle('show', !isVisible);
        if (arrow) arrow.classList.toggle('rotated', !isVisible);
        localStorage.setItem('presensi-dropdown-collapsed', isVisible ? 'true' : 'false');
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

// Filter Modal
function toggleFilterModal() {
    const modal = document.getElementById('filterModal');
    modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('filterModal');
    if (event.target === modal) {
        toggleFilterModal();
    }
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    filterTable(searchTerm);
});

function filterTable(searchTerm) {
    const rows = document.querySelectorAll('#rekapTableBody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

// Filter functions
function applyFilter() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const status = document.getElementById('statusFilter').value;
    const params = new URLSearchParams();
    if (dateFrom) params.set('dateFrom', dateFrom);
    if (dateTo) params.set('dateTo', dateTo);
    if (status) params.set('status', status);
    window.location.href = `${window.location.pathname}?${params.toString()}`;
}

function resetFilter() {
    window.location.href = window.location.pathname;
}

// Pagination functions
function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        updatePagination();
    }
}

function nextPage() {
    // In a real app, you'd check against total pages
    currentPage++;
    updatePagination();
}

function updatePagination() {
    document.getElementById('pageInfo').textContent = `Page ${currentPage} of 1`;
    document.getElementById('prevBtn').disabled = currentPage === 1;
}

// Initialize page
window.addEventListener('load', function() {
    updatePagination();

    // Set default date range (current month)
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);

    document.getElementById('dateFrom').value = firstDay.toISOString().split('T')[0];
    document.getElementById('dateTo').value = lastDay.toISOString().split('T')[0];

    // Start polling notifications
    if (typeof startNotifPolling === 'function') startNotifPolling();
    // Restore presensi submenu state in both sidebar/drawer
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
// ===== Notifikasi Overtime (user) - Global on rekap =====
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
    if(dd.style.display==='block'){
        hideNotifDropdown();
    }else{
        showNotifDropdown();
    }
}
function showNotifDropdown(){
    const dd=document.getElementById('notifDropdown');
    if(!dd) return;
    dd.style.display='block';
    try{ localStorage.setItem('overtime_last_seen', new Date().toISOString()); }catch{}
    updateBadge([]);
}
function hideNotifDropdown(){
    const dd=document.getElementById('notifDropdown');
    if(!dd) return;
    dd.style.display='none';
}
document.addEventListener('click',function(e){
    const dd=document.getElementById('notifDropdown');
    const bell=document.getElementById('notifBell');
    if(!dd) return;
    if(dd.style.display==='block' && !dd.contains(e.target) && !bell.contains(e.target)){
        hideNotifDropdown();
    }
});
document.addEventListener('keydown',function(e){
    if(e.key==='Escape'){
        hideNotifDropdown();
    }
});
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
</script>
<script>
// Mobile Drawer toggle
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
})();
</script>
</body>
</html>
