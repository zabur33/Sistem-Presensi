@extends('admin.layouts.app')

@section('content')
<div class="page-title" style="margin-bottom:18px;">
    <h1 style="font-weight:800; font-size:28px;">Kelola data pegawai</h1>
    <p style="color:#7a7a7a; margin-top:6px;">Lihat, cari, dan kelola akun pegawai.</p>
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
        .btn{border:none;border-radius:8px;padding:6px 10px;font-size:12px;color:#fff;display:inline-block;text-decoration:none}
        .btn.edit{background:#64748b}
        .btn.delete{background:#ef4444}
        .btn.detail{background:#b34555}
        .panel{background:#fff;border:1px solid #d9c7bf;border-radius:10px;padding:14px;margin:12px 0}
        .panel h3{margin:0 0 10px 0;font-weight:800}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .form-row .field{display:flex;flex-direction:column;gap:6px}
        .form-row input, .form-row select{border:1px solid #d9c7bf;border-radius:10px;padding:10px 12px}
        .actions{display:flex;gap:8px;align-items:center;margin-top:10px}
    </style>
</form>

@php
    $qs = http_build_query(array_filter(['q' => $q ?? null, 'division' => $division ?? null, 'active' => $active ?? null]));
@endphp

@if(!empty($showId) && !empty($selected))
    <div class="panel">
        <h3>Detail Pegawai</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <div><strong>Nama</strong><br>{{ $selected->user->name ?? '—' }}</div>
            <div><strong>Email</strong><br>{{ $selected->user->email ?? '—' }}</div>
            <div><strong>Divisi</strong><br>{{ $selected->division ?? '—' }}</div>
            <div><strong>Jabatan</strong><br>{{ $selected->position ?? '—' }}</div>
            <div><strong>Status</strong><br>{{ $selected->active ? 'Aktif' : 'Nonaktif' }}</div>
        </div>
        <div class="actions">
            <a class="btn edit" href="{{ route('admin.kelola-pegawai', array_filter(['q'=>$q,'division'=>$division,'active'=>$active,'edit'=>$selected->id])) }}">Edit</a>
            <a class="btn detail" href="{{ route('admin.kelola-pegawai', array_filter(['q'=>$q,'division'=>$division,'active'=>$active])) }}">Tutup</a>
        </div>
    </div>
@endif

@if(!empty($editId) && !empty($selected))
    <div class="panel">
        <h3>Edit Pegawai</h3>
        <form method="POST" action="{{ route('admin.employees.update', $selected) }}@if($qs)?{{ '?'.$qs }}@endif">
            @csrf
            @method('PATCH')
            <div class="form-row">
                <div class="field">
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ old('name', optional($selected->user)->name) }}">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', optional($selected->user)->email) }}">
                </div>
                <div class="field">
                    <label>Divisi</label>
                    <input type="text" name="division" value="{{ old('division', $selected->division) }}">
                </div>
                <div class="field">
                    <label>Jabatan</label>
                    <input type="text" name="position" value="{{ old('position', $selected->position) }}">
                </div>
                <div class="field">
                    <label>Status</label>
                    <select name="active">
                        <option value="1" @selected(old('active', (int)$selected->active)===1)>Aktif</option>
                        <option value="0" @selected(old('active', (int)$selected->active)===0)>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="actions">
                <button type="submit" class="btn edit">Simpan</button>
                <a class="btn detail" href="{{ route('admin.kelola-pegawai', array_filter(['q'=>$q,'division'=>$division,'active'=>$active])) }}">Batal</a>
            </div>
        </form>
    </div>
@endif

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
                    <td style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
                        <a class="btn detail" href="{{ route('admin.kelola-pegawai', array_filter(['q'=>$q,'division'=>$division,'active'=>$active,'show'=>$e->id])) }}">Detail</a>
                        <a class="btn edit" href="{{ route('admin.kelola-pegawai', array_filter(['q'=>$q,'division'=>$division,'active'=>$active,'edit'=>$e->id])) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.employees.destroy', $e) }}@if($qs)?{{ '?'.$qs }}@endif" onsubmit="return confirm('Hapus pegawai ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn delete" type="submit">Hapus</button>
                        </form>
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
