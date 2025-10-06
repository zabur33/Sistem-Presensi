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
                            <li><a href="/presensi/kantor">Kantorrrrrrr</a></li>
                            <li><a href="/presensi/luar-kantor">Luar Kantor</a></li>
                        </ul>
                    </li>
                    <li><a href="/lembur" class="active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>Lembur</a></li>
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

                    <!-- Upload Foto Wajah -->
                    <div class="form-group">
                        <label>Upload Foto Wajah</label>
                        <div class="upload-area" id="uploadWajah">
                            <div class="upload-icon">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="M21 15l-5-5L5 21"/>
                                </svg>
                            </div>
                            <p>Pilih atau drag file foto disini</p>
                            <input type="file" id="fotoWajah" name="foto_wajah" accept="image/*" hidden>
                        </div>
                        <button type="button" class="upload-btn" onclick="document.getElementById('fotoWajah').click()">Upload</button>
                    </div>

                    <!-- Upload Foto Pendukung -->
                    <div class="form-group">
                        <label>Upload Foto Pendukung</label>
                        <div class="upload-area" id="uploadPendukung">
                            <div class="upload-icon">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="M21 15l-5-5L5 21"/>
                                </svg>
                            </div>
                            <p>Pilih atau drag file foto disini</p>
                            <input type="file" id="fotoPendukung" name="foto_pendukung" accept="image/*" hidden>
                        </div>
                        <button type="button" class="upload-btn" onclick="document.getElementById('fotoPendukung').click()">Upload</button>
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

// File upload handlers
document.getElementById('fotoWajah').addEventListener('change', function(e) {
    handleFileUpload(e, 'uploadWajah');
});

document.getElementById('fotoPendukung').addEventListener('change', function(e) {
    handleFileUpload(e, 'uploadPendukung');
});

function handleFileUpload(event, uploadAreaId) {
    const file = event.target.files[0];
    const uploadArea = document.getElementById(uploadAreaId);

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            uploadArea.innerHTML = `
                <div class="uploaded-file">
                    <img src="${e.target.result}" alt="Uploaded image" style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                    <p>${file.name}</p>
                </div>
            `;
            uploadArea.classList.add('has-file');
        };
        reader.readAsDataURL(file);
    }
}

// Drag and drop functionality
document.querySelectorAll('.upload-area').forEach(area => {
    area.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('drag-over');
    });

    area.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
    });

    area.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const fileInput = this.parentNode.querySelector('input[type="file"]');
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });

    area.addEventListener('click', function() {
        const fileInput = this.parentNode.querySelector('input[type="file"]');
        fileInput.click();
    });
});

// Form submission
document.getElementById('lemburForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validate form
    const nama = document.getElementById('nama').value;
    const alamat = document.getElementById('alamat').value;
    const jam = document.getElementById('jam').value;
    const deskripsi = document.getElementById('deskripsi').value;

    if (!nama || !alamat || !jam || !deskripsi) {
        alert('Mohon lengkapi semua field yang diperlukan');
        return;
    }

    // Here you would typically send the data to the server
    alert('Form lembur berhasil disubmit!');
});

// Reset form
function resetForm() {
    document.getElementById('lemburForm').reset();
    document.querySelectorAll('.upload-area').forEach(area => {
        area.classList.remove('has-file');
        area.innerHTML = `
            <div class="upload-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <path d="M21 15l-5-5L5 21"/>
                </svg>
            </div>
            <p>Pilih atau drag file foto disini</p>
        `;
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
