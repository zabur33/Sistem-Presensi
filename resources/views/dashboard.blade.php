@extends('user.layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .dashboard-wrap{padding:18px;max-width:1200px;margin:0 auto}
    .grid{display:grid;gap:18px}
    .grid-4{grid-template-columns:repeat(4,minmax(0,1fr))}
    .grid-2{grid-template-columns:repeat(2,minmax(0,1fr))}
    .stat-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px;display:flex;flex-direction:column;min-height:110px}
    .stat-value{font-weight:800;font-size:26px;color:#0f172a;margin-top:auto}
    .stat-label{color:#6b7280;font-size:13px}
    .accent-green{color:#16a34a}.accent-blue{color:#2563eb}.accent-red{color:#dc2626}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px}
    .card-header{font-weight:700;color:#0f172a;margin-bottom:10px}
    .chart-container{height:320px;position:relative}
    .footer{margin:20px 0 4px;padding:12px 0;text-align:center;color:#9ca3af;font-size:12px}
    @media (max-width:1024px){.grid-4{grid-template-columns:repeat(2,1fr)}}
    @media (max-width:640px){.grid-4,.grid-2{grid-template-columns:1fr}}
    </style>

<div class="dashboard-wrap">
    <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:4px 0 14px;">Dashboard</h2>

    <div class="grid grid-4">
        <div class="stat-card"><div class="stat-label">Total Hari Kerja</div><div class="stat-value" id="total-work-days">0</div></div>
        <div class="stat-card"><div class="stat-label">Kehadiran Bulan Ini</div><div class="stat-value accent-green" id="present-this-month">0 Hari</div></div>
        <div class="stat-card"><div class="stat-label">WFH / Dinas Luar</div><div class="stat-value accent-blue" id="wfh-outdoor">0 Hari</div></div>
        <div class="stat-card"><div class="stat-label">Keterlambatan</div><div class="stat-value accent-red" id="late-count">0x</div></div>
    </div>

    <div class="grid grid-2" style="margin-top:18px;">
        <div class="card"><div class="card-header">Mode kerja</div><div class="chart-container"><canvas id="attendanceChart"></canvas></div></div>
        <div class="card"><div class="card-header">Grafik Kehadiran</div><div class="chart-container"><canvas id="modeChart"></canvas></div></div>
    </div>

    <div class="card" style="margin-top:18px;">
        <div class="card-header">Status Hari Ini</div>
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="padding:8px;border-radius:999px;background:#e8faf3;color:#16a34a;display:inline-flex;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <div class="text-sm" id="todayLocation" style="font-weight:700;color:#111827;">-</div>
                <div class="text-xs" id="todayTimes" style="color:#6b7280;">Check-in: — | Check-out: —</div>
            </div>
            <button id="checkoutBtn" style="margin-left:auto;padding:8px 12px;border-radius:8px;background:#10b981;color:#fff;display:none;">Check Out</button>
        </div>
    </div>

    <div class="footer">© {{ date('Y') }} Tim 3 - Life Media. All rights reserved.</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const barCtx=document.getElementById('attendanceChart');
    const bar=new Chart(barCtx,{type:'bar',data:{labels:['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],datasets:[
        {label:'Kantor',data:[3,2,2,1,2,1,2,2,1,2,2,1],backgroundColor:'#f59e0b',borderRadius:6,barPercentage:.7},
        {label:'Luar kantor',data:[19,17,22,20,19,21,22,20,21,19,20,18],backgroundColor:'#10b981',borderRadius:6,barPercentage:.7},
        {label:'Lembur',data:[5,7,4,5,6,5,4,6,5,7,6,7],backgroundColor:'#3b82f6',borderRadius:6,barPercentage:.7}
    ]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}},scales:{y:{beginAtZero:true,ticks:{stepSize:5},max:25},x:{grid:{display:false}}}}});

    const pieCtx=document.getElementById('modeChart');
    const pie=new Chart(pieCtx,{type:'pie',data:{labels:['Masuk','Tidak Masuk','Keterlambatan'],datasets:[{data:[15,25,60],backgroundColor:['#0bf565ff','#f63e3bff','#f59e0b'],borderWidth:0}]} ,options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'right'}}}});

    async function postCheckout(){
        try{
            const res = await fetch("{{ route('attendance.checkout') }}", {
                method:'POST',
                headers:{ 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' },
                body: JSON.stringify({ location_type: 'kantor', client_time: new Date().toTimeString().slice(0,8) })
            });
            if(!res.ok) throw new Error('Checkout gagal');
            return await res.json().catch(()=>({}));
        }catch(e){ console.warn(e.message||e); return null; }
    }

    try{
        const res=await fetch('/api/dashboard/metrics',{headers:{'Accept':'application/json'}});
        if(res.ok){
            const data=await res.json();
            document.getElementById('total-work-days').textContent = data.stats?.total_work_days ?? 0;
            document.getElementById('present-this-month').textContent = `${data.stats?.days_present ?? 0} Hari`;
            document.getElementById('wfh-outdoor').textContent = `${data.stats?.remote_days ?? 0} Hari`;
            document.getElementById('late-count').textContent = `${data.stats?.late_days ?? 0}x`;
            if(data.today_status){
                document.getElementById('todayLocation').textContent = data.today_status.location || '-';
                document.getElementById('todayTimes').textContent = `Check-in: ${data.today_status.time_in ?? '—'} | Check-out: ${data.today_status.time_out ?? '—'}`;
                const btn=document.getElementById('checkoutBtn');
                if(data.today_status.can_checkout){
                    btn.style.display='inline-flex';
                    btn.onclick = async () => {
                        btn.disabled = true;
                        const out = await postCheckout();
                        btn.disabled = false;
                        if(out!==null){
                            // refresh today status quickly
                            document.getElementById('todayTimes').textContent = `Check-in: ${data.today_status.time_in ?? '—'} | Check-out: ${new Date().toTimeString().slice(0,5)}`;
                            btn.style.display='none';
                        }
                    };
                }
            }
            if(data.monthly_data){
                const dinas=new Array(12).fill(0), hadir=new Array(12).fill(0), wfh=new Array(12).fill(0);
                data.monthly_data.forEach(m=>{const i=(m.month||1)-1; hadir[i]=+m.present||0; dinas[i]=+m.dinas||0; wfh[i]=+m.wfh||0;});
                bar.data.datasets[0].data=dinas; bar.data.datasets[1].data=hadir; bar.data.datasets[2].data=wfh; bar.update();
            }
            if(data.mode_summary){ pie.data.datasets[0].data=[data.mode_summary.dinas||0,data.mode_summary.wfh||0,data.mode_summary.hadir||0]; pie.update(); }
        }
    }catch(e){ console.warn('API metrics tidak tersedia:', e.message); }
});
</script>
@endsection
