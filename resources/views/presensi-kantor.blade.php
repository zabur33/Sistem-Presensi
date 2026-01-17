<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi - Life Media</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="{{ asset('css/presensi.css') }}">
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
                        <a href="javascript:void(0)" class="active" onclick="togglePresensiDropdown(event)">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            Presensi
                            <svg class="dropdown-arrow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6,9 12,15 18,9"/></svg>
                        </a>
                        <ul class="submenu" id="presensi-submenu">
                            <li><a href="/presensi/kantor" class="submenu-active">Kantor</a></li>
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
                <button class="mobile-menu-btn" id="uMobileMenuBtn" aria-label="Buka menu">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <a href="/dashboard" class="header-logo" aria-label="Kembali ke Dashboard">
                    <img src="{{ asset('images/logo2.png') }}" alt="Life Media Logo">
                </a>
            </div>
            <div class="header-icons">
                <div id="notifWrapper" style="position:relative;display:inline-block;">
                    <a href="#" id="notifBell" onclick="toggleNotifDropdown(event)" title="Notifikasi">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        <span id="notifBadge" style="position:absolute;top:-4px;right:-4px;background:#e11d48;color:#fff;border-radius:999px;padding:0 6px;font-size:10px;line-height:18px;height:18px;display:none;">0</span>
                    </a>
                    <div id="notifDropdown" style="display:none;position:absolute;right:0;top:28px;background:#fff;border:1px solid #e5e7eb;border-radius:8px;min-width:260px;box-shadow:0 10px 20px rgba(0,0,0,0.08);z-index:50;">
                        <div id="notifList" style="max-height:300px;overflow:auto"></div>
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
                    @include('user.partials.sidebar')
                </div>
            </div>
        </div>
        <div class="presensi-content">
            <div class="page-title">
                <h1>Presensi</h1>
                <p>Presensi Dalam Kantor</p>
            </div>

            <div class="attendance-grid">

                <!-- Kedatangan -->
                <div class="attendance-card">
                    <div class="card-header">
                        <h3>Kedatangan</h3>
                        <span class="status-badge" id="kedatangan-status">Presensi</span>
                    </div>
                    <div class="time-display" id="kedatangan-time">08.00</div>
                    <button class="presensi-btn" id="btn-kedatangan" onclick="handlePresensi('kedatangan')">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                        Presensi
                    </button>
                </div>

                <!-- Istirahat -->
                <div class="attendance-card">
                    <div class="card-header">
                        <h3>Istirahat</h3>
                        <span class="status-badge" id="kembali-status">Presensi</span>
                    </div>
                    <div class="time-display" id="kembali-time">60 <span class="unit">Menit</span></div>
                    <button class="presensi-btn" id="btn-kembali" onclick="handlePresensi('kembali')" disabled>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                        Presensi
                    </button>
                </div>

                <!-- Kembali dari Istirahat -->
                <div class="attendance-card">
                    <div class="card-header">
                        <h3>Kembali</h3>
                        <span class="status-badge" id="istirahat-status">Presensi</span>
                    </div>
                    <div class="time-display" id="istirahat-time">00.00</div>
                    <button class="presensi-btn" id="btn-istirahat" onclick="handlePresensi('istirahat')" disabled>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                        Presensi
                    </button>
                </div>

                <!-- Kepulangan -->
                <div class="attendance-card">
                    <div class="card-header">
                        <h3>Kepulangan</h3>
                        <span class="status-badge" id="kepulangan-status">Presensi</span>
                    </div>
                    <div class="time-display" id="kepulangan-time">00.00</div>
                    <button class="presensi-btn" id="btn-kepulangan" onclick="handlePresensi('kepulangan')" disabled>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                        Presensi
                    </button>
                </div>
            </div>

            <!-- Problem Report Section -->
            <div class="problem-section">
                <h2>Pengaduan masalah</h2>
                <div class="problem-card">
                    <div class="problem-input">
                        <textarea id="problem-text" placeholder="Apakah ada kendala saat melakukan presensi?" readonly></textarea>
                        <button class="report-btn" onclick="submitReport()">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Lapor
                        </button>
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
            <h3>Presensi Berhasil!</h3>
        </div>
        <div class="modal-body">
            <p id="modal-message">Presensi kedatangan berhasil dicatat.</p>
            <p id="modal-time">Waktu: <span id="recorded-time"></span></p>
        </div>
        <button class="modal-btn" onclick="closeModal()">OK</button>
    </div>
