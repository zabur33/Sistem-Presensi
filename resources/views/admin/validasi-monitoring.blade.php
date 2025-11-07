@extends('admin.layouts.app')

@section('content')
<div class="page-title" style="margin-bottom:18px;">
    <h1 style="font-weight:800; font-size:28px;">Monitoring</h1>
    <p style="color:#7a7a7a; margin-top:6px;"> Pantau status, dan filter berdasarkan periode.</p>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('admin.validasi-monitoring') }}" class="filters" style="display:flex;flex-wrap:wrap;gap:16px;align-items:end;margin-bottom:16px;">
    <div>
        <label class="form-label">Tanggal Mulai</label>
        <input type="date" class="form-input" name="start_date" value="{{ $start ?? '' }}" />
    </div>
    <div>
        <label class="form-label">Tanggal Selesai</label>
        <input type="date" class="form-input" name="end_date" value="{{ $end ?? '' }}" />
    </div>
    <div>
        <label class="form-label">Lokasi</label>
        <select class="form-input" name="location">
            <option value="">Semua</option>
            <option value="kantor" @selected(($location ?? '')==='kantor')>Kantor</option>
            <option value="luar_kantor" @selected(($location ?? '')==='luar_kantor')>Luar Kantor</option>
        </select>
    </div>
    <div>
        <label class="form-label">Status Verifikasi</label>
        <select class="form-input" name="status">
            <option value="">Semua</option>
            <option value="Pending" @selected(($status ?? '')==='Pending')>Pending</option>
            <option value="Berhasil" @selected(($status ?? '')==='Berhasil')>Terverifikasi</option>
            <option value="Ditolak" @selected(($status ?? '')==='Ditolak')>Ditolak</option>
        </select>
    </div>
    <button type="submit" class="btn" style="background:#fd9b63;color:#fff;border:none;padding:10px 16px;border-radius:10px;font-weight:700;">Terapkan</button>
    <style>
        .form-label{display:block;margin-bottom:6px;font-weight:600;color:#5b4e48}
        .form-input{min-width:200px;background:#fff;border:1px solid #d9c7bf;border-radius:12px;padding:10px 12px}
        .form-input:focus{border-color:#e07a5f;outline:none;box-shadow:0 0 0 3px rgba(224,122,95,.15)}
        .vm-table{width:100%;border-collapse:collapse;background:#fff}
        .vm-wrap{overflow:auto;border:1px solid #d9c7bf;border-radius:10px}
        .vm-table thead th{background:#b34555;color:#fff;text-align:left;padding:10px 12px}
        .vm-table tbody td{padding:10px 12px;border-top:1px solid #eee;color:#4a4a4a}
        .vm-table tbody tr:nth-child(even){background:#faf7f5}
        .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:700}
        .badge.ok{background:#e6f7ed;color:#0f8a4a}
        .badge.warn{background:#fff0cc;color:#a05a00}
        .badge.danger{background:#f7e6e6;color:#b34747}
        .btn{border:none;border-radius:8px;padding:6px 10px;font-size:12px;color:#fff}
        .btn.verify{background:#0f8a4a}
        .btn.reject{background:#b34747}
    </style>
</form>

<!-- Table -->
<div class="vm-wrap">
    <table class="vm-table">
        <thead>
            <tr>
                <th style="min-width:220px;">Nama</th>
                <th style="min-width:120px;">Durasi</th>
                <th style="min-width:120px;">Tanggal</th>
                <th style="min-width:120px;">Lokasi</th>
                <th style="min-width:140px;">Status</th>
                <th style="min-width:200px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($rows ?? []) as $a)
                <tr>
                    <td>{{ optional($a->user)->name ?? '—' }}</td>
                    <td>
                        @php
                            $mins = $a->duration_minutes;
                            $durasi = $mins !== null ? floor($mins/60).'j '.str_pad($mins%60,2,'0',STR_PAD_LEFT).'m' : '-';
                        @endphp
                        {{ $durasi }}
                    </td>
                    <td>{{ optional($a->date)->format('d-m-Y') ?? (string)$a->date }}</td>
                    <td>{{ $a->location_type === 'luar_kantor' ? 'Luar Kantor' : 'Kantor' }}</td>
                    <td>
                        @php
                            $ver = $a->verification ?? 'Pending';
                            $cls = $ver==='Berhasil' ? 'ok' : ($ver==='Ditolak' ? 'danger' : 'warn');
                            $label = $ver==='Berhasil' ? 'Terverifikasi' : ($ver==='Ditolak' ? 'Ditolak' : 'Pending');
                        @endphp
                        <span class="badge {{ $cls }}">{{ $label }}</span>
                    </td>
                    <td style="display:flex;gap:8px;flex-wrap:wrap;">
                        @if(($a->verification ?? 'Pending') !== 'Berhasil')
                            <form method="POST" action="{{ route('admin.attendance.verify', $a->id) }}">
                                @csrf
                                <button type="submit" class="btn verify">Verifikasi</button>
                            </form>
                        @endif
                        @if(($a->verification ?? 'Pending') !== 'Ditolak')
                            <form method="POST" action="{{ route('admin.attendance.reject', $a->id) }}">
                                @csrf
                                <button type="submit" class="btn reject">Tolak</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:#7a7a7a;">Tidak ada data pada rentang tanggal ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(isset($rows))
    <div style="margin-top:12px;display:flex;justify-content:flex-end;">
        {{ $rows->links() }}
    </div>
@endif
@endsection
