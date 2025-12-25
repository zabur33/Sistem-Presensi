<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Luar Kantor - Life Media</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="{{ asset('css/presensi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/presensi-luar.css') }}">
    <style>
        .required-asterisk{ color:#e11d48; }
    </style>
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
                            <li><a href="/presensi/kantor">Kantor</a></li>
                            <li><a href="/presensi/luar-kantor" class="submenu-active">Luar Kantor</a></li>
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
                    @include('user.partials.sidebar')
                </div>
            </div>
        </div>
        <div class="presensi-content">
            <div class="page-title">
                <h1>Presensi Luar Kantor</h1>
                <p>WARNING!! Sebelum melakukan Presensi diwajibkan untuk mengambil foto dan mengisi keterangan terlebih dahulu!!</p>
            </div>

            <div class="attendance-grid">
                <!-- Kedatangan -->
                <div class="attendance-card">
                    <div class="card-header">
                        <h3>Kedatangan</h3>
                        <span class="status-badge" id="kedatangan-status">Presensi</span>
                    </div>
                    <div class="time-display" id="kedatangan-time">08.00</div>
                    <button class="presensi-btn" id="btn-kedatangan" onclick="handlePresensi('kedatangan')" disabled>
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
                        <span class="status-badge" id="istirahat-status">Presensi</span>
                    </div>
                    <div class="time-display" id="istirahat-time">60 <span class="unit">Menit</span></div>
                    <button class="presensi-btn" id="btn-istirahat" onclick="handlePresensi('istirahat')" disabled>
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
                        <span class="status-badge" id="kembali-status">Presensi</span>
                    </div>
                    <div class="time-display" id="kembali-time">00.00</div>
                    <button class="presensi-btn" id="btn-kembali" onclick="handlePresensi('kembali')" disabled>
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

            <!-- Camera Capture Section -->
            <div class="camera-section">
                <h2>Ambil Foto Presensi <span class="required-asterisk" aria-hidden="true">*</span></h2>
                <div class="camera-card">
                    <!-- Camera Preview -->
                    <div class="camera-preview" id="cameraPreview" style="display: none;">
                        <video id="cameraVideo" autoplay playsinline></video>
                        <div class="camera-overlay">
                            <div class="camera-controls">
                                <button class="zoom-out-btn" onclick="adjustZoom(-0.1)" title="Zoom Out">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="11" cy="11" r="8"/>
                                        <path d="M21 21l-4.35-4.35"/>
                                        <line x1="8" y1="11" x2="14" y2="11"/>
                                    </svg>
                                </button>
                                <button class="capture-btn" onclick="capturePhoto()">
                                    <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/>
                                    </svg>
                                </button>
                                <button class="zoom-in-btn" onclick="adjustZoom(0.1)" title="Zoom In">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="11" cy="11" r="8"/>
                                        <path d="M21 21l-4.35-4.35"/>
                                        <line x1="8" y1="11" x2="14" y2="11"/>
                                        <line x1="11" y1="8" x2="11" y2="14"/>
                                    </svg>
                                </button>
                                <button class="close-camera-btn" onclick="stopCamera()">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <canvas id="captureCanvas" style="display: none;"></canvas>
                    </div>

                    <!-- Camera Start Area -->
                    <div class="camera-area" id="cameraArea">
                        <div class="camera-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                        </div>
                        <p class="camera-text">Ambil Foto Langsung dari Kamera</p>
                        <p class="camera-subtext">Foto wajib diambil sebelum melakukan presensi</p>
                        <div class="camera-status" id="cameraStatus">
                            <span class="status-indicator pending">Belum Mengambil Foto</span>
                        </div>
                    </div>
                    <button class="camera-btn" id="cameraBtn" onclick="startCamera()">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                        Ambil Foto
                    </button>
                </div>

                <!-- Photo Preview -->
                <div class="photo-preview" id="photoPreview" style="display: none;">
                    <img id="previewImage" src="" alt="Preview">
                    <div class="photo-info">
                        <div class="location-info">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span id="locationText">Mendeteksi lokasi...</span>
                        </div>
                        <div class="time-info">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12,6 12,12 16,14"/>
                            </svg>
                            <span id="timeText"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Section -->
            <div class="location-section">
                <h2>Tambahkan Lokasi</h2>
                <div class="location-card">
                    <div class="location-input">
                        <input type="text" id="locationInput" placeholder="Lokasi akan terdeteksi otomatis..." readonly>
                        <button class="location-btn" onclick="getCurrentLocation()">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            Deteksi Lokasi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Activity Description Section -->
            <div class="activity-section">
                <h2>Tambahkan Keterangan <span class="required-asterisk" aria-hidden="true">*</span></h2>
                <div class="activity-card">
                    <div class="activity-input">
                        <textarea id="activityInput" placeholder="Jelaskan kegiatan yang akan/sedang dilakukan..."></textarea>
                        <button class="activity-btn" onclick="saveActivity()">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                            </svg>
                            Simpan Keterangan
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
            <p id="modal-location" style="display: none;">Lokasi: <span id="recorded-location"></span></p>
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
    const m = String(d.getMonth()+1).padStart(2,'0');
    const day = String(d.getDate()).padStart(2,'0');
    return `${y}-${m}-${day}`;
}
function storageKey() {
    const uid = (typeof window !== 'undefined' && window.LARAVEL_USER_ID) ? window.LARAVEL_USER_ID : ({{ auth()->id() ?? 'null' }});
    return `presensi-luar-state:${uid}:${getTodayKey()}`;
}

