@extends('admin.layouts.app')

@section('content')
<div class="page-title" style="margin-bottom:18px;">
    <h1 style="font-weight:800; font-size:28px;">Rekap Pegawai</h1>
    <p style="color:#7a7a7a; margin-top:6px;">Laporan ringkasan kehadiran pegawai berdasarkan periode, lokasi, dan status kehadiran.</p>
</div>

<!-- Filters + Export -->
<form method="GET" action="{{ route('admin.rekap-pegawai') }}" class="filters" style="display:grid;grid-template-columns:repeat(4, minmax(180px, 1fr)) auto;gap:16px;align-items:end;max-width:1080px;margin-bottom:16px;">
    <div>
        <label class="form-label">Periode (Tahun)</label>
        <select name="year" class="form-input">
            @for ($y = now()->year; $y >= now()->year - 5; $y--)
                <option value="{{ $y }}" @selected(request('year', now()->year)==$y)>{{ $y }}</option>
            @endfor
        </select>
    </div>
    <div>
        <label class="form-label">Periode (Bulan)</label>
        <select name="month" class="form-input">
            @foreach ([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $mVal=>$mText)
                <option value="{{ $mVal }}" @selected(request('month', now()->month)==$mVal)>{{ $mText }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Lokasi</label>
        <select name="location" class="form-input">
            <option value="">Semua</option>
            <option value="kantor" @selected(request('location')==='kantor')>Kantor</option>
            <option value="luar_kantor" @selected(request('location')==='luar_kantor')>Luar Kantor</option>
        </select>
    </div>
    <div>
        <label class="form-label">Status Kehadiran</label>
        <select name="status" class="form-input">
            <option value="">Semua</option>
            <option value="Hadir" @selected(request('status')==='Hadir')>Hadir</option>
            <option value="Terlambat" @selected(request('status')==='Terlambat')>Terlambat</option>
            <option value="Lembur" @selected(request('status')==='Lembur')>Lembur</option>
            <option value="Tanpa Keterangan" @selected(request('status')==='Tanpa Keterangan')>Tanpa Keterangan</option>
        </select>
    </div>
    <div style="display:flex;gap:10px;align-items:center;align-self:end;white-space:nowrap;">
        <button type="submit" class="btn" style="background:#fd9b63;color:#fff;border:none;padding:10px 16px;border-radius:10px;font-weight:700;">Tampilkan</button>
        <a href="{{ route('admin.rekap-pegawai.export.csv', request()->query()) }}" class="btn" style="background:#64748b;color:#fff;border:none;padding:10px 16px;border-radius:10px;text-decoration:none;display:inline-block;">Export CSV</a>
        <a href="{{ route('admin.rekap-pegawai.print', request()->query()) }}" target="_blank" class="btn" style="background:#b34555;color:#fff;border:none;padding:10px 16px;border-radius:10px;text-decoration:none;display:inline-block;">Print</a>
    </div>

    <style>
        .form-label{display:block;margin-bottom:6px;font-weight:600;color:#5b4e48}
        .form-input{width:100%;background:#fff;border:1px solid #d9c7bf;border-radius:12px;padding:10px 12px; height:42px}
        .form-input:focus{border-color:#e07a5f;outline:none;box-shadow:0 0 0 3px rgba(224,122,95,.15)}
        .recap-table{width:100%;border-collapse:collapse;background:#fff}
        .recap-wrap{overflow:auto;border:1px solid #d9c7bf;border-radius:10px}
        .recap-table thead th{background:#b34555;color:#fff;text-align:left;padding:10px 12px;white-space:nowrap}
        .recap-table tbody td{padding:10px 12px;border-top:1px solid #eee;color:#4a4a4a;vertical-align:top}
        .recap-table tbody tr:nth-child(even){background:#faf7f5}
        .chip{display:inline-block;background:#efe2db;color:#5b4e48;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600}
        .chip.ok{background:#e6f7ed;color:#0f8a4a}
        .chip.badge{background:#fd9b63;color:#fff}
        .btn{line-height:1; height:42px; display:inline-flex; align-items:center}
    </style>
</form>

<!-- Recap Table -->
<div class="recap-wrap">
    <table class="recap-table">
        <thead>
            <tr>
                <th style="min-width:220px;">Nama</th>
                <th style="min-width:140px;">ID Pegawai</th>
                <th style="min-width:160px;">Jabatan</th>
                <th style="min-width:90px;">Hadir</th>
                <th style="min-width:110px;">Terlambat</th>
                <th style="min-width:160px;">Tanpa Keterangan</th>
                <th style="min-width:100px;">Lembur</th>
                <th style="min-width:160px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($rows ?? []) as $r)
                <tr>
                    <td>
                        <div style="font-weight:700;">{{ $r->name }}</div>
                        <div style="font-size:12px;color:#7a7a7a;">{{ $r->division ?? '—' }}</div>
                    </td>
                    <td>{{ $r->nip ?? '—' }}</td>
                    <td>{{ $r->position ?? '—' }}</td>
                    <td><span class="chip ok">{{ $r->hadir }}</span></td>
                    <td>{{ $r->terlambat }}</td>
                    <td><span class="chip">{{ $r->tanpa_keterangan }}</span></td>
                    <td><span class="chip badge">{{ $r->lembur }}</span></td>
                    <td><em>{{ request('location') ? ucfirst(str_replace('_',' ',request('location'))) : '—' }}</em></td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;color:#7a7a7a;">Tidak ada data untuk periode dan filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Footer: pagination only -->
<div style="margin-top:12px;display:flex;justify-content:flex-end;align-items:center;gap:10px;">
    @if(isset($rows))
        {{ $rows->links() }}
    @endif
</div>
@endsection
