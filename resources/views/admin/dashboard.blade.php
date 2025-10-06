@extends('admin.layouts.app')

@section('content')
<div class="profile-section">
    <img class="profile-pic" src="https://randomuser.me/api/portraits/men/.." alt="Profile">
    <div class="profile-info">
        <div class="name">Admin Life Media</div>
        <div class="role">Administrator</div>
    </div>
</div>

<div class="charts">
    <div class="chart-box">
        <div class="chart-title">Grafik Presensi</div>
        <canvas id="chartPresensi" class="chart-canvas"></canvas>
    </div>
    <div class="chart-box">
        <div class="chart-title">Keaktifan Kerja</div>
        <canvas id="chartAktif" class="chart-canvas"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Data contoh admin; nanti bisa diganti dari backend
    const labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'];
    const presensiData = [8, 7, 9, 8, 7];
    const aktifData = [70, 65, 85, 80, 75];

    const ctx1 = document.getElementById('chartPresensi');
    if (ctx1) {
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Jam Hadir',
                    data: presensiData,
                    backgroundColor: '#7ec6d3',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    const ctx2 = document.getElementById('chartAktif');
    if (ctx2) {
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Skor Aktivitas (%)',
                    data: aktifData,
                    backgroundColor: '#e76f51',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                scales: {
                    y: { beginAtZero: true, max: 100 }
                }
            }
        });
    }
</script>
@endsection