// Multipart upload helper
async function postForm(url, formData) {
    const res = await fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: formData
    });
    if (!res.ok) throw new Error('Upload failed');
    return res.json().catch(() => ({}));
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

// Konfigurasi jam kerja untuk luar kantor (penentuan telat/tepat waktu)
const WORK_START = '08:00'; // format HH:MM (24 jam)
const LATE_TOLERANCE_MIN = 0; // toleransi menit keterlambatan

let currentLocation = null;
let uploadedPhoto = null;
let activityDescription = '';
let proofUploaded = false;
let currentLat = null;
let currentLng = null;
let currentAccuracy = null;

function tryUploadProofIfReady() {
    if (proofUploaded) return;
    if (!uploadedPhoto || !currentLocation) return;
    const fd = new FormData();
    // Name the file
    const file = uploadedPhoto instanceof Blob ? new File([uploadedPhoto], `presensi_${Date.now()}.jpg`, { type: uploadedPhoto.type || 'image/jpeg' }) : uploadedPhoto;
    fd.append('photo', file);
    fd.append('location_text', currentLocation);
    fd.append('location_type', 'luar_kantor');
    if (currentLat != null && currentLng != null) {
        fd.append('lat', currentLat);
        fd.append('lng', currentLng);
    }
    if (currentAccuracy != null) {
        fd.append('accuracy', currentAccuracy);
    }
    if (activityDescription && activityDescription.trim().length > 0) {
        fd.append('activity_text', activityDescription.trim());
    }
    postForm('{{ route('attendance.proof') }}', fd)
        .then(() => { proofUploaded = true; })
        .catch(() => { /* silently ignore, user can retry by retake */ });
}

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
    if (liveTimers[displayId]) clearInterval(liveTimers[displayId]);
    const el = document.getElementById(displayId);
    if (!el) return;
    el.textContent = getCurrentTime();
    liveTimers[displayId] = setInterval(() => {
        el.textContent = getCurrentTime();
    }, 1000);
}

// Break (Istirahat) logic with 60-minute quota (resumable)
const BREAK_QUOTA_MS = 60 * 60 * 1000; // 60 minutes
let breakUsedMs = 0;
let breakStartAt = null; // timestamp if running
let breakInterval = null;

