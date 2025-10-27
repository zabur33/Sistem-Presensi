<div>
    <div class="logo">
        <img src="{{ asset('images/logo2.png') }}" alt="Life Media Logo">
    </div>
    <div class="menu">
        <div class="menu-title">DASHBOARD ADMIN</div>
        <ul>
            <li><a href="/admin" class="{{ request()->is('admin') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>
                Dashboard
            </a></li>
            <li><a href="/admin/kelola-presensi" class="{{ request()->is('admin/kelola-presensi') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                Kelola Presensi
            </a></li>
            <li><a href="/admin/kelola-pegawai" class="{{ request()->is('admin/kelola-pegawai') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11c1.657 0 3-1.79 3-4s-1.343-4-3-4-3 1.79-3 4 1.343 4 3 4z"/><path d="M6 22v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/><circle cx="8" cy="8" r="3"/></svg>
                Kelola Data Pegawai
            </a></li>
            <li><a href="/admin/rekap-pegawai" class="{{ request()->is('admin/rekap-pegawai') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h18v18H3z"/><path d="M7 13h3v6H7zM12 5h3v14h-3zM17 9h3v10h-3z"/></svg>
                Rekap Pegawai
            </a></li>
            <li><a href="/admin/validasi-monitoring" class="{{ request()->is('admin/validasi-monitoring') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4"/><rect x="3" y="4" width="18" height="16" rx="2"/></svg>
                Validasi & Monitoring
            </a></li>
            <li><a href="/admin/registrasi-pegawai" class="{{ request()->is('admin/registrasi-pegawai') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M6 21v-2a6 6 0 0 1 12 0v2"/><path d="M19 10h3m-1.5-1.5V11.5"/></svg>
                Register Pegawai
            </a></li>
            <li><a href="/admin/validasi-lembur" class="{{ request()->is('admin/validasi-lembur') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Validasi Lembur
            </a></li>
        </ul>
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