</div>


<script>
// Fetch helpers
function getCsrfToken() {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el ? el.getAttribute('content') : '';
}

// Storage key helpers - namespace by user and date
function getTodayKey() {
    const d = new Date();
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2,'0');
    const day = String(d.getDate()).padStart(2,'0');
    return `${y}-${m}-${day}`;
}
function storageKey() {
    const uid = (typeof window !== 'undefined' && window.LARAVEL_USER_ID) ? window.LARAVEL_USER_ID : ({{ auth()->id() ?? 'null' }});
    return `presensi-kantor-state:${uid}:${getTodayKey()}`;
}

async function postJSON(url, data) {
    const res = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify(data)
    });
    if (!res.ok) throw new Error('Request failed');
    return res.json().catch(() => ({}));
}
let attendanceState = {
    kedatangan: false,
    istirahat: false,
    kembali: false,
    kepulangan: false
};

let attendanceTimes = {
    kedatangan: null,
    istirahat: null,
    kembali: null,
    kepulangan: null
};

// Konfigurasi jam kerja (untuk penentuan telat/tepat waktu)
const WORK_START = '08:00'; // format HH:MM (24 jam)
const LATE_TOLERANCE_MIN = 0; // toleransi menit keterlambatan, 0 berarti tepat 08:00

function getCurrentTime() {
    const now = new Date();
    const hh = String(now.getHours()).padStart(2, '0');
    const mm = String(now.getMinutes()).padStart(2, '0');
    const ss = String(now.getSeconds()).padStart(2, '0');
    return `${hh}:${mm}:${ss}`;
}

// Live time updaters per card
const liveTimers = {};
function startLiveTime(displayId) {
    // clear previous if any
    if (liveTimers[displayId]) clearInterval(liveTimers[displayId]);
    const el = document.getElementById(displayId);
    if (!el) return;
    el.textContent = getCurrentTime();
    liveTimers[displayId] = setInterval(() => {
        el.textContent = getCurrentTime();
    }, 1000);
}

// Break (Istirahat) logic with 60-minute quota
const BREAK_QUOTA_MS = 60 * 60 * 1000; // 60 minutes
let breakUsedMs = 0;         // total used across sessions
let breakStartAt = null;     // timestamp when current break started
let breakInterval = null;    // interval id for ticking UI

function formatMMSS(ms) {
    const totalSec = Math.max(0, Math.floor(ms / 1000));
    const mm = String(Math.floor(totalSec / 60)).padStart(2, '0');
    const ss = String(totalSec % 60).padStart(2, '0');
    return `${mm}:${ss}`;
}

function updateBreakDisplays(remainingMs, elapsedMs) {
    // Show ELAPSED on the Istirahat card (second card: id='kembali-time' in markup)
    // Show REMAINING on the Kembali card (third card: id='istirahat-time')
    const istirahatCardEl = document.getElementById('kembali-time'); // should count up
    const kembaliCardEl   = document.getElementById('istirahat-time'); // show remaining quota
    if (istirahatCardEl) istirahatCardEl.textContent = formatMMSS(elapsedMs);
    if (kembaliCardEl)   kembaliCardEl.textContent   = formatMMSS(remainingMs);
}

// After user presses Kembali: show REMAINING on Istirahat card, and reset Kembali card to 00:00
function setBreakDisplaysAfterReturn(remainingMs) {
    const istirahatCardEl = document.getElementById('kembali-time'); // Istirahat card
    const kembaliCardEl   = document.getElementById('istirahat-time'); // Kembali card
    if (istirahatCardEl) istirahatCardEl.textContent = formatMMSS(Math.max(0, remainingMs));
    if (kembaliCardEl)   kembaliCardEl.textContent   = '00:00';
}

