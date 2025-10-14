<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembur - Life Media</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="{{ asset('css/lembur.css') }}">
    <link rel="stylesheet" href="{{ asset('css/presensi-luar.css') }}">
    <style>
        /* Page-specific tweaks so tampilan rapi di halaman lembur */
        .camera-card{ padding:12px; }
        .camera-area{ padding:20px 16px; }
        .camera-icon svg{ width:40px; height:40px; }
        .camera-preview video{ height:220px; }
        .camera-text{ font-size:1rem; }
        .retake-btn{ margin-top:10px; }
        /* Pastikan svg tidak membesar di luar container */
        .camera-section svg{ max-width:100%; max-height:100%; }
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
                    <li><a href="/lembur" class="active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>Lembur</a></li>
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
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <a href="/profile">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>
                </a>
            </div>
        </div>
        <div class="lembur-content">
            <div class="page-title">
                <h1>Lembur</h1>
            </div>

            <div class="lembur-form">
                <form id="lemburForm">
                    <!-- Nama dan Alamat Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" id="alamat" name="alamat" placeholder="https://location">
                        </div>
                        <div class="form-group">
                            <label for="jam">Jam</label>
                            <input type="time" id="jam" name="jam">
                        </div>
                    </div>

                    <!-- Foto Wajah (Kamera) -->
                    <div class="form-group">
                        <label>Foto Wajah</label>
                        <div class="camera-section">
                            <div class="camera-card">
                                <!-- Preview Kamera -->
                                <div class="camera-preview" id="cameraPreview-face" style="display:none;">
                                    <video id="cameraVideo-face" autoplay playsinline></video>
                                    <div class="camera-overlay">
                                        <div class="camera-controls">
                                            <button class="capture-btn" onclick="capturePhoto('face')" type="button" title="Ambil Foto">
                                                <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                                            </button>
                                            <button class="close-camera-btn" onclick="stopCamera('face')" type="button" title="Tutup Kamera">
                                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <canvas id="captureCanvas-face" style="display:none;"></canvas>
                                </div>
                                <!-- Area Mulai Kamera -->
                                <div class="camera-area" id="cameraArea-face">
                                    <div class="camera-icon">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1 2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                    </div>
                                    <p class="camera-text">Ambil foto wajah langsung dari kamera</p>
                                </div>
                                <button class="camera-btn" id="cameraBtn-face" onclick="startCamera('face')" type="button">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1 2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                    Buka Kamera
                                </button>
                            </div>
                            <!-- Preview Foto -->
                            <div class="photo-preview" id="photoPreview-face" style="display:none;">
                                <img id="previewImage-face" src="" alt="Preview">
                                <button class="retake-btn" onclick="startCamera('face')" type="button">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                    Ambil Ulang
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="fotoData-face" name="foto_wajah_data">
                    </div>

                    <!-- Foto Pendukung (Kamera) -->
                    <div class="form-group">
                        <label>Foto Pendukung</label>
                        <div class="camera-section">
                            <div class="camera-card">
                                <!-- Preview Kamera -->
                                <div class="camera-preview" id="cameraPreview-support" style="display:none;">
                                    <video id="cameraVideo-support" autoplay playsinline></video>
                                    <div class="camera-overlay">
                                        <div class="camera-controls">
                                            <button class="capture-btn" onclick="capturePhoto('support')" type="button" title="Ambil Foto">
                                                <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                                            </button>
                                            <button class="close-camera-btn" onclick="stopCamera('support')" type="button" title="Tutup Kamera">
                                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <canvas id="captureCanvas-support" style="display:none;"></canvas>
                                </div>
                                <!-- Area Mulai Kamera -->
                                <div class="camera-area" id="cameraArea-support">
                                    <div class="camera-icon">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1 2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                    </div>
                                    <p class="camera-text">Ambil foto pendukung langsung dari kamera</p>
                                </div>
                                <button class="camera-btn" id="cameraBtn-support" onclick="startCamera('support')" type="button">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1 2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                    Buka Kamera
                                </button>
                            </div>
                            <!-- Preview Foto -->
                            <div class="photo-preview" id="photoPreview-support" style="display:none;">
                                <img id="previewImage-support" src="" alt="Preview">
                                <button class="retake-btn" onclick="startCamera('support')" type="button">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                    Ambil Ulang
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="fotoData-support" name="foto_pendukung_data">
                    </div>

                    <!-- Deskripsi Kegiatan -->
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Kegiatan</label>
                        <textarea id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi kegiatan lembur yang akan dilakukan..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="button" class="btn-batal" onclick="resetForm()">Batal</button>
                        <button type="submit" class="btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
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

