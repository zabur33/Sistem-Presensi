@extends('user.layouts.app')

@push('head')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
<div class="profile-content">
    <div class="page-title">
        <h1>Edit Profile</h1>
    </div>

    <div class="profile-container">
        <div class="profile-form-section">
            <div class="section-header">
                <h2>User Information</h2>
            </div>
            <form id="editProfileForm" class="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Name*</label>
                    <input type="text" id="name" name="name" value="{{ auth()->user()->name ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address*</label>
                    <input type="email" id="email" name="email" value="{{ auth()->user()->email ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <div style="display:flex; gap:16px; align-items:center;">
                        <label style="display:flex; align-items:center; gap:6px;">
                            <input type="radio" name="gender" value="L" {{ (optional(auth()->user()->employee)->gender ?? '')==='L' ? 'checked' : '' }}> Laki-laki
                        </label>
                        <label style="display:flex; align-items:center; gap:6px;">
                            <input type="radio" name="gender" value="P" {{ (optional(auth()->user()->employee)->gender ?? '')==='P' ? 'checked' : '' }}> Perempuan
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password (leave blank to keep current)">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ optional(auth()->user()->employee)->phone ?? '' }}" placeholder="Enter phone number">
                </div>
                <div class="form-group">
                    <label for="position">Position</label>
                    <input type="text" id="position" name="position" value="{{ optional(auth()->user()->employee)->position ?? '' }}" placeholder="Enter position">
                </div>
                <div class="form-actions">
                    <a href="/profile" id="btnCancel" class="btn-cancel" role="button">Cancel</a>
                    <button type="submit" class="btn-save">Save</button>
                </div>
            </form>
        </div>
        <div class="profile-picture-section">
            <div class="profile-picture-container">
                <div class="profile-picture editable">
                    <img src="{{ optional(auth()->user()->employee)->avatar_url ? asset('storage/'.optional(auth()->user()->employee)->avatar_url) : asset('images/profile-placeholder.jpg') }}" alt="Profile Picture" id="profileImage">
                    <label class="upload-overlay" for="profileImageInput">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        <span>Change Photo</span>
                    </label>
                    <input type="file" id="profileImageInput" name="avatar" accept="image/*" style="display: none;" form="editProfileForm">
                </div>
                <div class="profile-info">
                    <h3 id="profileName">{{ auth()->user()->name ?? '' }}</h3>
                    <div id="profileRole" class="role">{{ optional(auth()->user()->employee)->position ?? '' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
// Initialize original values
let originalValues = {!! json_encode([
    'name' => auth()->user()->name ?? '',
    'email' => auth()->user()->email ?? '',
    'phone' => optional(auth()->user()->employee)->phone ?? '',
    'position' => optional(auth()->user()->employee)->position ?? ''
]) !!};

// Cancel button behavior
const btnCancel = document.getElementById('btnCancel');
if (btnCancel) {
    btnCancel.addEventListener('click', function(e){
        e.preventDefault();
        window.location.href = '/profile';
    });
}

// Basic validation
function isValidEmail(email) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }

function validateForm(){
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const pw = document.getElementById('password').value;
    const pwc = document.getElementById('password_confirmation').value;
    if(!name){ alert('Name is required'); return false; }
    if(!email || !isValidEmail(email)){ alert('Please enter a valid email'); return false; }
    if(pw && pw !== pwc){ alert('Password and confirm password do not match'); return false; }
    if(pw && pw.length < 6){ alert('Password must be at least 6 characters long'); return false; }
    return true;
}

// Image preview
const fileInput = document.getElementById('profileImageInput');
if(fileInput){
    fileInput.addEventListener('change', function(e){
        const f = e.target.files[0];
        if(!f) return;
        const reader = new FileReader();
        reader.onload = (ev)=>{ document.getElementById('profileImage').src = ev.target.result; };
        reader.readAsDataURL(f);
    });
}
</script>
