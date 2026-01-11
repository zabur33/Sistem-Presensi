@extends('admin.layouts.app')

@section('content')
<div class="page-title" style="margin-bottom:18px;">
    <h1 style="font-weight:800; font-size:28px;">Validasi Lembur</h1>
    <p style="color:#7a7a7a; margin-top:6px;">Pantau dan kelola pengajuan lembur dari pegawai</p>
</div>

<!-- Tabs + Search + Actions -->
<form method="GET" action="{{ route('admin.overtime') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:end;margin-bottom:14px;">
    <div style="display:flex;background:#f4e9e4;border:1px solid #e3d6cf;border-radius:999px;overflow:hidden;">
        <a href="{{ route('admin.overtime', array_merge(request()->query(), ['tab'=>'all'])) }}" class="tab {{ ($tab ?? 'all')==='all' ? 'active' : '' }}">Semua</a>
        <a href="{{ route('admin.overtime', array_merge(request()->query(), ['tab'=>'unread'])) }}" class="tab {{ ($tab ?? 'all')==='unread' ? 'active' : '' }}">Belum dibaca</a>
    </div>
    <div style="position:relative;">
        <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#8a8a8a;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </span>
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari nama/alasan" class="control" style="width:280px;padding:10px 12px 10px 36px;background:#efe2db;border:1px solid #e3d6cf;border-radius:12px;outline:none;height:42px;">
    </div>
    <div>
        <label class="form-label" style="display:block;margin-bottom:6px;font-weight:600;color:#5b4e48">Dari</label>
        <input type="date" name="start_date" value="{{ $start ?? '' }}" class="form-input" style="min-width:160px;background:#fff;border:1px solid #d9c7bf;border-radius:12px;padding:10px 12px" />
    </div>
    <div>
        <label class="form-label" style="display:block;margin-bottom:6px;font-weight:600;color:#5b4e48">Sampai</label>
        <input type="date" name="end_date" value="{{ $end ?? '' }}" class="form-input" style="min-width:160px;background:#fff;border:1px solid #d9c7bf;border-radius:12px;padding:10px 12px" />
    </div>
    <div>
        <label class="form-label" style="display:block;margin-bottom:6px;font-weight:600;color:#5b4e48">Status</label>
        <select name="status" class="form-input" style="min-width:160px;background:#fff;border:1px solid #d9c7bf;border-radius:12px;padding:10px 12px">
            <option value="">Semua</option>
            <option value="pending" @selected(($status ?? '')==='pending')>Pending</option>
            <option value="approved" @selected(($status ?? '')==='approved')>Approved</option>
            <option value="rejected" @selected(($status ?? '')==='rejected')>Rejected</option>
        </select>
    </div>
    <div style="display:flex;gap:10px;align-items:center;align-self:end;white-space:nowrap;">
        <button type="submit" class="btn" style="background:#fd9b63;color:#fff;border:none;padding:10px 16px;border-radius:10px;font-weight:700;height:42px;display:inline-flex;align-items:center;">Terapkan</button>
    </div>
    <style>
        .tab{display:inline-flex;align-items:center;justify-content:center;padding:0 14px;height:42px;border-radius:999px;text-decoration:none;color:#5b4e48}
        .tab.active{background:#b34555;color:#fff}
        .ot-card{background:#f4e9e4;border:1px solid #e3d6cf;border-radius:10px;padding:0;overflow:hidden}
        .ot-item{display:flex;gap:10px;align-items:flex-start;padding:12px;border-top:1px solid #e5dedb}
        .ot-item:first-child{border-top:none}
        .user-ava{width:38px;height:38px;border-radius:50%;background:#fff;border:2px solid #e07a5f;display:flex;align-items:center;justify-content:center;color:#c04d5b;font-weight:800}
        .badge{display:inline-block;padding:4px 8px;border-radius:999px;font-size:12px;font-weight:700}
        .badge.pending{background:#fff0cc;color:#a05a00}
        .badge.approved{background:#e6f7ed;color:#0f8a4a}
        .badge.rejected{background:#f7e6e6;color:#b34747}
        .btn{border:none;border-radius:8px;padding:6px 10px;font-size:12px;color:#fff;height:32px}
        .btn.read{background:#64748b}
        .btn.approve{background:#0f8a4a}
        .btn.reject{background:#b34747}
    </style>
</form>

<!-- Toolbar kanan: tindakan massal -->
<div style="display:flex;justify-content:flex-end;margin-bottom:12px;">
    <form method="POST" action="{{ route('admin.overtime.readAll') }}">
        @csrf
        <button type="submit" class="btn" style="background:#64748b;color:#fff;border:none;padding:10px 16px;border-radius:10px;height:42px;display:inline-flex;align-items:center;">Tandai semua terbaca</button>
    </form>
    </div>

<!-- List -->
<div class="ot-card">
    <div style="padding:10px 12px;font-weight:800;color:#5b4e48;border-bottom:1px solid #e3d6cf;">Validasi Lembur</div>
    @forelse(($items ?? []) as $it)
        <div class="ot-item">
            <div class="user-ava">{{ strtoupper(substr(optional($it->user)->name ?? 'U',0,1)) }}</div>
            <div style="flex:1;min-width:0;">
                <div style="display:flex;justify-content:space-between;gap:10px;">
                    <div style="font-weight:700;">{{ optional($it->user)->name ?? '—' }}</div>
                    <div style="color:#7a7a7a;font-size:12px;white-space:nowrap;">{{ optional($it->date)->format('d-m-Y') ?? (string)$it->date }} {{ substr($it->start_time,0,5) }}-{{ substr($it->end_time,0,5) }}</div>
                </div>
                <div style="margin-top:2px;color:#5b4e48;">Mengirimkan pengajuan lembur: <em>{{ $it->reason ?? '-' }}</em></div>
                @php
                    $face = $it->face_photo_path ? asset('storage/'.$it->face_photo_path) : null;
                    $supp = $it->support_photo_path ? asset('storage/'.$it->support_photo_path) : null;
                @endphp
                @if($face || $supp || !empty($it->address))
                    <div style="margin-top:8px;display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                        @if($face)
                            <a href="{{ $face }}" target="_blank" style="display:inline-block;border:1px solid #e5dedb;border-radius:6px;overflow:hidden;width:60px;height:60px;background:#fff;">
                                <img src="{{ $face }}" alt="face" style="width:100%;height:100%;object-fit:cover;"/>
                            </a>
                        @endif
                        @if($supp)
                            <a href="{{ $supp }}" target="_blank" style="display:inline-block;border:1px solid #e5dedb;border-radius:6px;overflow:hidden;width:60px;height:60px;background:#fff;">
                                <img src="{{ $supp }}" alt="support" style="width:100%;height:100%;object-fit:cover;"/>
                            </a>
                        @endif
                        @if(!empty($it->address))
                            <span style="color:#6b7280;font-size:12px;background:#fff;padding:6px 10px;border:1px solid #e5dedb;border-radius:6px;">{{ $it->address }}</span>
                        @endif
                    </div>
                @endif
                <div style="margin-top:6px;display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                    <span class="badge {{ $it->status }}">{{ ucfirst($it->status) }}</span>
                    @if(!$it->read_at)
                        <form method="POST" action="{{ route('admin.overtime.read', $it->id) }}">
                            @csrf
                            <button type="submit" class="btn read">Tandai terbaca</button>
                        </form>
                    @else
                        <span style="color:#7a7a7a;font-size:12px;">Terbaca {{ $it->read_at->diffForHumans() }}</span>
                    @endif
                    @if($it->status === 'pending')
                        <form method="POST" action="{{ route('admin.overtime.approve', $it->id) }}">
                            @csrf
                            <button type="submit" class="btn approve">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('admin.overtime.reject', $it->id) }}">
                            @csrf
                            <button type="submit" class="btn reject">Reject</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="ot-item" style="justify-content:center;color:#7a7a7a;">Tidak ada notifikasi</div>
    @endforelse
</div>

@if(isset($items))
    <div style="margin-top:12px;display:flex;justify-content:flex-end;">
        {{ $items->links() }}
    </div>
@endif
@endsection
