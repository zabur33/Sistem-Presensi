@extends('admin.layouts.app')

@section('content')
<style>
  :root { --card-bg:#ffffff; --border:#e5e7eb; --text:#111827; --muted:#6b7280; --shadow:0 2px 8px rgba(0,0,0,0.08); --radius:12px; }
  .admin-dashboard { padding: 16px; }
  .stats-row { display:grid; grid-template-columns:repeat(6, minmax(160px,1fr)); gap:14px; margin-bottom:16px; }
  .stat-card { background:var(--card-bg); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow); padding:14px; display:flex; flex-direction:column; gap:6px; }
  .stat-card h4 { margin:0; font-size:13px; color:var(--muted); font-weight:600; }
  .stat-card .value { font-size:22px; font-weight:800; color:var(--text); letter-spacing:0.3px; }
  .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px; }
  .card { background:var(--card-bg); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow); padding:16px; min-height:280px; }
  .card h2 { margin:0 0 12px 0; font-size:16px; font-weight:700; color:var(--text); }
  .chart-area { position:relative; width:100%; height:320px; }
  .donut-area, .pie-area { position:relative; width:100%; height:260px; display:flex; align-items:center; justify-content:center; }
  .calendar-box { border:1px dashed var(--border); border-radius:8px; padding:10px; color:var(--muted); height:auto; display:grid; grid-template-rows:auto 1fr; gap:8px; overflow:hidden; }
  .calendar-header { display:flex; align-items:center; gap:8px; justify-content:space-between; }
  .calendar-title { font-weight:600; color:var(--text); }
  .calendar-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:6px; font-size:12px; color:var(--text); }
  .cal-cell { text-align:center; padding:6px 0; border-radius:6px; min-height:30px; line-height:30px; background:transparent !important; }
  .cal-cell.header { color:var(--muted); font-weight:600; background:transparent !important; }
  .cal-cell.today { background:#1f2937; color:#fff; font-weight:700; }
  .calendar-nav { display:flex; gap:6px; }
  .calendar-nav button { border:1px solid var(--border); border-radius:8px; background:#f8fafc; padding:6px 10px; cursor:pointer; color:var(--text); }
  @media (max-width:1280px){ .stats-row{ grid-template-columns:repeat(3,1fr);} }
  @media (max-width:900px){ .grid-2{ grid-template-columns:1fr;} }
  @media (max-width:640px){ .stats-row{ grid-template-columns:repeat(2,1fr);} }
</style>

<div class="admin-dashboard">
  <div class="stats-row">
    <div class="stat-card"><h4>Total Pegawai</h4><div class="value">124</div></div>
    <div class="stat-card"><h4>Pegawai Hadir</h4><div class="value">112</div></div>
    <div class="stat-card"><h4>Pegawai Tidak Hadir</h4><div class="value">12</div></div>
    <div class="stat-card"><h4>Pegawai di Kantor</h4><div class="value">85</div></div>
    <div class="stat-card"><h4>Pegawai di Luar Kantor</h4><div class="value">39</div></div>
    <div class="stat-card"><h4>Lembur Disetujui</h4><div class="value">14</div></div>
  </div>

  <div class="grid-2">
    <div class="card">
      <h2>Rekap Kehadiran, Tidak Hadir & Lembur</h2>
      <div class="chart-area"><canvas id="barChart"></canvas></div>
    </div>
    <div class="card">
      <h2>Lokasi Kerja Pegawai</h2>
      <div class="donut-area"><canvas id="donutChart"></canvas></div>
    </div>
  </div>

  <div class="grid-2">
    <div class="card">
      <h2>Kalender Kehadiran</h2>
      <div class="calendar-box">
        <div class="calendar-header">
          <div class="calendar-title">November 2025</div>
          <div class="calendar-nav"><button>&lt;</button><button>&gt;</button></div>
        </div>
        <div class="calendar-grid">
          <div class="cal-cell header">Su</div><div class="cal-cell header">Mo</div><div class="cal-cell header">Tu</div><div class="cal-cell header">We</div><div class="cal-cell header">Th</div><div class="cal-cell header">Fr</div><div class="cal-cell header">Sa</div>
          <div class="cal-cell">27</div><div class="cal-cell">28</div><div class="cal-cell">29</div><div class="cal-cell">30</div><div class="cal-cell">31</div><div class="cal-cell">1</div><div class="cal-cell">2</div>
          <div class="cal-cell">3</div><div class="cal-cell today">4</div><div class="cal-cell">5</div><div class="cal-cell">6</div><div class="cal-cell">7</div><div class="cal-cell">8</div><div class="cal-cell">9</div>
          <div class="cal-cell">10</div><div class="cal-cell">11</div><div class="cal-cell">12</div><div class="cal-cell">13</div><div class="cal-cell">14</div><div class="cal-cell">15</div><div class="cal-cell">16</div>
          <div class="cal-cell">17</div><div class="cal-cell">18</div><div class="cal-cell">19</div><div class="cal-cell">20</div><div class="cal-cell">21</div><div class="cal-cell">22</div><div class="cal-cell">23</div>
          <div class="cal-cell">24</div><div class="cal-cell">25</div><div class="cal-cell">26</div><div class="cal-cell">27</div><div class="cal-cell">28</div><div class="cal-cell">29</div><div class="cal-cell">30</div>
        </div>
      </div>
    </div>
    <div class="card">
      <h2>Rasio Hadir vs Tidak Hadir</h2>
      <div class="pie-area"><canvas id="pieChart"></canvas></div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
<script>
  const monthLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

  const barCtx = document.getElementById('barChart');
  const donutCtx = document.getElementById('donutChart');
  const pieCtx = document.getElementById('pieChart');

  const barChart = barCtx ? new Chart(barCtx, {
    type: 'bar',
    data: { labels: monthLabels, datasets: [] },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          grid: { display: false },
          ticks: { color: '#6b7280' }
        },
        y: {
          beginAtZero: true,
          suggestedMax: 600,
          ticks: { stepSize: 150, color: '#6b7280' },
          grid: { color: '#f3f4f6' }
        }
      },
      plugins: {
        legend: { display: true, position: 'bottom', labels: { usePointStyle: true, pointStyle: 'rectRounded' } },
        tooltip: { enabled: true }
      }
    }
  }) : null;

  const donutChart = donutCtx ? new Chart(donutCtx, {
    type: 'doughnut',
    data: { labels: ['Kantor', 'Luar Kantor'], datasets: [{ data: [0,0], backgroundColor: ['#3b82f6','#10b981'], borderWidth: 0 }] },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '65%',
      plugins: {
        legend: { display: true, position: 'bottom', padding: 24, labels: { usePointStyle: true, padding: 50 } },
        datalabels: {
          formatter: (value) => (value > 0 ? value : ''),
          color: '#111827',
          anchor: 'end',
          align: 'end',
          offset: 14,
          font: { weight: '700' }
        }
      },
      layout: { padding: { bottom: 10 } }
    },
    plugins: [ChartDataLabels]
  }) : null;

  const pieChart = pieCtx ? new Chart(pieCtx, {
    type: 'pie',
    data: { labels: ['Hadir','Tidak Hadir'], datasets: [{ data: [0,0], backgroundColor: ['#10b981','#ef4444'], borderWidth: 0 }] },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: true, position: 'bottom', padding: 24, labels: { usePointStyle: true, padding: 50 } },
        datalabels: {
          formatter: (value) => (value > 0 ? value : ''),
          color: '#111827',
          anchor: 'end',
          align: 'end',
          offset: 16,
          font: { weight: '700' }
        }
      },
      layout: { padding: { bottom: 20 } }
    },
    plugins: [ChartDataLabels]
  }) : null;

  async function loadAdminMetrics() {
    try {
      const res = await fetch('/admin/api/dashboard/metrics', { headers: { 'Accept': 'application/json' } });
      if (!res.ok) return;
      const data = await res.json();

      const series = data.series || { hadir: [], kantor: [], luar_kantor: [] };
      const today = data.today || { total_employees: 0, present: 0, absent: 0, kantor: 0, luar_kantor: 0, overtime_approved: 0 };

      // Bar chart: gunakan Hadir, plus placeholder untuk TidakHadir/Lembur bila belum ada data backend
      if (barChart) {
        const hadir = (series.hadir || []).slice(0,12);
        const tidakHadir = new Array(12).fill(0);
        const lembur = new Array(12).fill(0);
        barChart.data.datasets = [
          { label: 'Hadir', data: hadir, backgroundColor: '#10b981', borderRadius: 8, barThickness: 26, categoryPercentage: 0.6, barPercentage: 0.9 },
          { label: 'Lembur', data: lembur, backgroundColor: '#3b82f6', borderRadius: 8, barThickness: 26, categoryPercentage: 0.6, barPercentage: 0.9 },
          { label: 'TidakHadir', data: tidakHadir, backgroundColor: '#ef4444', borderRadius: 8, barThickness: 26, categoryPercentage: 0.6, barPercentage: 0.9 }
        ];
        barChart.update();
      }

      // Donut: lokasi kerja (hari ini)
      if (donutChart) {
        donutChart.data.datasets[0].data = [today.kantor || 0, today.luar_kantor || 0];
        donutChart.update();
      }

      // Pie: rasio hadir vs tidak hadir (hari ini)
      if (pieChart) {
        const present = today.present || 0;
        const absent = today.absent || 0;
        pieChart.data.datasets[0].data = [present, absent];
        pieChart.update();
      }

      // Isi semua stat cards
      try {
        const setText = (sel, v) => { const el = document.querySelector(sel); if (el) el.textContent = v; };
        // urutan kartu: 1 Total Pegawai, 2 Pegawai Hadir, 3 Pegawai Tidak Hadir, 4 Pegawai di Kantor, 5 Pegawai di Luar Kantor, 6 Lembur Disetujui
        setText('.stats-row .stat-card:nth-child(1) .value', today.total_employees || 0);
        setText('.stats-row .stat-card:nth-child(2) .value', today.present || 0);
        setText('.stats-row .stat-card:nth-child(3) .value', today.absent || 0);
        setText('.stats-row .stat-card:nth-child(4) .value', today.kantor || 0);
        setText('.stats-row .stat-card:nth-child(5) .value', today.luar_kantor || 0);
        setText('.stats-row .stat-card:nth-child(6) .value', today.overtime_approved || 0);
      } catch (_) {}
    } catch (_) {}
  }

  document.addEventListener('DOMContentLoaded', loadAdminMetrics);
</script>
@endsection
