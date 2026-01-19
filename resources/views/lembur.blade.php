<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembur - Life Media</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .required-asterisk{ color:#e11d48; }
        .status-alert{display:none;margin-top:14px;padding:14px;border-radius:14px;background:#fff7ed;border:1px solid #fdba74;color:#b45309;font-weight:600;align-items:flex-start;gap:12px;}
        .status-alert svg{flex-shrink:0;color:#f97316;width:28px;height:28px;}
        .status-alert .status-desc{font-size:0.9rem;font-weight:500;color:#92400e;margin-top:4px;}
        .status-alert.show{display:flex;}
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
                        <span id="notifBadge" style="position:absolute;top:-4px;right:-4px;background:#e11d48;color:#fff;border-radius:999px;padding:0 6px;font-size:10px;line-height:18px;height:18px;display:none;">0</span>
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
                                <li><a href="/lembur" class="active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>Lembur</a></li>
                                <li><a href="/rekap-keseluruhan"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/></svg>Rekap Keseluruhan</a></li>
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
                </div>
            </div>
        </div>
        <div class="lembur-content">
            <div class="page-title">
                <h1>Lembur</h1>
            </div>
            <div id="pendingStatusBox" class="status-alert" role="status" aria-live="polite">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 6v6l4 2"/>
                </svg>
                <div>
                    <div>Pengajuan lembur sedang menunggu persetujuan</div>
                    <div class="status-desc">Admin akan meninjau permintaan Anda. Status akan diperbarui setelah ada keputusan.</div>
                </div>
            </div>
            <div class="lembur-form">
                <form id="lemburForm">
                    <!-- Nama dan Alamat Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text"
                                   id="nama"
                                   name="nama"
                                   placeholder="Masukkan nama lengkap"
                                   value="{{ auth()->user()->name ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label for="alamat" style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
                                <span>Alamat</span>
                            </label>
                            <input type="text" id="alamat" name="alamat" placeholder="Lokasi akan terisi otomatis saat izin lokasi diberikan">
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
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                    </div>
                                    <p class="camera-text">Ambil foto wajah langsung dari kamera</p>
                                </div>
                                <button class="camera-btn" id="cameraBtn-face" onclick="startCamera('face')" type="button">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                    Ambil Foto
                                </button>
                            </div>
                            <!-- Preview Foto -->
                            <div class="photo-preview" id="photoPreview-face" style="display:none;">
                                <img id="previewImage-face" src="" alt="Preview">
                            </div>
                        </div>
                        <input type="hidden" id="fotoData-face" name="foto_wajah_data">
                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">
                    </div>

                    <!-- Foto Pendukung (Kamera / Upload) -->
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
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                    </div>
                                    <p class="camera-text">Ambil foto pendukung langsung dari kamera</p>
                                </div>
                                <button class="camera-btn" id="cameraBtn-support" onclick="startCamera('support')" type="button">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                    Ambil Foto
                                </button>
                            </div>
                            <!-- Preview Foto -->
                            <div class="photo-preview" id="photoPreview-support" style="display:none;">
                                <img id="previewImage-support" src="" alt="Preview">
                            </div>
                        </div>
                        <input type="hidden" id="fotoData-support" name="foto_pendukung_data">
                        <div style="margin-top:10px">
                            <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:6px;">Atau unggah file (opsional, jika kamera bermasalah)</label>
                            <input type="file" id="supportFile" accept="image/*">
                        </div>
                    </div>

                    <!-- Deskripsi Kegiatan -->
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Kegiatan <span class="required-asterisk" aria-hidden="true">*</span></label>
                        <textarea id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi kegiatan lembur yang akan dilakukan..." required aria-required="true"></textarea>
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
// Dropdown functionality that works for desktop + drawer
function togglePresensiDropdown(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    const trigger = event ? (event.currentTarget || event.target) : null;
    const rootMenu = trigger ? trigger.closest('.presensi-menu') : document.querySelector('.presensi-menu');
    if (!rootMenu) return false;

    const submenu = rootMenu.querySelector('.submenu');
    const arrow = rootMenu.querySelector('.dropdown-arrow');
    if (!submenu) return false;

    const isVisible = submenu.classList.contains('show');
    submenu.classList.toggle('show', !isVisible);
    if (arrow) arrow.classList.toggle('rotated', !isVisible);

    try {
        const storageKey = rootMenu.closest('.mobile-drawer') ? 'presensi-dropdown-collapsed-mobile' : 'presensi-dropdown-collapsed';
        localStorage.setItem(storageKey, (!isVisible).toString());
    } catch (e) {}

    return false;
}

// Dapatkan lokasi dengan timeout agar tidak menggantung UI
function getLocationWithTimeout(timeout = 7000){
    return new Promise((resolve) => {
        if (!('geolocation' in navigator)) { return resolve({ ok:false, reason:'not_supported' }); }
        let done = false;
        const timer = setTimeout(()=>{ if(!done){ done=true; resolve({ ok:false, reason:'timeout' }); } }, timeout);
        navigator.geolocation.getCurrentPosition((pos)=>{
            if(done) return; done=true; clearTimeout(timer);
            resolve({ ok:true, coords: pos.coords });
        }, (err)=>{
            if(done) return; done=true; clearTimeout(timer);
            resolve({ ok:false, reason: err && err.code });
        }, { enableHighAccuracy:true, timeout, maximumAge:0 });
    });
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    document.querySelectorAll('.presensi-menu').forEach(menu => {
        if (!menu.contains(event.target)) {
            const submenu = menu.querySelector('.submenu');
            const arrow = menu.querySelector('.dropdown-arrow');
            if (submenu) submenu.classList.remove('show');
            if (arrow) arrow.classList.remove('rotated');
        }
    });
});

// Camera logic (dual: face & support)
let cameraStreams = { face: null, support: null };
let currentAddress = '';

async function fetchAddressForLembur(lat, lng){
    try{
        const res = await fetch(`{{ route('reverse.geocode') }}?lat=${lat}&lng=${lng}`);
        const data = await res.json().catch(()=> ({}));
        const address = (data && data.address) ? data.address : '';
        currentAddress = address;
        const alamatInput = document.getElementById('alamat');
        if (alamatInput){
            if (address && address.trim().length>0){
                alamatInput.value = address;
            } else {
                alamatInput.value = `${parseFloat(lat).toFixed(6)}, ${parseFloat(lng).toFixed(6)}`;
            }
        }
    }catch(e){
        currentAddress = '';
        const alamatInput = document.getElementById('alamat');
        if (alamatInput && lat!=null && lng!=null){
            alamatInput.value = `${parseFloat(lat).toFixed(6)}, ${parseFloat(lng).toFixed(6)}`;
        }
    }
}

// Start camera with geolocation
function startCamera(key) {
    const video = document.getElementById(`cameraVideo-${key}`);
    const cameraArea = document.getElementById(`cameraArea-${key}`);
    const cameraPreview = document.getElementById(`cameraPreview-${key}`);
    const photoPreview = document.getElementById(`photoPreview-${key}`);
    const cameraBtn = document.getElementById(`cameraBtn-${key}`);

    // Hide photo preview and show camera
    photoPreview.style.display = 'none';
    cameraArea.style.display = 'none';
    cameraPreview.style.display = 'block';

    // Kembalikan tombol utama menjadi "Ambil Foto"
    if (cameraBtn) {
        cameraBtn.innerHTML = `
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            Ambil Foto
        `;
        cameraBtn.classList.remove('completed');
    }

    // Request geolocation permission first
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                // Save coordinates to form
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
                console.log('Location access granted:', position.coords);
                // Fetch human-readable address and prefill alamat field
                fetchAddressForLembur(position.coords.latitude, position.coords.longitude);

                // After getting location, access camera
                accessCamera(key);
            },
            (error) => {
                console.error('Error getting location:', error);
                alert('Tidak dapat mengakses lokasi. Beberapa fitur mungkin tidak berfungsi dengan baik.');
                // Continue with camera access even if location is denied
                // Try to use any existing lat/lng captured earlier
                const latVal = document.getElementById('latitude').value;
                const lngVal = document.getElementById('longitude').value;
                if (latVal && lngVal){ fetchAddressForLembur(latVal, lngVal); }
                accessCamera(key);
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
    } else {
        console.log('Geolocation is not supported by this browser');
        accessCamera(key);
    }
}

// Access camera function
function accessCamera(key) {
    const video = document.getElementById(`cameraVideo-${key}`);

    navigator.mediaDevices.getUserMedia({
        video: {
            width: { ideal: 1280 },
            height: { ideal: 720 },
            facingMode: key === 'face' ? 'user' : 'environment'
        }
    })
    .then(stream => {
        video.srcObject = stream;
        video.play();
        // Simpan stream agar bisa dihentikan dengan benar
        cameraStreams[key] = stream;
    })
    .catch(err => {
        console.error('Error accessing camera:', err);
        alert('Tidak dapat mengakses kamera. Pastikan Anda telah memberikan izin akses kamera.');
        stopCamera(key);
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

// Capture photo from camera with geolocation
function capturePhoto(key) {
    const video = document.getElementById(`cameraVideo-${key}`);
    const canvas = document.getElementById(`captureCanvas-${key}`);
    const ctx = canvas.getContext('2d');

    canvas.width = video.videoWidth || 1280;
    canvas.height = video.videoHeight || 720;
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Create a new canvas to add location info
    const finalCanvas = document.createElement('canvas');
    finalCanvas.width = canvas.width;
    finalCanvas.height = canvas.height + 30; // Extra space for location text
    const finalCtx = finalCanvas.getContext('2d');

    // Draw the original image
    finalCtx.drawImage(canvas, 0, 0);

    // Add location info at the bottom
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;

    if (lat && lng) {
        const text = currentAddress && currentAddress.trim().length > 0
            ? `Lokasi: ${currentAddress}`
            : `Lokasi: ${parseFloat(lat).toFixed(6)}, ${parseFloat(lng).toFixed(6)}`;
        const barH = 34;
        finalCtx.fillStyle = 'rgba(0, 0, 0, 0.7)';
        finalCtx.fillRect(0, canvas.height, canvas.width, barH);
        finalCtx.font = '14px Arial';
        finalCtx.fillStyle = 'white';
        finalCtx.textAlign = 'left';
        // truncate to fit roughly within width
        const maxChars = Math.floor(canvas.width / 10);
        const drawText = text.length > maxChars ? text.slice(0, maxChars - 3) + '...' : text;
        finalCtx.fillText(drawText, 10, canvas.height + 22);
    }

    // Convert to data URL
    const dataUrl = finalCanvas.toDataURL('image/jpeg', 0.85);

    // Update preview and form data
    document.getElementById(`previewImage-${key}`).src = dataUrl;
    const previewEl = document.getElementById(`photoPreview-${key}`);
    // Move preview into the camera card above the action button
    try {
        const cameraBtn = document.getElementById(`cameraBtn-${key}`);
        const cameraCard = cameraBtn ? cameraBtn.closest('.camera-card') : null;
        if (cameraCard && previewEl && previewEl.parentElement !== cameraCard) {
            cameraCard.insertBefore(previewEl, cameraBtn);
        }
    } catch {}
    previewEl.style.display = 'block';
    document.getElementById(`fotoData-${key}`).value = dataUrl;
    // Pastikan input alamat terisi bila masih kosong
    const alamatInput = document.getElementById('alamat');
    if (alamatInput && (!alamatInput.value || alamatInput.value.trim()==='')){
        const latVal = document.getElementById('latitude').value;
        const lngVal = document.getElementById('longitude').value;
        if (currentAddress && currentAddress.trim().length>0){
            alamatInput.value = currentAddress;
        } else if (latVal && lngVal){
            alamatInput.value = `${parseFloat(latVal).toFixed(6)}, ${parseFloat(lngVal).toFixed(6)}`;
        }
    }

    // Ubah tombol utama menjadi "Ambil Ulang"
    const cameraBtn = document.getElementById(`cameraBtn-${key}`);
    if (cameraBtn) {
        cameraBtn.innerHTML = `
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
            Ambil Ulang
        `;
        cameraBtn.classList.remove('completed');
    }

    // stop camera after capture, but keep camera area hidden so preview fills the box
    stopCamera(key);
    const cameraArea = document.getElementById(`cameraArea-${key}`);
    if (cameraArea) cameraArea.style.display = 'none';
}

// CSRF helper
function getCsrfToken(){ const el=document.querySelector('meta[name="csrf-token"]'); return el?el.getAttribute('content'):''; }

// Form submission to backend
document.getElementById('lemburForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validate form
    const nama = document.getElementById('nama').value.trim();
    const alamat = document.getElementById('alamat').value.trim();
    const jam = document.getElementById('jam').value;
    const deskripsi = document.getElementById('deskripsi').value;
    const faceData = document.getElementById('fotoData-face').value;
    const supportData = document.getElementById('fotoData-support').value;

    // Validasi field yang wajib diisi
    if (!nama) {
        alert('Mohon isi Nama Anda.');
        document.getElementById('nama').focus();
        return;
    }

    if (!alamat) {
        alert('Mohon isi Alamat Anda.');
        document.getElementById('alamat').focus();
        return;
    }

    if (!jam || !deskripsi) {
        alert('Mohon isi Jam dan Deskripsi Kegiatan.');
        return;
    }

    if (!faceData) {
        alert('Mohon ambil foto wajah terlebih dahulu.');
        return;
    }

    const supportFile = document.getElementById('supportFile').files[0];
    if (!supportData && !supportFile) {
        alert('Mohon ambil atau unggah foto pendukung terlebih dahulu.');
        return;
    }

    // supportFile dideklarasikan di atas
    let requestInit;
    if (supportFile) {
        const fd = new FormData();
        fd.append('nama', nama||'');
        fd.append('alamat', alamat||'');
        fd.append('jam', jam);
        fd.append('deskripsi', deskripsi);
        if (faceData) fd.append('foto_wajah_data', faceData);
        if (supportData) fd.append('foto_pendukung_data', supportData);
        fd.append('support_file', supportFile);
        requestInit = {
            method:'POST',
            credentials:'same-origin',
            headers:{ 'X-CSRF-TOKEN': getCsrfToken(), 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' },
            body: fd
        };
    } else {
        const payload = { nama, alamat, jam, deskripsi, foto_wajah_data: faceData, foto_pendukung_data: supportData };
        requestInit = {
            method:'POST',
            credentials:'same-origin',
            headers:{ 'Content-Type':'application/json', 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': getCsrfToken() },
            body: JSON.stringify(payload)
        };
    }

    fetch("{{ route('user.overtime.submit') }}", requestInit).then(async(res)=>{
        if(!res.ok){
            const txt = await res.text().catch(()=> '');
            throw new Error('Gagal submit ('+res.status+'): '+txt.slice(0,200));
        }
        return res.json();
    }).then(()=>{
        alert('Pengajuan lembur berhasil dikirim ke admin.');
        showPendingStatus();
        resetForm();
    }).catch((err)=>{
        alert('Gagal mengirim pengajuan lembur. '+ (err && err.message ? err.message : 'Pastikan Jam, Deskripsi, dan kedua foto sudah diambil.'));
    });
});

function showPendingStatus(){
    const box=document.getElementById('pendingStatusBox');
    if(box){
        box.classList.add('show');
    }
}

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

    // Prefetch lokasi agar alamat terisi otomatis lebih cepat
    getLocationWithTimeout().then(async(res)=>{
        if(res && res.ok && res.coords){
            const { latitude:lat, longitude:lng } = res.coords;
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            await fetchAddressForLembur(lat, lng);
        } else {
            // Jika gagal, beri petunjuk ringan di konsol. Browser modern butuh HTTPS/localhost.
            console.warn('Geolocation prefetch failed:', res && res.reason);
        }
    });

    // Start polling notifications
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

// ===== Notifikasi User (overtime approvals) =====
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
