@extends('admin.layouts.app')

@section('content')
<div class="page-title" style="margin-bottom:18px;">
    <h1 style="font-weight:800; font-size:28px;">Kelola data pegawai</h1>
    <p style="color:#7a7a7a; margin-top:6px;">Lihat, cari, dan kelola akun pegawai.</p>
</div>

<!-- Highlight Card (first employee or placeholder) -->
@php $first = optional($employees ?? null)->first(); @endphp
<div class="emp-highlight" style="background:#f4e9e4;border:1px solid #e3d6cf;border-radius:18px;padding:18px 22px;display:flex;align-items:center;gap:16px;max-width:980px;">
    <div style="width:64px;height:64px;border-radius:50%;overflow:hidden;border:3px solid #e07a5f;flex:0 0 auto;display:flex;align-items:center;justify-content:center;background:#fff;">
        @if($first && $first->avatar_url)
            <img src="{{ $first->avatar_url }}" alt="Foto Pegawai" style="width:100%;height:100%;object-fit:cover;">
        @else
            <svg viewBox="0 0 24 24" width="28" height="28" stroke="#c04d5b" fill="none" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>
        @endif
    </div>
    <div style="line-height:1.25;">
        <div style="font-weight:800;font-size:20px;">{{ $first?->user?->name ?? '—' }}</div>
        <div style="color:#6b6b6b;">{{ $first?->position ?? '—' }}</div>
    </div>
</div>

<!-- Search -->
<form method="GET" action="{{ route('admin.kelola-pegawai') }}" style="margin:16px 0 10px;display:flex;gap:14px;flex-wrap:wrap;align-items:flex-end;">
    <div style="position:relative;">
        <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#8a8a8a;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </span>
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search" style="width:300px;padding:10px 12px 10px 36px;background:#efe2db;border:1px solid #e3d6cf;border-radius:12px;outline:none;">
    </div>
    <div>
        <label class="form-label" style="display:block;margin-bottom:6px;font-weight:600;color:#5b4e48">Divisi</label>
        <select name="division" class="form-input" style="min-width:180px;background:#fff;border:1px solid #d9c7bf;border-radius:12px;padding:10px 12px">
            <option value="">Semua</option>
            @foreach(($divisions ?? []) as $d)
                <option value="{{ $d }}" @selected(($division ?? '') === $d)>{{ $d }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label" style="display:block;margin-bottom:6px;font-weight:600;color:#5b4e48">Status</label>
        <select name="active" class="form-input" style="min-width:160px;background:#fff;border:1px solid #d9c7bf;border-radius:12px;padding:10px 12px">
            <option value="">Semua</option>
            <option value="1" @selected(($active ?? '')==='1')>Aktif</option>
            <option value="0" @selected(($active ?? '')==='0')>Nonaktif</option>
        </select>
    </div>
    <button type="submit" style="background:#fd9b63;color:#fff;border:none;padding:10px 16px;border-radius:10px;font-weight:700;">Terapkan</button>
    <style>
        .emp-table{width:100%;border-collapse:collapse;background:#fff}
        .emp-table thead th{background:#b34555;color:#fff;text-align:left;padding:10px 12px}
        .emp-table tbody td{padding:10px 12px;border-top:1px solid #e9e3e0}
        .emp-table tbody tr:nth-child(even){background:#faf7f5}
        .table-shell{overflow:auto;border:1px solid #d9c7bf;border-radius:10px}
        .badge{display:inline-block;padding:4px 8px;border-radius:999px;font-size:12px;font-weight:600}
        .badge.active{background:#e6f7ed;color:#0f8a4a}
        .badge.inactive{background:#f7e6e6;color:#b34747}
        .btn{border:none;border-radius:8px;padding:6px 10px;font-size:12px;color:#fff}
        .btn.edit{background:#64748b}
        .btn.delete{background:#ef4444}
        .btn.detail{background:#b34555}
    </style>
</form>

<!-- Table -->
<div class="table-shell">
    <table class="emp-table">
        <thead>
            <tr>
                <th style="width:34%">Nama</th>
                <th style="width:28%">Email</th>
                <th style="width:18%">Jabatan</th>
                <th style="width:10%">Status</th>
                <th style="width:10%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($employees ?? []) as $e)
                <tr>
                    <td>{{ optional($e->user)->name ?? '—' }}</td>
                    <td>{{ optional($e->user)->email ?? '—' }}</td>
                    <td>{{ $e->position ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $e->active ? 'active' : 'inactive' }}">{{ $e->active ? 'Aktif' : 'Nonaktif' }}</span>
                    </td>
                    <td style="display:flex;gap:6px;flex-wrap:wrap;">
                        <button class="btn detail" type="button">Detail</button>
                        <button class="btn edit" type="button">Edit</button>
                        <button class="btn delete" type="button">Hapus</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:#7a7a7a;">Belum ada data pegawai.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($employees))
    <div style="margin-top:12px;display:flex;justify-content:flex-end;">
        {{ $employees->links() }}
    </div>
@endif
@endsection