function startBreak() {
    const remainingAllowance = BREAK_QUOTA_MS - breakUsedMs;
    if (remainingAllowance <= 0) {
        alert('Waktu istirahat 60 menit sudah habis.');
        return;
    }

    // UI state: disable Istirahat button while running, enable Kembali
    const btnIstirahat = document.getElementById('btn-kembali');
    const btnKembali   = document.getElementById('btn-istirahat');
    if (btnIstirahat) btnIstirahat.disabled = true;
    if (btnKembali)   btnKembali.disabled   = false;

    breakStartAt = Date.now();
    savePresensiState();
    // clear existing interval
    if (breakInterval) clearInterval(breakInterval);
    // tick every second
    breakInterval = setInterval(() => {
        const now = Date.now();
        const sessionElapsed = now - breakStartAt;
        const totalElapsed = breakUsedMs + sessionElapsed;
        const remaining = BREAK_QUOTA_MS - totalElapsed;
        updateBreakDisplays(remaining, sessionElapsed);
    }, 1000);

    // immediate paint: elapsed starts at 0 on Istirahat card
    updateBreakDisplays(remainingAllowance, 0);
}

function finishBreak() {
    if (!breakStartAt) {
        // no running break; nothing to do
        return;
    }
    const now = Date.now();
    const sessionElapsed = now - breakStartAt;
    breakUsedMs += sessionElapsed;
    breakStartAt = null;
    if (breakInterval) { clearInterval(breakInterval); breakInterval = null; }

    const remaining = BREAK_QUOTA_MS - breakUsedMs;
    // After finishing break, show remaining on Istirahat card to avoid confusion
    setBreakDisplaysAfterReturn(remaining);

    // Late detection (over quota)
    const statusReturn = document.getElementById('istirahat-status');
    if (breakUsedMs > BREAK_QUOTA_MS && statusReturn) {
        statusReturn.textContent = 'Terlambat';
        statusReturn.classList.add('completed');
    }

    // UI: if masih ada sisa, boleh istirahat lagi
    const btnIstirahat = document.getElementById('btn-kembali');
    const btnKembali   = document.getElementById('btn-istirahat');
    if (btnKembali)   btnKembali.disabled   = true;
    if (btnIstirahat) btnIstirahat.disabled = remaining <= 0; // re-enable only if there is time left

    // After at least one return, allow kepulangan
    const btnKepulangan = document.getElementById('btn-kepulangan');
    if (btnKepulangan) btnKepulangan.disabled = false;
    savePresensiState();
}

function stopAllTimers() {
    // stop generic live timers
    Object.keys(liveTimers).forEach(id => clearInterval(liveTimers[id]));
    // stop break timer
    if (breakInterval) { clearInterval(breakInterval); breakInterval = null; }
}

// Reset state and UI to initial so user can presensi again
function resetPresensiStateAndUI() {
    try {
        stopAllTimers();

        // Reset in-memory state
        attendanceState = { kedatangan: false, istirahat: false, kembali: false, kepulangan: false };
        attendanceTimes = { kedatangan: null, istirahat: null, kembali: null, kepulangan: null };
        breakUsedMs = 0;
        breakStartAt = null;

        // Clear persisted state
        localStorage.removeItem(storageKey());

        // Restore badges
        ['kedatangan','kembali','istirahat','kepulangan'].forEach(type => {
            const badge = document.getElementById(`${type}-status`);
            if (badge) {
                badge.textContent = 'Presensi';
                badge.classList.remove('completed');
            }
        });

        // Restore times to initial placeholders
        const ked = document.getElementById('kedatangan-time');
        if (ked) ked.textContent = '08.00';
        const istRem = document.getElementById('kembali-time');
        if (istRem) istRem.innerHTML = '60 <span class="unit">Menit</span>';
        const kembaliTime = document.getElementById('istirahat-time');
        if (kembaliTime) kembaliTime.textContent = '00.00';
        const pulangTime = document.getElementById('kepulangan-time');
        if (pulangTime) pulangTime.textContent = '00.00';

        // Helper to restore button content
        const btnIcon = '\n        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">\n            <path d="M9 12l2 2 4-4"/>\n        </svg>\n        Presensi\n    ';

        // Enable only Kedatangan, others disabled
        const btnKed = document.getElementById('btn-kedatangan');
        if (btnKed) { btnKed.disabled = false; btnKed.classList.remove('completed'); btnKed.innerHTML = btnIcon; }
        const btnIst = document.getElementById('btn-kembali');
        if (btnIst)  { btnIst.disabled = true; btnIst.classList.remove('completed'); btnIst.innerHTML = btnIcon; }
        const btnKmb = document.getElementById('btn-istirahat');
        if (btnKmb) { btnKmb.disabled = true; btnKmb.classList.remove('completed'); btnKmb.innerHTML = btnIcon; }
        const btnPul = document.getElementById('btn-kepulangan');
        if (btnPul) { btnPul.disabled = true; btnPul.classList.remove('completed'); btnPul.innerHTML = btnIcon; }

        // Ensure pointer styles are correct
        const buttons = document.querySelectorAll('button, .presensi-btn, .report-btn');
        buttons.forEach(button => {
            button.style.pointerEvents = 'auto';
            button.style.cursor = 'pointer';
        });
    } catch {}
}