function formatMMSS(ms) {
    const totalSec = Math.max(0, Math.floor(ms / 1000));
    const mm = String(Math.floor(totalSec / 60)).padStart(2, '0');
    const ss = String(totalSec % 60).padStart(2, '0');
    return `${mm}:${ss}`;
}

function updateBreakDisplays(remainingMs, elapsedMs) {
    // Make Istirahat card run (show elapsed), and Kembali card show remaining quota
    const istirahatEl = document.getElementById('istirahat-time'); // should count up
    const kembaliEl   = document.getElementById('kembali-time');   // show remaining
    if (istirahatEl) istirahatEl.textContent = formatMMSS(elapsedMs);
    if (kembaliEl)   kembaliEl.textContent   = formatMMSS(remainingMs);
}

// After user presses Kembali: show REMAINING on Istirahat card, reset Kembali to 00:00
function setBreakDisplaysAfterReturn(remainingMs) {
    const istirahatEl = document.getElementById('istirahat-time');
    const kembaliEl   = document.getElementById('kembali-time');
    if (istirahatEl) istirahatEl.textContent = formatMMSS(Math.max(0, remainingMs));
    if (kembaliEl)   kembaliEl.textContent   = '00:00';
}

function startBreak() {
    const remainingAllowance = BREAK_QUOTA_MS - breakUsedMs;
    if (remainingAllowance <= 0) {
        alert('Waktu istirahat 60 menit sudah habis.');
        return;
    }
    // UI: disable Istirahat while running, enable Kembali
    const btnIstirahat = document.getElementById('btn-istirahat');
    const btnKembali = document.getElementById('btn-kembali');
    if (btnIstirahat) btnIstirahat.disabled = true;
    if (btnKembali) btnKembali.disabled = false;

    breakStartAt = Date.now();
    savePresensiState();
    if (breakInterval) clearInterval(breakInterval);
    breakInterval = setInterval(() => {
        const now = Date.now();
        const sessionElapsed = now - breakStartAt;
        const totalElapsed = breakUsedMs + sessionElapsed;
        const remaining = BREAK_QUOTA_MS - totalElapsed;
        updateBreakDisplays(remaining, sessionElapsed);
    }, 1000);
    updateBreakDisplays(remainingAllowance, 0);
}

function finishBreak() {
    if (!breakStartAt) return;
    const now = Date.now();
    const sessionElapsed = now - breakStartAt;
    breakUsedMs += sessionElapsed;
    breakStartAt = null;
    if (breakInterval) { clearInterval(breakInterval); breakInterval = null; }

    const remaining = BREAK_QUOTA_MS - breakUsedMs;
    // After finishing break, show remaining on Istirahat card to avoid confusion
    setBreakDisplaysAfterReturn(remaining);

    // Late detection on return card's badge (kembali-status)
    const returnStatus = document.getElementById('kembali-status');
    if (breakUsedMs > BREAK_QUOTA_MS && returnStatus) {
        returnStatus.textContent = 'Terlambat';
        returnStatus.classList.add('completed');
    }

    // Re-enable Istirahat if still has remaining quota
    const btnIstirahat = document.getElementById('btn-istirahat');
    const btnKembali = document.getElementById('btn-kembali');
    if (btnKembali) btnKembali.disabled = true;
    if (btnIstirahat) btnIstirahat.disabled = remaining <= 0;

    // Allow kepulangan after at least one return
    const btnKepulangan = document.getElementById('btn-kepulangan');
    if (btnKepulangan) btnKepulangan.disabled = false;
    savePresensiState();
}

function stopAllTimers() {
    Object.keys(liveTimers).forEach(id => clearInterval(liveTimers[id]));
    if (breakInterval) { clearInterval(breakInterval); breakInterval = null; }
}

