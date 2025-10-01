<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Presensi - Bar Chart</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js'])
    @endif
    <style>
        .report-container { padding: 24px; }
        .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 16px; }
        .card h2 { text-align: center; margin: 0 0 8px; font-weight: 700; }
        .card p.small { text-align: center; color: #6b7280; margin: 0 0 10px; font-size: 0.9rem; }
        .chart-wrapper { width: 100%; height: 220px; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        window.__attendanceLabels = @json($labels);
        window.__attendanceValues = @json($values);
    </script>
    </head>
<body>
    <div class="report-container">
        <div class="card">
            <p class="small">Daily Attendance (30 days)</p>
            <h2>Grafik Presensi</h2>
            <div class="chart-wrapper">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        const labels = window.__attendanceLabels || [];
        const values = window.__attendanceValues || [];
        const ctx = document.getElementById('attendanceChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Kehadiran',
                        data: values,
                        backgroundColor: '#2cb1bc',
                        borderRadius: 6,
                        maxBarThickness: 12,
                        barThickness: 10,
                        categoryPercentage: 0.7,
                        barPercentage: 0.7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { titleFont: { size: 11 }, bodyFont: { size: 11 } }
                    },
                    scales: {
                        x: {
                            title: { display: true, text: 'Hari', color: '#6b7280', font: { size: 12 } },
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        },
                        y: {
                            title: { display: true, text: 'Status', color: '#6b7280', font: { size: 12 } },
                            beginAtZero: true,
                            suggestedMax: 2,
                            grid: { color: 'rgba(0,0,0,0.06)' },
                            ticks: { stepSize: 1, font: { size: 10 } }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>


