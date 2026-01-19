<div>
    <div class="logo">
        <a href="/dashboard" aria-label="Kembali ke Dashboard">
            <img src="{{ asset('images/logo2.png') }}" alt="Life Media Logo">
        </a>
    </div>
    <div class="menu">
        <div class="menu-title">MAIN MENU</div>
        <ul>
            <li>
                <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>
                    Dashboard
                </a>
            </li>
            <li class="presensi-menu">
                <a href="javascript:void(0)" onclick="togglePresensiDropdown(event)" style="justify-content:space-between;display:flex;align-items:center;"
                   class="{{ request()->is('presensi*') ? 'active' : '' }}">
                    <span style="display:flex;align-items:center;gap:0;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        Presensi
                    </span>
                    <svg class="dropdown-arrow {{ request()->is('presensi*') ? 'rotated' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6,9 12,15 18,9"/></svg>
                </a>
                <ul class="submenu {{ request()->is('presensi*') ? 'show' : '' }}" id="presensi-submenu">
                    <li><a href="/presensi/kantor" class="{{ request()->is('presensi/kantor') ? 'submenu-active' : '' }}">Kantor</a></li>
                    <li><a href="/presensi/luar-kantor" class="{{ request()->is('presensi/luar-kantor') ? 'submenu-active' : '' }}">Luar Kantor</a></li>
                </ul>
            </li>
            <li>
                <a href="/lembur" class="{{ request()->is('lembur') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    Lembur
                </a>
            </li>
            <li>
                <a href="/rekap-keseluruhan" class="{{ request()->is('rekap-keseluruhan') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/></svg>
                    Rekap Keseluruhan
                </a>
            </li>
            <li>
                <div class="logout" style="margin:16px 0 0 0;">
                    <form method="POST" action="{{ route('logout') }}" style="display:flex;align-items:center;gap:8px;">
                        @csrf
                        <button type="submit" style="display:flex;align-items:center;gap:8px;background:none;border:none;color:inherit;cursor:pointer;">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7"/><path d="M3 21V3a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v4"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
<script>
function togglePresensiDropdown(event){
    event.preventDefault();
    const submenu=document.getElementById('presensi-submenu');
    const arrow=document.querySelector('.presensi-menu .dropdown-arrow');
    if(!submenu||!arrow)return; const show=!submenu.classList.contains('show');
    submenu.classList.toggle('show',show); arrow.classList.toggle('rotated',show);
    try{ localStorage.setItem('presensi-dropdown-collapsed', show ? 'false' : 'true'); }catch(e){}
}
</script>