// Reset state and UI so user can start a fresh luar-kantor attendance
function resetPresensiStateAndUI() {
    try {
        stopAllTimers();

        // Reset core states
        attendanceState = { kedatangan: false, istirahat: false, kembali: false, kepulangan: false };
        attendanceTimes = { kedatangan: null, istirahat: null, kembali: null, kepulangan: null };
        breakUsedMs = 0;
        breakStartAt = null;

        // Reset luar-kantor specifics
        uploadedPhoto = null;
        currentLocation = null;
        activityDescription = '';
        proofUploaded = false;

        // Clear persisted state
        localStorage.removeItem(storageKey());

        // Badges back to default
        ['kedatangan','istirahat','kembali','kepulangan'].forEach(type => {
            const badge = document.getElementById(`${type}-status`);
            if (badge) { badge.textContent = 'Presensi'; badge.classList.remove('completed'); }
        });

        // Times back to placeholders
        const ked = document.getElementById('kedatangan-time'); if (ked) ked.textContent = '08.00';
        const ist = document.getElementById('istirahat-time'); if (ist) ist.innerHTML = '60 <span class="unit">Menit</span>';
        const kmb = document.getElementById('kembali-time'); if (kmb) kmb.textContent = '00.00';
        const pul = document.getElementById('kepulangan-time'); if (pul) pul.textContent = '00.00';

        // Restore button contents and disabled state
        const btnIcon = '\n        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">\n            <path d="M9 12l2 2 4-4"/>\n        </svg>\n        Presensi\n    ';
        const btnKed = document.getElementById('btn-kedatangan');
        if (btnKed) { btnKed.disabled = true; btnKed.classList.remove('completed'); btnKed.innerHTML = btnIcon; }
        const btnIst = document.getElementById('btn-istirahat');
        if (btnIst) { btnIst.disabled = true; btnIst.classList.remove('completed'); btnIst.innerHTML = btnIcon; }
        const btnKmb = document.getElementById('btn-kembali');
        if (btnKmb) { btnKmb.disabled = true; btnKmb.classList.remove('completed'); btnKmb.innerHTML = btnIcon; }
        const btnPul = document.getElementById('btn-kepulangan');
        if (btnPul) { btnPul.disabled = true; btnPul.classList.remove('completed'); btnPul.innerHTML = btnIcon; }

        // Reset camera/photo UI
        const cameraArea = document.getElementById('cameraArea');
        const cameraPreview = document.getElementById('cameraPreview');
        const cameraStatus = document.getElementById('cameraStatus');
        const cameraBtn = document.getElementById('cameraBtn');
        const photoPreview = document.getElementById('photoPreview');
        const previewImage = document.getElementById('previewImage');
        if (photoPreview) photoPreview.style.display = 'none';
        if (previewImage) previewImage.src = '';
        if (cameraArea) cameraArea.style.display = 'block';
        if (cameraPreview) cameraPreview.style.display = 'none';
        if (cameraStatus) cameraStatus.innerHTML = '<span class="status-indicator pending">Belum Mengambil Foto</span>';
        if (cameraBtn) {
            cameraBtn.classList.remove('completed');
            cameraBtn.innerHTML = '\n                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">\n                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>\n                    <circle cx="12" cy="13" r="4"/>\n                </svg>\n                Ambil Foto\n            ';
        }

        // Reset location and activity inputs
        const locInput = document.getElementById('locationInput');
        if (locInput) { locInput.value = ''; locInput.placeholder = 'Lokasi akan terdeteksi otomatis...'; }
        const actInput = document.getElementById('activityInput');
        if (actInput) actInput.value = '';

        // Fix cursors
        const buttons = document.querySelectorAll('button, .presensi-btn, .camera-btn, .activity-btn');
        buttons.forEach(button => {
            button.style.pointerEvents = 'auto';
            button.style.cursor = button.disabled ? 'not-allowed' : 'pointer';
        });
    } catch {}
}