function handlePresensi(type) {
    const currentTime = getCurrentTime();
    const button = document.getElementById(`btn-${type}`);
    const statusBadge = document.getElementById(`${type}-status`);
    const timeDisplay = document.getElementById(`${type}-time`);

    // Custom flows
    if (type === 'kembali') { // This card labeled "Istirahat"
        // start break session (resumable, 60-min quota)
        startBreak();
        // update status badge to running
        if (statusBadge) statusBadge.textContent = 'Berjalan';
        return;
    }

    if (type === 'istirahat') { // This card labeled "Kembali"
        // finish current break session
        finishBreak();
        // set recorded time display to current
        if (timeDisplay) timeDisplay.textContent = currentTime;
        // Do not start live ticking; times should remain static after return
        return;
    }

    if (type === 'kepulangan') {
        // Sync checkout to backend
        postJSON('{{ route('attendance.checkout') }}', { location_type: 'kantor', client_time: currentTime }).catch(() => {});
        // Freeze Kedatangan at checkout time and stop timers
        const ked = document.getElementById('kedatangan-time');
        if (ked) ked.textContent = currentTime;
        stopAllTimers();
        if (timeDisplay) timeDisplay.textContent = currentTime;
        // disable other action buttons
        ['btn-kedatangan','btn-kembali','btn-istirahat','btn-kepulangan'].forEach(id=>{
            const b=document.getElementById(id); if(b) b.disabled=true;
        });
        // mark status
        if (statusBadge) statusBadge.textContent = 'Presensi';
        // save and exit without starting live timer
        attendanceState[type] = true;
        attendanceTimes[type] = currentTime;
        // persist frozen kedatangan time as well
        attendanceTimes['kedatangan'] = currentTime;
        savePresensiState();
        // Show success modal (do not reset automatically; keep recorded times visible)
        showSuccessModal(type, currentTime);
        return;
    }

    // Record attendance
    attendanceState[type] = true;
    attendanceTimes[type] = currentTime;

    // Update UI
    button.disabled = true;
    button.classList.add('completed');
    button.innerHTML = `
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M9 12l2 2 4-4"/>
        </svg>
        Selesai
    `;

    // Set status badge dan waktu tampil
    if (type === 'kedatangan') {
        // Tentukan telat atau tepat waktu berdasarkan WORK_START
        const [wsH, wsM] = WORK_START.split(':').map(n=>parseInt(n,10));
        const now = new Date();
        const start = new Date(now.getFullYear(), now.getMonth(), now.getDate(), wsH, wsM + LATE_TOLERANCE_MIN, 0, 0);
        const isLate = now.getTime() > start.getTime();
        statusBadge.textContent = isLate ? 'Terlambat' : 'Tepat Waktu';
        statusBadge.classList.add('completed');
        // Start ticking kedatangan time until checkout
        startLiveTime('kedatangan-time');
    } else {
        statusBadge.textContent = 'Presensi';
        statusBadge.classList.add('completed');
        timeDisplay.textContent = currentTime;
    }
    // Jangan menyalakan ticking setelah waktu dicatat (biarkan statis)

    // Enable next button in sequence
    enableNextButton(type);

    // Sync check-in on kedatangan
    if (type === 'kedatangan') {
        postJSON('{{ route('attendance.checkin') }}', { location_type: 'kantor', client_time: currentTime }).catch(() => {});
    }

    // Show success modal
    showSuccessModal(type, currentTime);

    // Persist after action
    savePresensiState();
}