// Camera logic (dual: face & support)
let cameraStreams = { face: null, support: null };

function startCamera(key) {
    const preview = document.getElementById(`cameraPreview-${key}`);
    const area = document.getElementById(`cameraArea-${key}`);
    const video = document.getElementById(`cameraVideo-${key}`);

    area.style.display = 'none';
    preview.style.display = 'block';

    const constraints = {
        video: {
            facingMode: 'environment',
            width: { ideal: 1280 },
            height: { ideal: 720 },
            frameRate: { ideal: 30, min: 15 }
        }
    };

    navigator.mediaDevices.getUserMedia(constraints)
        .then(stream => {
            cameraStreams[key] = stream;
            video.srcObject = stream;
        })
        .catch(() => {
            alert('Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.');
            area.style.display = 'block';
            preview.style.display = 'none';
        });
}

function stopCamera(key) {
    const stream = cameraStreams[key];
    if (stream) {
        stream.getTracks().forEach(t => t.stop());
        cameraStreams[key] = null;
    }
    document.getElementById(`cameraArea-${key}`).style.display = 'block';
    document.getElementById(`cameraPreview-${key}`).style.display = 'none';
}

function capturePhoto(key) {
    const video = document.getElementById(`cameraVideo-${key}`);
    const canvas = document.getElementById(`captureCanvas-${key}`);
    const ctx = canvas.getContext('2d');

    canvas.width = video.videoWidth || 1280;
    canvas.height = video.videoHeight || 720;
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
    document.getElementById(`previewImage-${key}`).src = dataUrl;
    document.getElementById(`photoPreview-${key}`).style.display = 'block';
    document.getElementById(`fotoData-${key}`).value = dataUrl;

    // stop camera after capture
    stopCamera(key);
}

// Form submission
document.getElementById('lemburForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validate form
    const nama = document.getElementById('nama').value;
    const alamat = document.getElementById('alamat').value;
    const jam = document.getElementById('jam').value;
    const deskripsi = document.getElementById('deskripsi').value;
    const faceData = document.getElementById('fotoData-face').value;
    const supportData = document.getElementById('fotoData-support').value;

    if (!nama || !alamat || !jam || !deskripsi) {
        alert('Mohon lengkapi semua field yang diperlukan');
        return;
    }

    if (!faceData) {
        alert('Mohon ambil foto wajah terlebih dahulu.');
        return;
    }

    if (!supportData) {
        alert('Mohon ambil foto pendukung terlebih dahulu.');
        return;
    }

    // Here you would typically send the data to the server
    alert('Form lembur berhasil disubmit!');
});

// Reset form
function resetForm() {
    document.getElementById('lemburForm').reset();
    ['face','support'].forEach(key => {
        document.getElementById(`fotoData-${key}`).value = '';
        document.getElementById(`photoPreview-${key}`).style.display = 'none';
        stopCamera(key);
        document.getElementById(`cameraArea-${key}`).style.display = 'block';
        document.getElementById(`cameraPreview-${key}`).style.display = 'none';
    });
}

// Initialize page
window.addEventListener('load', function() {
    // Set current time as default
    const now = new Date();
    const timeString = now.toTimeString().slice(0, 5);
    document.getElementById('jam').value = timeString;
});
</script>
</body>
</html>