function handlePresensi(type) {
    // Require description and photo before any presensi action
    const actEl = document.getElementById('activityInput');
    const actVal = actEl ? actEl.value.trim() : '';
    if (!actVal) {
        alert('Mohon isi keterangan kegiatan terlebih dahulu.');
        if (actEl) actEl.focus();
        return;
    }
    activityDescription = actVal;
    if (!uploadedPhoto) {
        alert('Anda harus mengambil foto terlebih dahulu sebelum melakukan presensi!');
        return;
    }
    const currentTime = getCurrentTime();
    const button = document.getElementById(`btn-${type}`);
    const statusBadge = document.getElementById(`${type}-status`);
    const timeDisplay = document.getElementById(`${type}-time`);

    // Custom flows
    if (type === 'istirahat') { // start break
        startBreak();
        const statusBadge = document.getElementById('istirahat-status');
        if (statusBadge) statusBadge.textContent = 'Berjalan';
        return;
    }

    if (type === 'kembali') { // finish break
        finishBreak();
        const timeDisplay = document.getElementById('kembali-time');
        if (timeDisplay) timeDisplay.textContent = currentTime;
        return;
    }

    if (type === 'kepulangan') {
        // sync checkout
        postJSON('{{ route('attendance.checkout') }}', { location_type: 'luar_kantor', client_time: currentTime }).catch(() => {});
        // Freeze Kedatangan at checkout time and stop timers
        const ked = document.getElementById('kedatangan-time');
        if (ked) kedatanganDisplayBeforeFreeze = ked.textContent;
        if (ked) ked.textContent = currentTime;
        stopAllTimers();
        const timeDisplay = document.getElementById('kepulangan-time');
        if (timeDisplay) timeDisplay.textContent = currentTime;
        ['btn-kedatangan','btn-istirahat','btn-kembali','btn-kepulangan'].forEach(id=>{ const b=document.getElementById(id); if(b) b.disabled=true; });
        attendanceState[type] = true;
        attendanceTimes[type] = currentTime;
        savePresensiState();
        // Keep recorded times visible; do not trigger auto reset
        showSuccessModal(type, currentTime);
        return;
    }

    // Record attendance (kedatangan)
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

    // Set status & waktu tampil
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

    // Enable next button in sequence
    enableNextButton(type);

    // Sync check-in when kedatangan
    if (type === 'kedatangan') {
        postJSON('{{ route('attendance.checkin') }}', { location_type: 'luar_kantor', client_time: currentTime }).catch(() => {});
    }

    // Show success modal
    showSuccessModal(type, currentTime);

    // Persist
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
        // Cleanup legacy key once
        try { localStorage.removeItem('presensi-luar-state'); } catch {}
        const raw = localStorage.getItem(storageKey());
        if (!raw) return;
        const s = JSON.parse(raw);
        if (s.attendanceState) attendanceState = s.attendanceState;
        if (s.attendanceTimes) attendanceTimes = s.attendanceTimes;
        if (typeof s.breakUsedMs === 'number') breakUsedMs = s.breakUsedMs;
        if (s.breakStartAt) breakStartAt = s.breakStartAt;

        // Restore times/buttons
        ['kedatangan','istirahat','kembali','kepulangan'].forEach(type => {
            const timeVal = attendanceTimes[type];
            const el = document.getElementById(`${type}-time`);
            const btn = document.getElementById(`btn-${type}`);
            const status = document.getElementById(`${type}-status`);
            if (timeVal && el) el.textContent = timeVal;
            if (attendanceState[type] && btn) {
                btn.disabled = true;
                btn.classList.add('completed');
                btn.innerHTML = `\n        <svg fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" viewBox=\"0 0 24 24\">\n            <path d=\"M9 12l2 2 4-4\"/>\n        </svg>\n        Selesai\n    `;
                if (status) status.classList.add('completed');
            }
            if (type === 'kedatangan' && attendanceState.kedatangan && !attendanceState.kepulangan) {
                startLiveTime('kedatangan-time');
            }
        });

        const remaining = Math.max(0, BREAK_QUOTA_MS - breakUsedMs - (breakStartAt ? (Date.now() - breakStartAt) : 0));
        const sessionElapsed = breakStartAt ? (Date.now() - breakStartAt) : 0;
        updateBreakDisplays(remaining, sessionElapsed);

        // Buttons availability
        if (!attendanceState.kedatangan) {
            document.getElementById('btn-istirahat').disabled = true;
            document.getElementById('btn-kembali').disabled = true;
            document.getElementById('btn-kepulangan').disabled = true;
        } else {
            const btnIst = document.getElementById('btn-istirahat');
            if (btnIst) btnIst.disabled = breakStartAt ? true : (remaining <= 0);
            const btnKmb = document.getElementById('btn-kembali');
            if (btnKmb) btnKmb.disabled = !breakStartAt;
        }

        // Kedatangan clock rules: before check-in run; after check-in keep running until checkout
        if (!attendanceState.kedatangan) {
            startLiveTime('kedatangan-time');
        } else if (attendanceState.kedatangan && !attendanceState.kepulangan) {
            startLiveTime('kedatangan-time');
        }
        if (breakStartAt) {
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
    } catch {}
}

