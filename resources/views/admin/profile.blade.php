@extends('admin.layouts.app')

@section('content')
<div class="page-title" style="margin-bottom:16px;">
    <h1 style="font-weight:800; font-size:28px;">Kelola Profil</h1>
    <p style="color:#7a7a7a; margin-top:6px;">Perbarui data profil dan kata sandi.</p>
    </div>

<div class="card" style="background:#f4e9e4; border:1px solid #d9c7bf; border-radius:18px; padding:24px; box-shadow:0 1px 0 rgba(0,0,0,0.02); max-width:860px;">
    <form method="POST" action="#" style="display:grid; grid-template-columns: 1fr 1fr; gap:18px;">
        @csrf
        <div style="grid-column: span 2; display:flex; align-items:center; gap:14px;">
            <div style="width:56px;height:56px;border-radius:50%;background:#c04d5b;display:flex;align-items:center;justify-content:center;color:#fff;">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="28" height="28"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>
            </div>
            <div>
                <div style="font-weight:700;">Admin</div>
                <div style="color:#7a7a7a; font-size:14px;">Profil akun administrator</div>
            </div>
        </div>

        <div>
            <label class="form-label">Nama</label>
            <input class="form-input" type="text" name="name" placeholder="Nama lengkap" value="{{ old('name') }}">
        </div>
        <div>
            <label class="form-label">Email</label>
            <input class="form-input" type="email" name="email" placeholder="email@domain.com" value="{{ old('email') }}">
        </div>

        <div>
            <label class="form-label">Password Baru</label>
            <input class="form-input" type="password" name="password" placeholder="Kosongkan jika tidak diganti">
        </div>
        <div>
            <label class="form-label">Konfirmasi Password</label>
            <input class="form-input" type="password" name="password_confirmation" placeholder="Ulangi password">
        </div>

        <div style="grid-column: span 2; display:flex; justify-content:flex-end; margin-top:8px; gap:10px;">
            <button type="reset" style="background:#e7e0dc;color:#5b4e48;border:none;padding:10px 18px;border-radius:10px;">Batal</button>
            <button type="submit" style="background:#b34555;color:#fff;border:none;padding:10px 18px;border-radius:10px;font-weight:700;">Simpan</button>
        </div>
    </form>
</div>

<style>
    .form-label { display:block; margin-bottom:6px; font-weight:600; color:#5b4e48; }
    .form-input { width:100%; background:#fff; border:1px solid #d9c7bf; border-radius:10px; padding:10px 12px; outline:none; box-shadow:inset 0 1px 0 rgba(0,0,0,0.02); }
    .form-input:focus { border-color:#e07a5f; box-shadow:0 0 0 3px rgba(224,122,95,0.15); }
</style>
@endsection
