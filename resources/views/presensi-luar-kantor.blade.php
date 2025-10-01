<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Luar Kantor - Life Media</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="{{ asset('css/presensi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/presensi-luar.css') }}">
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
                <p>Presensi Luar Kantor</p>
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
                <h2>Ambil Foto Presensi</h2>
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
                        Buka Kamera
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
                    <button class="retake-btn" onclick="startCamera()">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="1 4 1 10 7 10"/>
                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                        </svg>
                        Ambil Ulang
                    </button>
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
                <h2>Tambahkan Keterangan</h2>
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

let currentLocation = null;
let uploadedPhoto = null;
let activityDescription = '';

function getCurrentTime() {
    const now = new Date();
    return now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    });
}

function handlePresensi(type) {
    // Check if photo has been taken
    if (!uploadedPhoto) {
        alert('Anda harus mengambil foto terlebih dahulu sebelum melakukan presensi!');
        return;
    }

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
    
    // Hide camera area and show preview
    cameraArea.style.display = 'none';
    cameraPreview.style.display = 'block';
    
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
            preview.style.display = 'block';
            
            // Update camera status
            cameraStatus.innerHTML = '<span class="status-indicator completed">Foto Berhasil Diambil</span>';
            cameraBtn.innerHTML = `
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4"/>
                </svg>
                Foto Tersimpan
            `;
            cameraBtn.classList.add('completed');
            
            uploadedPhoto = blob;
            getCurrentLocation(); // Auto-detect location when photo is taken
            enableAttendanceButtons(); // Enable attendance buttons after photo is taken
            
            // Stop camera and hide preview
            stopCamera();
            document.getElementById('cameraArea').style.display = 'block';
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
                
                // Use reverse geocoding to get address (simplified version)
                fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=id`)
                    .then(response => response.json())
                    .then(data => {
                        const address = data.locality + ', ' + data.city + ', ' + data.principalSubdivision;
                        currentLocation = address;
                        locationText.textContent = address;
                        locationInput.value = address;
                    })
                    .catch(error => {
                        const fallbackLocation = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                        currentLocation = fallbackLocation;
                        locationText.textContent = fallbackLocation;
                        locationInput.value = fallbackLocation;
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
    alert('Keterangan kegiatan berhasil disimpan.');
}

// Auto-detect location on page load
window.addEventListener('load', function() {
    getCurrentLocation();
    
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
        if (submenu) submenu.classList.remove('show');
        if (arrow) arrow.classList.remove('rotated');
    }
});
</script>
</body>
</html>