function enableNextButton(currentType) {
    switch(currentType) {
        case 'kedatangan':
            document.getElementById('btn-istirahat').disabled = false;
            break;
        case 'istirahat':
            document.getElementById('btn-kembali').disabled = false;
            break;
        case 'kembali':
            document.getElementById('btn-kepulangan').disabled = false;
            break;
    }
}

function showSuccessModal(type, time) {
    const modal = document.getElementById('successModal');
    const message = document.getElementById('modal-message');
    const recordedTime = document.getElementById('recorded-time');
    const modalLocation = document.getElementById('modal-location');
    const recordedLocation = document.getElementById('recorded-location');

    const messages = {
        kedatangan: 'Presensi kedatangan berhasil dicatat.',
        istirahat: 'Presensi istirahat berhasil dicatat.',
        kembali: 'Presensi kembali dari istirahat berhasil dicatat.',
        kepulangan: 'Presensi kepulangan berhasil dicatat.'
    };

    message.textContent = messages[type];
    recordedTime.textContent = time;

    if (currentLocation) {
        modalLocation.style.display = 'block';
        recordedLocation.textContent = currentLocation;
    }

    modal.style.display = 'flex';
}

function closeModal() {
    document.getElementById('successModal').style.display = 'none';
}

// Camera Functions
let cameraStream = null;
let currentZoom = 1;

function startCamera() {
    const cameraArea = document.getElementById('cameraArea');
    const cameraPreview = document.getElementById('cameraPreview');
    const video = document.getElementById('cameraVideo');
    const photoPreview = document.getElementById('photoPreview');
    const cameraBtn = document.getElementById('cameraBtn');

    // Hide camera area and show preview
    cameraArea.style.display = 'none';
    cameraPreview.style.display = 'block';
    // Hide previous photo preview if any (retake flow)
    if (photoPreview) photoPreview.style.display = 'none';
    // Set main button back to 'Ambil Foto'
    if (cameraBtn) {
        cameraBtn.innerHTML = `
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                <circle cx="12" cy="13" r="4"/>
            </svg>
            Ambil Foto
        `;
        cameraBtn.classList.remove('completed');
    }

    // Access camera with wider field of view and better quality
    const constraints = {
        video: {
            facingMode: 'environment', // Use back camera
            width: { ideal: 1920, min: 1280 },
            height: { ideal: 1080, min: 720 },
            aspectRatio: { ideal: 16/9 },
            frameRate: { ideal: 30, min: 15 }
        }
    };

    // Try with high resolution first, fallback to lower if not supported
    navigator.mediaDevices.getUserMedia(constraints)
    .then(function(stream) {
        cameraStream = stream;
        video.srcObject = stream;

        // Get actual video track settings
        const videoTrack = stream.getVideoTracks()[0];
        const settings = videoTrack.getSettings();
        console.log('Camera settings:', settings);

        // Update video element to match actual resolution
        video.addEventListener('loadedmetadata', function() {
            console.log('Video dimensions:', video.videoWidth, 'x', video.videoHeight);
        });
    })
    .catch(function(error) {
        console.error('Error accessing camera:', error);

        // Fallback to basic camera access if high resolution fails
        navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'environment'
            }
        })
        .then(function(stream) {
            cameraStream = stream;
            video.srcObject = stream;
        })
        .catch(function(fallbackError) {
            console.error('Fallback camera error:', fallbackError);
            alert('Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan dan browser mendukung akses kamera.');
            cameraArea.style.display = 'block';
            cameraPreview.style.display = 'none';
        });
    });
}

function stopCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }

    document.getElementById('cameraArea').style.display = 'block';
    document.getElementById('cameraPreview').style.display = 'none';
}

function capturePhoto() {
    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('captureCanvas');
    const context = canvas.getContext('2d');

    // Set canvas dimensions to match video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    // Draw video frame to canvas
    context.drawImage(video, 0, 0);

    // Convert to blob
    canvas.toBlob(function(blob) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            const previewImage = document.getElementById('previewImage');
            const timeText = document.getElementById('timeText');
            const cameraStatus = document.getElementById('cameraStatus');
            const cameraBtn = document.getElementById('cameraBtn');

            previewImage.src = e.target.result;
            timeText.textContent = getCurrentTime() + ' - ' + new Date().toLocaleDateString('id-ID');
            // Move preview INTO the camera box, positioned above the action button
            try {
                const cameraCard = document.querySelector('.camera-section .camera-card');
                const cameraBtnEl = document.getElementById('cameraBtn');
                if (cameraCard && cameraBtnEl && preview && preview.parentElement !== cameraCard) {
                    cameraCard.insertBefore(preview, cameraBtnEl);
                }
            } catch {}
            preview.style.display = 'block';

            // Update camera status
            cameraStatus.innerHTML = '<span class="status-indicator completed">Foto Berhasil Diambil</span>';
            // Ganti fungsi tombol utama menjadi 'Ambil Ulang'
            cameraBtn.innerHTML = `
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="1 4 1 10 7 10"/>
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                </svg>
                Ambil Ulang
            `;
            cameraBtn.classList.remove('completed');

            uploadedPhoto = blob;
            getCurrentLocation();
            enableAttendanceButtons();
            tryUploadProofIfReady();

            // Stop camera and keep camera area hidden so preview occupies the box
            stopCamera();
            document.getElementById('cameraArea').style.display = 'none';
        };
        reader.readAsDataURL(blob);
    }, 'image/jpeg', 0.8);
}

function adjustZoom(delta) {
    if (!cameraStream) return;

    const videoTrack = cameraStream.getVideoTracks()[0];
    const capabilities = videoTrack.getCapabilities();

    if (capabilities.zoom) {
        currentZoom = Math.max(capabilities.zoom.min,
                      Math.min(capabilities.zoom.max, currentZoom + delta));

        videoTrack.applyConstraints({
            advanced: [{ zoom: currentZoom }]
        }).catch(error => {
            console.log('Zoom not supported:', error);
        });
    } else {
        // Fallback: CSS transform zoom
        const video = document.getElementById('cameraVideo');
        currentZoom = Math.max(0.5, Math.min(3, currentZoom + delta));
        video.style.transform = `scaleX(-1) scale(${currentZoom})`;
    }
}

function enableAttendanceButtons() {
    // Enable the first attendance button (kedatangan) after photo is taken
    document.getElementById('btn-kedatangan').disabled = false;
}

