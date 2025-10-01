<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Dalam Kantor - Life Media</title>
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
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7"/><path d="M3 21V3a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v4"/></svg>
            Logout
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
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <a href="/profile">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>
                </a>
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
                        <h3>Kembali</h3>
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

function getCurrentTime() {
    const now = new Date();
    return now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    });
}

function handlePresensi(type) {
    const currentTime = getCurrentTime();
    const button = document.getElementById(`btn-${type}`);
    const statusBadge = document.getElementById(`${type}-status`);
    const timeDisplay = document.getElementById(`${type}-time`);

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

    statusBadge.textContent = 'Presensi';
    statusBadge.classList.add('completed');
    timeDisplay.textContent = currentTime;

    // Enable next button in sequence
    enableNextButton(type);

    // Show success modal
    showSuccessModal(type, currentTime);
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
    const problemText = document.getElementById('problem-text').value;
    if (problemText.trim() === '' || problemText === 'Istirahat') {
        alert('Silakan masukkan deskripsi masalah terlebih dahulu.');
        return;
    }

    // Here you would typically send the report to the server
    alert('Laporan berhasil dikirim. Tim IT akan segera menindaklanjuti.');
    document.getElementById('problem-text').value = 'Istirahat';
}

// Make problem textarea editable when clicked
document.getElementById('problem-text').addEventListener('focus', function() {
    if (this.value === 'Istirahat') {
        this.value = '';
    }
    this.readOnly = false;
});

document.getElementById('problem-text').addEventListener('blur', function() {
    if (this.value.trim() === '') {
        this.value = 'Istirahat';
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
        button.style.cursor = button.disabled ? 'not-allowed' : 'pointer';
    });
    
    // Force enable dropdown link
    const dropdownLink = document.querySelector('.presensi-menu > a');
    if (dropdownLink) {
        dropdownLink.style.pointerEvents = 'auto';
        dropdownLink.style.cursor = 'pointer';
    }
});
</script>
</body>
</html>
