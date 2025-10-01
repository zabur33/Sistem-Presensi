<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Keseluruhan - Life Media</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="{{ asset('css/rekap.css') }}">
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
                    <li><a href="/rekap-keseluruhan" class="active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/></svg>Rekap Keseluruhan</a></li>
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
        <div class="rekap-content">
            <div class="page-title">
                <h1>Rekap Keseluruhan</h1>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="search-filter">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search">
                        <svg class="search-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                    </div>
                    <button class="filter-btn" onclick="toggleFilterModal()">Filter</button>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-container">
                <table class="rekap-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kedatangan</th>
                            <th>Kepulangan</th>
                            <th>Total Jam Kerja</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="rekapTableBody">
                        <!-- Sample data - in real app this would come from backend -->
                        <tr>
                            <td>1</td>
                            <td>01/01/2024</td>
                            <td>08:00</td>
                            <td>17:00</td>
                            <td>8 jam</td>
                            <td>Hadir</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>02/01/2024</td>
                            <td>08:15</td>
                            <td>17:30</td>
                            <td>8 jam 15 menit</td>
                            <td>Terlambat</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>03/01/2024</td>
                            <td>08:00</td>
                            <td>16:00</td>
                            <td>7 jam</td>
                            <td>Pulang Cepat</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>04/01/2024</td>
                            <td>-</td>
                            <td>-</td>
                            <td>0 jam</td>
                            <td>Tidak Hadir</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>05/01/2024</td>
                            <td>08:00</td>
                            <td>18:00</td>
                            <td>9 jam</td>
                            <td>Lembur</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>06/01/2024</td>
                            <td>08:30</td>
                            <td>17:00</td>
                            <td>7 jam 30 menit</td>
                            <td>Terlambat</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>07/01/2024</td>
                            <td>08:00</td>
                            <td>17:00</td>
                            <td>8 jam</td>
                            <td>Hadir</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>08/01/2024</td>
                            <td>08:00</td>
                            <td>17:00</td>
                            <td>8 jam</td>
                            <td>Hadir</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>09/01/2024</td>
                            <td>08:10</td>
                            <td>17:15</td>
                            <td>8 jam 5 menit</td>
                            <td>Terlambat</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>10/01/2024</td>
                            <td>08:00</td>
                            <td>17:00</td>
                            <td>8 jam</td>
                            <td>Hadir</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <button class="page-btn" onclick="previousPage()" id="prevBtn">Previous</button>
                <span class="page-info" id="pageInfo">Page 1 of 1</span>
                <button class="page-btn" onclick="nextPage()" id="nextBtn">Next</button>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div id="filterModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Filter Data</h3>
            <span class="close" onclick="toggleFilterModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="filter-group">
                <label for="dateFrom">Dari Tanggal:</label>
                <input type="date" id="dateFrom" name="dateFrom">
            </div>
            <div class="filter-group">
                <label for="dateTo">Sampai Tanggal:</label>
                <input type="date" id="dateTo" name="dateTo">
            </div>
            <div class="filter-group">
                <label for="statusFilter">Status:</label>
                <select id="statusFilter" name="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="Hadir">Hadir</option>
                    <option value="Terlambat">Terlambat</option>
                    <option value="Pulang Cepat">Pulang Cepat</option>
                    <option value="Tidak Hadir">Tidak Hadir</option>
                    <option value="Lembur">Lembur</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="resetFilter()">Reset</button>
            <button class="btn-primary" onclick="applyFilter()">Terapkan</button>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let itemsPerPage = 10;
let filteredData = [];
let allData = [];

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

// Filter Modal
function toggleFilterModal() {
    const modal = document.getElementById('filterModal');
    modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('filterModal');
    if (event.target === modal) {
        toggleFilterModal();
    }
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    filterTable(searchTerm);
});

function filterTable(searchTerm) {
    const rows = document.querySelectorAll('#rekapTableBody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

// Filter functions
function applyFilter() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const status = document.getElementById('statusFilter').value;

    // Here you would typically filter the data based on the criteria
    // For now, we'll just close the modal
    toggleFilterModal();

    // In a real application, you would send these filters to the backend
    console.log('Applying filters:', { dateFrom, dateTo, status });
}

function resetFilter() {
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    document.getElementById('statusFilter').value = '';

    // Reset table display
    const rows = document.querySelectorAll('#rekapTableBody tr');
    rows.forEach(row => {
        row.style.display = '';
    });
}

// Pagination functions
function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        updatePagination();
    }
}

function nextPage() {
    // In a real app, you'd check against total pages
    currentPage++;
    updatePagination();
}

function updatePagination() {
    document.getElementById('pageInfo').textContent = `Page ${currentPage} of 1`;
    document.getElementById('prevBtn').disabled = currentPage === 1;
}

// Initialize page
window.addEventListener('load', function() {
    updatePagination();

    // Set default date range (current month)
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);

    document.getElementById('dateFrom').value = firstDay.toISOString().split('T')[0];
    document.getElementById('dateTo').value = lastDay.toISOString().split('T')[0];
});
</script>
</body>
</html>