// Geolocation Functions
function getCurrentLocation() {
    const locationText = document.getElementById('locationText');
    const locationInput = document.getElementById('locationInput');

    if (navigator.geolocation) {
        locationText.textContent = 'Mendeteksi lokasi...';
        locationInput.value = 'Mendeteksi lokasi...';

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const acc = position.coords.accuracy;
                currentLat = lat;
                currentLng = lng;
                currentAccuracy = acc;

                // Reverse geocode via backend (Google Maps)
                fetch(`{{ route('reverse.geocode') }}?lat=${lat}&lng=${lng}`)
                    .then(r => r.ok ? r.json() : Promise.reject())
                    .then(data => {
                        const address = (data && data.address) ? data.address : `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                        currentLocation = address;
                        locationText.textContent = address;
                        locationInput.value = address;
                        tryUploadProofIfReady();
                    })
                    .catch(() => {
                        const fallbackLocation = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                        currentLocation = fallbackLocation;
                        locationText.textContent = fallbackLocation;
                        locationInput.value = fallbackLocation;
                        tryUploadProofIfReady();
                    });
            },
            function(error) {
                let errorMessage = 'Tidak dapat mendeteksi lokasi';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Akses lokasi ditolak';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Lokasi tidak tersedia';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'Timeout mendeteksi lokasi';
                        break;
                }
                locationText.textContent = errorMessage;
                locationInput.value = errorMessage;
            }
        );
    } else {
        const errorMessage = 'Geolocation tidak didukung browser';
        locationText.textContent = errorMessage;
        locationInput.value = errorMessage; 
    }
}

// Activity Functions
function saveActivity() {
    const activityInput = document.getElementById('activityInput');
    const activity = activityInput.value.trim();

    if (activity === '') {
        alert('Silakan masukkan keterangan kegiatan terlebih dahulu.');
        return;
    }

    activityDescription = activity;
    // Push metadata-only update to backend (no photo required)
    const fd = new FormData();
    fd.append('location_type', 'luar_kantor');
    fd.append('activity_text', activityDescription.trim());
    if (currentLocation) fd.append('location_text', currentLocation);
    if (currentLat != null && currentLng != null) {
        fd.append('lat', currentLat);
        fd.append('lng', currentLng);
    }
    if (currentAccuracy != null) {
        fd.append('accuracy', currentAccuracy);
    }
    postForm('{{ route('attendance.proof') }}', fd).catch(()=>{});
    alert('Keterangan kegiatan berhasil disimpan.');
}

// Auto-detect location on page load
// Fungsi untuk mendapatkan lokasi dengan timeout
function getLocationWithTimeout(timeout = 5000) {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject('Geolocation tidak didukung di browser Anda');
            return;
        }

        const options = {
            enableHighAccuracy: true,
            timeout: timeout,
            maximumAge: 0
        };

        const success = (position) => {
            currentLocation = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy
            };
            resolve(currentLocation);
        };

        const error = (err) => {
            console.warn('ERROR(' + err.code + '): ' + err.message);
            // Tetap lanjutkan meskipun gagal dapat lokasi
            resolve(null);
        };

        navigator.geolocation.getCurrentPosition(success, error, options);
    });
}

window.addEventListener('load', function() {
    // Coba dapatkan lokasi, tapi jangan blok inisialisasi UI
    getLocationWithTimeout().then(location => {
        if (location) {
            console.log('Lokasi berhasil didapatkan:', location);
            const locationInput = document.getElementById('locationInput');
            if (locationInput) {
                locationInput.placeholder = 'Lokasi terdeteksi';
            }
        }
    }).catch(err => {
        console.warn('Gagal mendapatkan lokasi:', err);
    });

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
    // Jika belum presensi kedatangan, mulai jam real-time di kartu Kedatangan
    try {
        if (!attendanceState.kedatangan) {
            startLiveTime('kedatangan-time');
        }
    } catch {}
    // Start polling notifications
    if (typeof startNotifPolling === 'function') startNotifPolling();
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('successModal');
    if (event.target === modal) {
        closeModal();
    }
});

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
        if (submenu) submenu.classList.remove('show');
        if (arrow) arrow.classList.remove('rotated');
    }
});

// ===== Notifikasi Overtime (user) =====
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