// ===== Persistence (localStorage) =====
function savePresensiState() {
    const state = {
        attendanceState,
        attendanceTimes,
        breakUsedMs,
        breakStartAt,
    };
    localStorage.setItem(storageKey(), JSON.stringify(state));
}

function loadPresensiState() {
    try {
        const raw = localStorage.getItem(storageKey());
        if (!raw) return;
        const s = JSON.parse(raw);
        if (s.attendanceState) attendanceState = s.attendanceState;
        if (s.attendanceTimes) attendanceTimes = s.attendanceTimes;
        if (typeof s.breakUsedMs === 'number') breakUsedMs = s.breakUsedMs;
        if (s.breakStartAt) breakStartAt = s.breakStartAt;

        // Restore UI for each card
        ['kedatangan','kembali','istirahat','kepulangan'].forEach(type => {
            const timeVal = attendanceTimes[type];
            const timeEl = document.getElementById(`${type}-time`);
            const btn = document.getElementById(`btn-${type}`);
            const statusBadge = document.getElementById(`${type}-status`);
            if (timeVal && timeEl) timeEl.textContent = timeVal;
            if (attendanceState[type] && btn) {
                btn.disabled = true;
                btn.classList.add('completed');
                btn.innerHTML = `\n        <svg fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" viewBox=\"0 0 24 24\">\n            <path d=\"M9 12l2 2 4-4\"/>\n        </svg>\n        Selesai\n    `;
                if (statusBadge) statusBadge.classList.add('completed');
            }
            if (type === 'kedatangan' && attendanceState.kedatangan && !attendanceState.kepulangan) {
                startLiveTime('kedatangan-time');
            }
        });

        // Restore break displays and ticking if running
        const remaining = Math.max(0, BREAK_QUOTA_MS - breakUsedMs - (breakStartAt ? (Date.now() - breakStartAt) : 0));
        const sessionElapsed = breakStartAt ? (Date.now() - breakStartAt) : 0;
        updateBreakDisplays(remaining, sessionElapsed);

        // Restore buttons availability
        if (!attendanceState.kedatangan) {
            document.getElementById('btn-kembali').disabled = true;
            document.getElementById('btn-istirahat').disabled = true;
            document.getElementById('btn-kepulangan').disabled = true;
        } else {
            // kedatangan sudah, default enable istirahat
            const btnIst = document.getElementById('btn-kembali');
            if (btnIst) btnIst.disabled = breakStartAt ? true : (remaining <= 0);
            const btnKmb = document.getElementById('btn-istirahat');
            if (btnKmb) btnKmb.disabled = !breakStartAt; // hanya aktif saat sesi berjalan
        }

        // If break is running, resume ticking
        if (breakStartAt) {
            // ensure types: number
            if (typeof breakStartAt === 'string') breakStartAt = parseInt(breakStartAt, 10);
            if (breakInterval) clearInterval(breakInterval);
            breakInterval = setInterval(() => {
                const now = Date.now();
                const elapsed = now - breakStartAt;
                const total = breakUsedMs + elapsed;
                const rem = BREAK_QUOTA_MS - total;
                updateBreakDisplays(rem, elapsed);
            }, 1000);
        }

        // Kedatangan clock rules
        // - Before check-in: run live clock
        // - After check-in and before checkout: keep running
        // - After checkout: do not run
        if (!attendanceState.kedatangan) {
            startLiveTime('kedatangan-time');
        } else if (attendanceState.kedatangan && !attendanceState.kepulangan) {
            startLiveTime('kedatangan-time');
        }
    } catch {}
}

function enableNextButton(currentType) {
    switch(currentType) {
        case 'kedatangan':
            document.getElementById('btn-kembali').disabled = false;
            break;
        case 'kembali':
            document.getElementById('btn-istirahat').disabled = false;
            break;
        case 'istirahat':
            document.getElementById('btn-kepulangan').disabled = false;
            break;
    }
}

