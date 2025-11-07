<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Life Media</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js'])
    @endif
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* Additional styles for new dashboard content */
        .dashboard-content {
            padding: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.25rem;
        }

        .stat-card h4 {
            color: #9ca3af;
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stat-card .value {
            font-size: 1.5rem;
            font-weight: 700;
            display: block;
            line-height: 1.3;
            margin-top: 0.15rem;
        }

        .stat-card.total .value { color: #333; }
        .stat-card.present .value { color: #10b981; }
        .stat-card.remote .value { color: #3b82f6; }
        .stat-card.late .value { color: #ef4444; }

        .main-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .card h2 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .chart-legend {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1rem;
            font-size: 0.85rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 3px;
        }

        .status-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .location-info {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .location-icon {
            width: 40px;
            height: 40px;
            background: #10b98120;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #10b981;
            font-size: 1.2rem;
        }

        .location-details h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .location-details p {
            font-size: 0.85rem;
            color: #666;
        }

        .checkout-btn {
            background: #10b981;
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.95rem;
            transition: background 0.3s;
        }

        .checkout-btn:hover {
            background: #059669;
        }

        .calendar {
            padding: 0.5rem;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .calendar-header h3 {
            font-size: 1rem;
            font-weight: 600;
        }

        .calendar-nav {
            display: flex;
            gap: 1rem;
        }

        .calendar-nav button {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 1.1rem;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }

        .calendar-day {
            text-align: center;
            padding: 0.5rem;
            font-size: 0.85rem;
        }

        .calendar-day.header {
            font-weight: 600;
            color: #666;
            background: transparent !important;
            background-color: transparent !important;
        }

        .calendar-day.date {
            cursor: pointer;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .calendar-day.date:hover {
            background: #f3f4f6;
        }

        .calendar-day.today {
            background: #667eea;
            color: white;
            font-weight: 600;
        }

        .calendar-day.empty {
            visibility: hidden;
        }

        .donut-container {
            position: relative;
            height: 250px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Dropdown menu styles */
        .submenu {
            display: none;
            padding-left: 2.5rem;
        }

        .submenu.show {
            display: block;
        }

        .dropdown-arrow {
            margin-left: auto;
            transition: transform 0.2s;
            width: 16px;
            height: 16px;
        }

        .dropdown-arrow.rotated {
            transform: rotate(180deg);
        }

        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-card .value {
                font-size: 1.5rem;
            }

            .status-content {
                flex-direction: column;
                gap: 1rem;
            }

            .checkout-btn {
                width: 100%;
            }
        }
    </style>
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
                    <li><a href="/dashboard" class="active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>Dashboard</a></li>
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
            <form method="POST" action="{{ route('logout') }}" style="display:flex;align-items:center;gap:8px;">
                @csrf
                <button type="submit" style="display:flex;align-items:center;gap:8px;background:none;border:none;color:inherit;cursor:pointer;">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7"/><path d="M3 21V3a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v4"/></svg>
                    Logout
                </button>
            </form>
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
                <div id="notifWrapper" style="position:relative;display:inline-block;">
                    <a href="#" id="notifBell" onclick="toggleNotifDropdown(event)" title="Notifikasi">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        <span id="notifBadge" style="position:absolute;top:-4px;right:-4px;background:#dc2626;color:#fff;border-radius:999px;padding:0 6px;font-size:10px;line-height:18px;height:18px;display:none;">0</span>
                    </a>
                    <div id="notifDropdown" style="display:none;position:absolute;right:0;top:28px;background:#ffffff;border:1px solid #e5e7eb;border-radius:10px;min-width:280px;box-shadow:0 12px 24px rgba(0,0,0,0.12);z-index:50;">
                        <div style="padding:10px 12px;border-bottom:1px solid #f3f4f6;font-weight:700;color:#111827;">Notifikasi</div>
                        <div id="notifList" style="max-height:320px;overflow:auto"></div>
                    </div>
                </div>
                <a href="/profile">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>
                </a>
            </div>
        </div>
        <div class="dashboard-content">
            <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card total">
                        <h4>Total Hari Kerja</h4>
                        <div class="value" id="totalWorkdaysVal">22</div>
                    </div>
                    <div class="stat-card present">
                        <h4>Kehadiran Bulan Ini</h4>
                        <div class="value" id="presentVal">-</div>
                    </div>
                    <div class="stat-card remote">
                        <h4>WFH / Dinas Luar</h4>
                        <div class="value" id="remoteVal">-</div>
                    </div>
                    <div class="stat-card late">
                        <h4>Keterlambatan</h4>
                        <div class="value" id="lateVal">-</div>
                    </div>
                </div>

                <!-- Main Grid -->
                <div class="main-grid">
                    <!-- Grafik Kehadiran -->
                    <div class="card">
                        <h2>Grafik Kehadiran</h2>
                        <div class="chart-container">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                        <div class="chart-legend">
                            <div class="legend-item">
                                <div class="legend-color" style="background: #f59e0b;"></div>
                                <span>Dinas</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #10b981;"></div>
                                <span>Hadir</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #3b82f6;"></div>
                                <span>WFH</span>
                            </div>
                        </div>
                    </div>

                    <!-- Mode Kerja -->
                    <div class="card">
                        <h2>Mode Kerja</h2>
                        <div class="donut-container">
                            <canvas id="workModeChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="main-grid">
                    <!-- Status Hari Ini -->
                    <div class="card">
                        <h2>Status Hari Ini</h2>
                        <div class="status-content">
                            <div class="location-info">
                                <div class="location-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="location-details">
                                    <h3 id="todayLocation">-</h3>
                                    <p id="todayTimes">Check-in: — | Check-out: —</p>
                                </div>
                            </div>
                            <button class="checkout-btn">Check Out</button>
                        </div>
                    </div>

                    <!-- Kalender -->
                    <div class="card">
                        <h2>Kalender</h2>
                        <div class="calendar">
                            <div class="calendar-header">
                                <h3>November 2025</h3>
                                <div class="calendar-nav">
                                    <button><i class="fas fa-chevron-left"></i></button>
                                    <button><i class="fas fa-chevron-right"></i></button>
                                </div>
                            </div>
                            <div class="calendar-grid">
                                <div class="calendar-day header">Su</div>
                                <div class="calendar-day header">Mo</div>
                                <div class="calendar-day header">Tu</div>
                                <div class="calendar-day header">We</div>
                                <div class="calendar-day header">Th</div>
                                <div class="calendar-day header">Fr</div>
                                <div class="calendar-day header">Sa</div>
                                
                                <!-- Empty cells for alignment -->
                                <div class="calendar-day empty">-</div>
                                <div class="calendar-day empty">-</div>
                                <div class="calendar-day empty">-</div>
                                <div class="calendar-day empty">-</div>
                                <div class="calendar-day empty">-</div>
                                <div class="calendar-day empty">-</div>
                                
                                <!-- Days of November 2025 -->
                                <div class="calendar-day date">1</div>
                                <div class="calendar-day date">2</div>
                                <div class="calendar-day date today">3</div>
                                <div class="calendar-day date">4</div>
                                <div class="calendar-day date">5</div>
                                <div class="calendar-day date">6</div>
                                <div class="calendar-day date">7</div>
                                <div class="calendar-day date">8</div>
                                <div class="calendar-day date">9</div>
                                <div class="calendar-day date">10</div>
                                <div class="calendar-day date">11</div>
                                <div class="calendar-day date">12</div>
                                <div class="calendar-day date">13</div>
                                <div class="calendar-day date">14</div>
                                <div class="calendar-day date">15</div>
                                <div class="calendar-day date">16</div>
                                <div class="calendar-day date">17</div>
                                <div class="calendar-day date">18</div>
                                <div class="calendar-day date">19</div>
                                <div class="calendar-day date">20</div>
                                <div class="calendar-day date">21</div>
                                <div class="calendar-day date">22</div>
                                <div class="calendar-day date">23</div>
                                <div class="calendar-day date">24</div>
                                <div class="calendar-day date">25</div>
                                <div class="calendar-day date">26</div>
                                <div class="calendar-day date">27</div>
                                <div class="calendar-day date">28</div>
                                <div class="calendar-day date">29</div>
                                <div class="calendar-day date">30</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Grafik Kehadiran (Bar Chart)
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(attendanceCtx, {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                datasets: []
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 24,
                        ticks: {
                            stepSize: 6
                        },
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Mode Kerja (Donut Chart)
        const workModeCtx = document.getElementById('workModeChart').getContext('2d');
        const workModeChart = new Chart(workModeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'WFH', 'Dinas Luar'],
                datasets: [{
                    data: [0, 0, 0],
                    backgroundColor: [
                        '#10b981',
                        '#3b82f6',
                        '#f59e0b'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                },
                cutout: '70%'
            },
            plugins: [{
                id: 'doughnutLabel',
                afterDatasetDraw(chart) {
                    const { ctx, data } = chart;
                    const centerX = chart.getDatasetMeta(0).data[0].x;
                    const centerY = chart.getDatasetMeta(0).data[0].y;
                    
                    ctx.save();
                    ctx.font = 'bold 14px Arial';
                    ctx.fillStyle = '#333';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    
                    // Draw labels for each segment
                    data.datasets[0].data.forEach((value, index) => {
                        const meta = chart.getDatasetMeta(0).data[index];
                        const angle = (meta.startAngle + meta.endAngle) / 2;
                        const radius = (meta.innerRadius + meta.outerRadius) / 2;
                        
                        const x = centerX + Math.cos(angle) * radius;
                        const y = centerY + Math.sin(angle) * radius;
                        
                        ctx.fillStyle = 'white';
                        ctx.fillText(value, x, y);
                    });
                    
                    ctx.restore();
                }
            }]
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
                    submenu.classList.remove('show');
                    arrow.classList.remove('rotated');
                    localStorage.setItem('presensi-dropdown-collapsed', 'true');
                } else {
                    submenu.classList.add('show');
                    arrow.classList.add('rotated');
                    localStorage.setItem('presensi-dropdown-collapsed', 'false');
                }
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.presensi-menu')) {
                const submenu = document.querySelector('.presensi-menu .submenu');
                const arrow = document.querySelector('.presensi-menu .dropdown-arrow');
                if (submenu) {
                    submenu.classList.remove('show');
                    localStorage.setItem('presensi-dropdown-collapsed', 'true');
                }
                if (arrow) arrow.classList.remove('rotated');
            }
        });

        // Initialize dropdown state on dashboard
        window.addEventListener('DOMContentLoaded', function() {
            const submenu = document.getElementById('presensi-submenu');
            const arrow = document.querySelector('.presensi-menu .dropdown-arrow');
            
            if (submenu && arrow) {
                const isCollapsed = localStorage.getItem('presensi-dropdown-collapsed') === 'true';
                if (!isCollapsed) {
                    submenu.classList.add('show');
                    arrow.classList.add('rotated');
                }
            }
        });

        // Notification functionality
        function toggleNotifDropdown(e) {
            e.preventDefault();
            const dd = document.getElementById('notifDropdown');
            if (!dd) return;
            const isVisible = dd.style.display === 'block';
            dd.style.display = isVisible ? 'none' : 'block';
            if (!isVisible) {
                localStorage.setItem('overtime_last_seen', new Date().toISOString());
                updateBadge([]);
            }
        }

        function updateBadge(items) {
            const badge = document.getElementById('notifBadge');
            if (!badge) return;
            const lastSeen = new Date(localStorage.getItem('overtime_last_seen') || 0).getTime();
            const cnt = (items || []).filter(it => new Date(it.updated_at).getTime() > lastSeen).length;
            if (cnt > 0) {
                badge.style.display = 'inline-block';
                badge.textContent = cnt;
            } else {
                badge.style.display = 'none';
            }
        }

        // Close notification dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#notifWrapper')) {
                const dd = document.getElementById('notifDropdown');
                if (dd) dd.style.display = 'none';
            }
        });
    </script>
</body>
</html> 