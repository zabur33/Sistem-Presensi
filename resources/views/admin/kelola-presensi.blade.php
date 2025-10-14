@extends('admin.layouts.app')

@section('content')
<div class="page-title" style="margin-bottom:18px;">
    <h1 style="font-weight:800; font-size:28px;">Kelola Presensi</h1>
    <p style="color:#7a7a7a; margin-top:6px;">Pantau ringkasan dan riwayat presensi pegawai.</p>
    </div>
<!-- Summary Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="icon">
            <!-- check-circle-plus -->
            <svg viewBox="0 0 24 24" fill="none" stroke="#6c6c6c" stroke-width="2">
                <circle cx="12" cy="12" r="9"/>
                <path d="M9 12l2 2 4-4"/>
                <path d="M12 7v10M7 12h10"/>
            </svg>
        </div>
        <div class="title">Hadir</div>
        <div class="footer">{{ $summary['hadir'] ?? 0 }}</div>
    </div>
    <div class="summary-card">
        <div class="icon">
            <!-- person-circle-exclamation -->
            <svg viewBox="0 0 24 24" fill="none" stroke="#6c6c6c" stroke-width="2">
                <circle cx="12" cy="12" r="9"/>
                <circle cx="12" cy="9" r="3"/>
                <path d="M7 20a7 7 0 0 1 10 0"/>
                <line x1="12" y1="5" x2="12" y2="3"/>
                <circle cx="18.5" cy="6" r="1.2"/>
            </svg>
        </div>
        <div class="title">Tanpa Keterangan</div>
        <div class="footer">{{ $summary['tanpa_ket'] ?? 0 }}</div>
    </div>
    <!-- Hanya dua kartu: Hadir & Tanpa Keterangan -->

    <style>
        .summary-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:18px;margin-bottom:20px}
        .summary-card{background:#f4e9e4;border:1px solid #e3d6cf;border-radius:16px;padding:18px 18px 0 18px;display:flex;flex-direction:column;align-items:center;box-shadow:0 1px 0 rgba(0,0,0,.02)}
        .summary-card .icon{width:48px;height:48px;display:flex;align-items:center;justify-content:center;margin:8px 0}
        .summary-card .icon svg{width:40px;height:40px}
        .summary-card .title{color:#5b4e48;margin-bottom:12px;font-weight:600}
        /* Make footer span full card width (cancel card's horizontal padding) */
        .summary-card .footer{align-self:stretch; margin-left:-18px; margin-right:-18px; background:#b34555;color:#fff;text-align:center;padding:10px; border-bottom-left-radius:16px;border-bottom-right-radius:16px;margin-top:auto;font-weight:700}
        @media(max-width: 1000px){.summary-grid{grid-template-columns:1fr}}
    </style>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('admin.kelola-presensi') }}" class="filters" style="display:flex;flex-wrap:wrap;gap:18px;align-items:flex-end;margin:8px 0 22px;">
    <div>
        <label class="form-label">Tahun</label>
        <select class="form-input" name="year">
            @for ($y = now()->year; $y >= now()->year - 5; $y--)
                <option value="{{ $y }}" @selected($y == ($year ?? now()->year))>{{ $y }}</option>
            @endfor
        </select>
    </div>
    <div>
        <label class="form-label">Bulan</label>
        <select class="form-input" name="month">
            @foreach ([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $mVal=>$mText)
                <option value="{{ $mVal }}" @selected($mVal==($month ?? now()->month))>{{ $mText }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Lokasi</label>
        <select class="form-input" name="location">
            <option value="">Semua</option>
            <option value="kantor" @selected(request('location')==='kantor')>Kantor</option>
            <option value="luar_kantor" @selected(request('location')==='luar_kantor')>Luar Kantor</option>
        </select>
    </div>
    <div>
        <label class="form-label">Status</label>
        <select class="form-input" name="status">
            <option value="">Semua</option>
            <option value="Hadir" @selected(($statusFilter ?? '')==='Hadir')>Hadir</option>
            <option value="Tanpa Keterangan" @selected(($statusFilter ?? '')==='Tanpa Keterangan')>Tanpa Keterangan</option>
        </select>
    </div>
    <div>
        <label class="form-label">Verifikasi</label>
        <select class="form-input" name="verification">
            <option value="">Semua</option>
            <option value="—" @selected(($verificationFilter ?? '')==='—')>—</option>
            <option value="Berhasil" @selected(($verificationFilter ?? '')==='Berhasil')>Berhasil</option>
            <option value="Ditolak" @selected(($verificationFilter ?? '')==='Ditolak')>Ditolak</option>
        </select>
    </div>
    <button type="submit" class="btn-show" style="background:#fd9b63;color:#fff;border:none;padding:10px 16px;border-radius:10px;font-weight:700;">Tampilkan</button>
    <style>
        .form-label{display:block;margin-bottom:6px;font-weight:600;color:#5b4e48}
        .form-input{min-width:220px;background:#fff;border:1px solid #d9c7bf;border-radius:12px;padding:10px 12px}
        .form-input:focus{border-color:#e07a5f;outline:none;box-shadow:0 0 0 3px rgba(224,122,95,.15)}
    </style>
</form>

<!-- Table -->
<div style="margin-top:6px;margin-bottom:8px;font-weight:800;font-size:20px;">Riwayat Presensi :</div>
<div class="table-wrap">
    <table class="table-presensi">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Masuk</th>
                <th>Pulang</th>
                <th>Status</th>
                <th>Tipe</th>
                <th>Lokasi</th>
                <th>Kegiatan</th>
                <th>Foto</th>
                <th>Verifikasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['time_in'] }}</td>
                    <td>{{ $row['time_out'] }}</td>
                    <td>{{ $row['status'] }}</td>
                    <td>{{ $row['location_type'] === 'luar_kantor' ? 'Luar Kantor' : ($row['location_type'] === 'kantor' ? 'Dalam Kantor' : '—') }}</td>
                    <td>{{ $row['location_text'] ?? '—' }}</td>
                    <td>{{ ($row['location_type'] === 'luar_kantor') ? ($row['activity_text'] ?? '—') : '—' }}</td>
                    <td>
                        @if(!empty($row['photo_path']))
                            <a href="{{ asset('storage/'.$row['photo_path']) }}" target="_blank">Lihat</a>
                        @else
                            —
                        @endif
                    </td>
                    <td style="display:flex; align-items:center; gap:10px;">
                        @php $type = $row['verification_type'] ?? 'neutral'; @endphp
                        <span class="badge {{ $type === 'success' ? 'success' : '' }}">{{ $row['verification'] }}</span>
                        @if(($row['verification'] ?? '') !== 'Berhasil' && ($row['id'] ?? null))
                            <form method="POST" action="{{ route('admin.attendance.verify', $row['id']) }}" onsubmit="return confirm('Verifikasi presensi ini?');">
                                @csrf
                                <button type="submit" style="background:#b34555;color:#fff;border:none;padding:6px 10px;border-radius:8px;font-size:12px;">Verifikasi</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align:center;color:#7a7a7a;">Tidak ada data presensi untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <style>
        .table-wrap{overflow:auto;border-radius:10px;border:1px solid #d9c7bf}
        .table-presensi{width:100%;border-collapse:collapse;background:#fff}
        .table-presensi thead th{background:#b34555;color:#fff;text-align:left;padding:10px 12px;position:sticky;top:0}
        .table-presensi tbody td{padding:10px 12px;border-top:1px solid #eee;color:#4a4a4a}
        .table-presensi tbody tr:nth-child(even){background:#faf7f5}
        .badge{display:inline-block;background:#e7e0dc;color:#5b4e48;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600}
        .badge.success{background:#fd9b63;color:#fff}
    </style>
</div>
@endsection