function showSuccessModal(type, time) {
    const modal = document.getElementById('successModal');
    const message = document.getElementById('modal-message');
    const recordedTime = document.getElementById('recorded-time');

    const messages = {
        kedatangan: 'Presensi kedatangan berhasil dicatat.',
        kembali: 'Presensi istirahat berhasil dicatat.',
        istirahat: 'Presensi kembali dari istirahat berhasil dicatat.',
        kepulangan: 'Presensi kepulangan berhasil dicatat.'
    };

    message.textContent = messages[type];
    recordedTime.textContent = time;
    modal.style.display = 'flex';
}

function closeModal() {
    document.getElementById('successModal').style.display = 'none';
}


function submitReport() {
    const problemInput = document.getElementById('problem-text');
    const message = (problemInput.value || '').trim();
    if (message === '' || message === '') {
        alert('Silakan masukkan deskripsi masalah terlebih dahulu.');
        return;
    }
    const payload = {
        message,
        location_type: 'kantor',
        client_time: getCurrentTime()
    };
    fetch("{{ route('user.complaints.submit') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken(), 'Accept': 'application/json' },
        body: JSON.stringify(payload)
    }).then(async(res)=>{
        if(!res.ok){
            const t = await res.text().catch(()=> '');
            throw new Error('Gagal mengirim laporan ('+res.status+'): '+t.slice(0,200));
        }
        return res.json().catch(()=> ({}));
    }).then(()=>{
        alert('Laporan berhasil dikirim. Admin akan mendapatkan notifikasi.');
        problemInput.value = '';
        problemInput.readOnly = true;
    }).catch(err=>{
        alert('Tidak dapat mengirim laporan saat ini. Coba lagi nanti.');
        console.warn(err);
    });
}

// Make problem textarea editable when clicked
document.getElementById('problem-text').addEventListener('focus', function() {
    if (this.value === '') {
        this.value = '';
    }
    this.readOnly = false;
});

document.getElementById('problem-text').addEventListener('blur', function() {
    if (this.value.trim() === '') {
        this.value = '';
        this.readOnly = true;
    }
});

// Initialize page when loaded
window.addEventListener('load', function() {
    // Show submenu on presensi pages by default - but allow toggle
    const submenu = document.getElementById('presensi-submenu');
    const arrow = document.querySelector('.presensi-menu .dropdown-arrow');
    if (submenu && arrow) {
        // Check if user previously collapsed it (from localStorage)
        const isCollapsed = localStorage.getItem('presensi-dropdown-collapsed') === 'true';
        if (!isCollapsed) {
            submenu.classList.add('show');
            arrow.classList.add('rotated');
        }
    }

    // Restore presensi/timer state
    if (typeof loadPresensiState === 'function') {
        loadPresensiState();
    }
    // Jika user baru membuka halaman dan belum presensi kedatangan, mulai jam real-time
    try {
        if (!attendanceState.kedatangan) {
            startLiveTime('kedatangan-time');
        }
    } catch {}
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const successModal = document.getElementById('successModal');
    if (event.target === successModal) {
        closeModal();
    }
});

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
            localStorage.setItem('presensi-dropdown-collapsed', 'true');
        } else {
            // Show submenu
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

// Ensure all buttons are clickable
document.addEventListener('DOMContentLoaded', function() {
    // Force enable button clicks
    const buttons = document.querySelectorAll('button, .presensi-btn, .report-btn');
    buttons.forEach(button => {
        button.style.pointerEvents = 'auto';
        button.style.cursor = 'pointer';
    });

    // Force enable dropdown link
    const dropdownLink = document.querySelector('.presensi-menu > a');
    if (dropdownLink) {
        dropdownLink.style.pointerEvents = 'auto';
        dropdownLink.style.cursor = 'pointer';
    }
});

// ===== Notifikasi Overtime (user) =====
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

// Close notification dropdown when clicking outside
document.addEventListener('click', function(event){
    const wrapper = document.getElementById('notifWrapper');
    const dropdown = document.getElementById('notifDropdown');
    if(!wrapper || !dropdown) return;
    if(!wrapper.contains(event.target)){
        dropdown.style.display = 'none';
    }
});
</script>
<script>
// Mobile Drawer toggle (standalone page)
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
