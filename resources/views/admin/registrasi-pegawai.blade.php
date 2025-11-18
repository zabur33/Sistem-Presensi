@extends('admin.layouts.app')

@section('content')
<div class="page-title" style="margin-bottom:16px;">
    <h1 style="font-weight:800; font-size:28px;">Mohon di isi data di bawah ini!</h1>
    <p style="color:#7a7a7a; margin-top:6px;">Lengkapi data pegawai untuk membuat akun baru.</p>
</div>

@if(session('success'))
    <div id="success-message" data-message="{{ session('success') }}"></div>
@endif

<div class="card" style="background:#f4e9e4; border:1px solid #d9c7bf; border-radius:18px; padding:24px; box-shadow:0 1px 0 rgba(0,0,0,0.02);">
    <form id="registrationForm" method="POST" action="{{ route('admin.registrasi-pegawai.store') }}" style="display:grid; grid-template-columns: 1fr 1fr; gap:18px;" autocomplete="off" onsubmit="return handleFormSubmit(event)">
        @csrf

        <div>
            <label class="form-label">Nama</label>
            <input class="form-input" type="text" name="name" placeholder="Nama lengkap" required>
        </div>
        <div>
            <label class="form-label">Nomor Induk Pegawai</label>
            <input class="form-input" type="text" name="nip" placeholder="NIP">
        </div>

        <div>
            <label class="form-label">Email</label>
            <input class="form-input" type="email" name="email" placeholder="email@domain.com" required autocomplete="new-email">
        </div>
        <div>
            <label class="form-label">Jenis Kelamin</label>
            <select class="form-input" name="gender">
                <option value="">Pilih</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>

        <div>
            <label class="form-label">Tanggal Lahir</label>
            <input class="form-input" type="date" name="birth_date">
        </div>
        <div>
            <label class="form-label">Alamat</label>
            <input class="form-input" type="text" name="address" placeholder="Alamat lengkap">
        </div>

        <div>
            <label class="form-label">Jabatan</label>
            <input class="form-input" type="text" name="position" placeholder="Contoh: Staff IT">
        </div>
        <div>
            <label class="form-label">No. Telepon</label>
            <input class="form-input" type="text" name="phone" placeholder="08xxxxxxxxxx">
        </div>

        <div style="grid-column: span 2;">
            <label class="form-label">Divisi</label>
            <input class="form-input" type="text" name="division" placeholder="Contoh: Teknologi Informasi">
        </div>

        <div>
            <label class="form-label">Password Awal</label>
            <input class="form-input" type="password" name="password" placeholder="Password awal" required autocomplete="new-password">
        </div>
        <div>
            <label class="form-label">Konfirmasi Password</label>
            <input class="form-input" type="password" name="password_confirmation" placeholder="Ulangi password" required autocomplete="new-password">
        </div>

        <div style="grid-column: span 2; display:flex; align-items:center; gap:10px; margin-top:6px;">
            <input id="is_admin" type="checkbox" name="is_admin" value="1">
            <label for="is_admin" style="user-select:none;">Jadikan Admin</label>
        </div>

        <div style="grid-column: span 2; display:flex; justify-content:center; margin-top:8px;">
            <button type="submit" class="btn-primary" style="background:#b34555; color:#fff; border:none; padding:12px 26px; border-radius:10px; font-weight:700;">Daftar</button>
        </div>
    </form>
</div>

<style>
    .form-label { display:block; margin-bottom:6px; font-weight:600; color:#5b4e48; }
    .form-input { width:100%; background:#fff; border:1px solid #d9c7bf; border-radius:10px; padding:10px 12px; outline:none; box-shadow:inset 0 1px 0 rgba(0,0,0,0.02); }
    .form-input:focus { border-color:#e07a5f; box-shadow:0 0 0 3px rgba(224,122,95,0.15); }
    @media (max-width: 900px){
        .card form{ grid-template-columns:1fr; }
    }
</style>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fungsi untuk menangani submit form
    async function handleFormSubmit(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                // Tampilkan popup sukses
                await Swal.fire({
                    title: 'Berhasil!',
                    text: data.message || 'Pegawai berhasil diregistrasi!',
                    icon: 'success',
                    confirmButtonColor: '#b34555',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: true
                });

                // Reset form
                form.reset();

                // Reset file inputs
                document.querySelectorAll('input[type="file"]').forEach(input => {
                    input.value = '';
                });

                // Reset select elements
                document.querySelectorAll('select').forEach(select => {
                    select.selectedIndex = 0;
                });

                // Reset checkboxes
                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Hapus pesan error jika ada
                const errorElements = document.querySelectorAll('.error-message');
                errorElements.forEach(el => el.remove());

            } else {
                // Tampilkan pesan error validasi
                let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                if (data.errors) {
                    errorMessage = Object.values(data.errors).join('\n');
                } else if (data.message) {
                    errorMessage = data.message;
                }

                await Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonColor: '#b34555',
                    confirmButtonText: 'OK'
                });
            }

        } catch (error) {
            console.error('Error:', error);
            await Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan. Silakan coba lagi.',
                icon: 'error',
                confirmButtonColor: '#b34555',
                confirmButtonText: 'OK'
            });
        }
    }

    // Tetap pertahankan kode untuk menampilkan pesan sukses dari redirect
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            const message = successMessage.getAttribute('data-message');

            Swal.fire({
                title: 'Berhasil!',
                text: message,
                icon: 'success',
                confirmButtonColor: '#b34555',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: true
            });
        }
    });
</script>
@endpush
