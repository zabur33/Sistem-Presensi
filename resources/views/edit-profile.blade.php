<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Life Media</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
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
        <div class="profile-content">
            <div class="page-title">
                <h1>Edit Profile</h1>
            </div>

            <div class="profile-container">
                <!-- User Information Form -->
                <div class="profile-form-section">
                    <div class="section-header">
                        <h2>User Information</h2>
                    </div>

                    <form id="editProfileForm" class="profile-form">
                        <div class="form-group">
                            <label for="name">Name*</label>
                            <input type="text" id="name" name="name" value="Ilham Wahyudi" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address*</label>
                            <input type="email" id="email" name="email" value="ilham.wahyudi@lifemedia.com" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Enter new password (leave blank to keep current)">
                        </div>

                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="+62 812-3456-7890" placeholder="Enter phone number">
                        </div>

                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" id="position" name="position" value="Staff IT" placeholder="Enter position">
                        </div>

                        <div class="forgot-password">
                            <a href="#" onclick="showForgotPasswordModal()">Forgot Password?</a>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-cancel" onclick="cancelEdit()">Cancel</button>
                            <button type="submit" class="btn-save">Save</button>
                        </div>
                    </form>
                </div>

                <!-- Profile Picture Section -->
                <div class="profile-picture-section">
                    <div class="profile-picture-container">
                        <div class="profile-picture editable">
                            <img src="{{ asset('images/profile-placeholder.jpg') }}" alt="Profile Picture" id="profileImage">
                            <div class="upload-overlay">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                                <span>Change Photo</span>
                            </div>
                            <input type="file" id="profileImageInput" accept="image/*" style="display: none;">
                        </div>
                        <div class="profile-info">
                            <h3 id="profileName">Ilham Wahyudi</h3>
                            <p id="profileRole">Staff IT</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div id="forgotPasswordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reset Password</h3>
            <span class="close" onclick="closeForgotPasswordModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Enter your email address and we'll send you a link to reset your password.</p>
            <div class="form-group">
                <label for="resetEmail">Email Address</label>
                <input type="email" id="resetEmail" placeholder="Enter your email">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeForgotPasswordModal()">Cancel</button>
            <button class="btn-primary" onclick="sendResetLink()">Send Reset Link</button>
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
            <h3>Profile Updated!</h3>
        </div>
        <div class="modal-body">
            <p>Your profile information has been successfully updated.</p>
        </div>
        <button class="modal-btn" onclick="redirectToProfile()">OK</button>
    </div>
</div>

<!-- Confirm Cancel Modal -->
<div id="confirmCancelModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Discard Changes?</h3>
            <span class="close" onclick="closeConfirmCancelModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to discard your changes? All unsaved changes will be lost.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeConfirmCancelModal()">Keep Editing</button>
            <button class="btn-primary" onclick="confirmCancel()">Discard Changes</button>
        </div>
    </div>
</div>

<script>
// Store original values for comparison
let originalValues = {
    name: 'Ilham Wahyudi',
    email: 'ilham.wahyudi@lifemedia.com',
    phone: '+62 812-3456-7890',
    position: 'Staff IT'
};

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

// Profile image upload
document.getElementById('profileImageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profileImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Profile picture click handler
document.querySelector('.profile-picture').addEventListener('click', function() {
    document.getElementById('profileImageInput').click();
});

// Check if form has changes
function hasChanges() {
    const currentValues = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        position: document.getElementById('position').value,
        password: document.getElementById('password').value,
        confirmPassword: document.getElementById('confirmPassword').value
    };

    return (
        currentValues.name !== originalValues.name ||
        currentValues.email !== originalValues.email ||
        currentValues.phone !== originalValues.phone ||
        currentValues.position !== originalValues.position ||
        currentValues.password !== '' ||
        currentValues.confirmPassword !== ''
    );
}

// Cancel edit with confirmation if changes exist
function cancelEdit() {
    if (hasChanges()) {
        document.getElementById('confirmCancelModal').style.display = 'flex';
    } else {
        window.location.href = '/profile';
    }
}

// Confirm cancel modal functions
function closeConfirmCancelModal() {
    document.getElementById('confirmCancelModal').style.display = 'none';
}

function confirmCancel() {
    window.location.href = '/profile';
}

// Form validation
function validateForm() {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (!name) {
        alert('Name is required');
        return false;
    }

    if (!email) {
        alert('Email is required');
        return false;
    }

    if (!isValidEmail(email)) {
        alert('Please enter a valid email address');
        return false;
    }

    if (password && password !== confirmPassword) {
        alert('Password and confirm password do not match');
        return false;
    }

    if (password && password.length < 6) {
        alert('Password must be at least 6 characters long');
        return false;
    }

    return true;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Save profile
function saveProfile() {
    if (!validateForm()) {
        return;
    }

    const formData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        position: document.getElementById('position').value,
        password: document.getElementById('password').value
    };

    // Update profile display
    document.getElementById('profileName').textContent = formData.name;
    document.getElementById('profileRole').textContent = formData.position;

    // Show success modal
    document.getElementById('successModal').style.display = 'flex';
}

// Redirect to profile page after success
function redirectToProfile() {
    window.location.href = '/profile';
}

// Forgot password modal
function showForgotPasswordModal() {
    document.getElementById('forgotPasswordModal').style.display = 'flex';
}

function closeForgotPasswordModal() {
    document.getElementById('forgotPasswordModal').style.display = 'none';
    document.getElementById('resetEmail').value = '';
}

function sendResetLink() {
    const email = document.getElementById('resetEmail').value;
    if (!email) {
        alert('Please enter your email address');
        return;
    }

    if (!isValidEmail(email)) {
        alert('Please enter a valid email address');
        return;
    }

    // Here you would typically send the reset link
    alert('Password reset link has been sent to your email');
    closeForgotPasswordModal();
}

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const forgotModal = document.getElementById('forgotPasswordModal');
    const successModal = document.getElementById('successModal');
    const confirmModal = document.getElementById('confirmCancelModal');

    if (event.target === forgotModal) {
        closeForgotPasswordModal();
    }
    if (event.target === successModal) {
        redirectToProfile();
    }
    if (event.target === confirmModal) {
        closeConfirmCancelModal();
    }
});

// Form submission
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    saveProfile();
});

// Warn user before leaving page if there are unsaved changes
window.addEventListener('beforeunload', function(e) {
    if (hasChanges()) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
</body>
</html>
