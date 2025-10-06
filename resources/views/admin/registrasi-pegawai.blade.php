@extends('admin.layouts.app')

@section('content')
<div class="page-title" style="margin-bottom:16px;">
    <h1 style="font-weight:800; font-size:28px;">Mohon di isi data di bawah ini!</h1>
    <p style="color:#7a7a7a; margin-top:6px;">Lengkapi data pegawai untuk membuat akun baru.</p>
    </div>

<div class="card" style="background:#f4e9e4; border:1px solid #d9c7bf; border-radius:18px; padding:24px; box-shadow:0 1px 0 rgba(0,0,0,0.02);">
    <form method="POST" action="#" style="display:grid; grid-template-columns: 1fr 1fr; gap:18px;">
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
            <input class="form-input" type="email" name="email" placeholder="email@domain.com" required>
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
            <input class="form-input" type="password" name="password" placeholder="Password awal" required>
        </div>
        <div>
            <label class="form-label">Konfirmasi Password</label>
            <input class="form-input" type="password" name="password_confirmation" placeholder="Ulangi password" required>
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
@endsection
