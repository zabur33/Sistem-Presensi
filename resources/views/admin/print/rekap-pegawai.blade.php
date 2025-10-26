<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekap Pegawai</title>
    <style>
        *{box-sizing:border-box}
        body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Inter,Helvetica,Arial,sans-serif;color:#111827;margin:24px;background:#fff}
        .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
        .title{font-size:20px;font-weight:800;color:#111827}
        .meta{color:#6b7280;font-size:12px;margin-top:2px}
        .filters{margin:8px 0 16px;padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;background:#fafafa;color:#374151;font-size:12px;display:flex;gap:14px;flex-wrap:wrap}
        table{width:100%;border-collapse:collapse}
        thead th{background:#111827;color:#fff;text-align:left;padding:10px 12px;font-size:12px}
        tbody td{padding:10px 12px;border-top:1px solid #e5e7eb;font-size:12px;vertical-align:top}
        tbody tr:nth-child(even){background:#fafafa}
        .chip{display:inline-block;padding:2px 8px;border-radius:999px;font-weight:700;font-size:11px}
        .ok{background:#e6f7ed;color:#065f46}
        .warn{background:#fde68a;color:#92400e}
        .muted{background:#efe2db;color:#5b4e48}
        @media print{
            body{margin:0}
            .no-print{display:none !important}
            .filters{background:#fff}
            thead th{background:#111827 -webkit-print-color-adjust:exact; print-color-adjust:exact}
        }
        .footer{margin-top:14px;display:flex;justify-content:space-between;align-items:center;color:#6b7280;font-size:11px}
        .btn{display:inline-block;padding:8px 12px;border-radius:8px;background:#111827;color:#fff;text-decoration:none;border:none}
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="title">Rekap Pegawai</div>
            <div class="meta">Periode: {{ str_pad((string)$month,2,'0',STR_PAD_LEFT) }}/{{ $year }}</div>
        </div>
        <div class="no-print">
            <button class="btn" onclick="window.print()">Print</button>
        </div>
    </div>

    <div class="filters">
        <div>Lokasi: <strong>{{ $location ? ucfirst(str_replace('_',' ',$location)) : 'Semua' }}</strong></div>
        <div>Status: <strong>{{ $status ?: 'Semua' }}</strong></div>
        <div>Total Pegawai: <strong>{{ ($rows ?? collect())->count() }}</strong></div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="min-width:200px;">Nama</th>
                <th style="min-width:120px;">NIP</th>
                <th style="min-width:160px;">Jabatan</th>
                <th style="min-width:90px;">Hadir</th>
                <th style="min-width:110px;">Terlambat</th>
                <th style="min-width:160px;">Tanpa Keterangan</th>
                <th style="min-width:100px;">Lembur</th>
                <th style="min-width:140px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($rows ?? []) as $r)
                <tr>
                    <td>
                        <div style="font-weight:700">{{ $r->name }}</div>
                        <div style="color:#6b7280">{{ $r->division ?? '—' }}</div>
                    </td>
                    <td>{{ $r->nip ?? '—' }}</td>
                    <td>{{ $r->position ?? '—' }}</td>
                    <td><span class="chip ok">{{ $r->hadir }}</span></td>
                    <td>{{ $r->terlambat }}</td>
                    <td><span class="chip muted">{{ $r->tanpa_keterangan }}</span></td>
                    <td><span class="chip warn">{{ $r->lembur }}</span></td>
                    <td>{{ $location ? ucfirst(str_replace('_',' ',$location)) : '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:#6b7280">Tidak ada data untuk filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div>Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
        <div>Life Media • Sistem Presensi</div>
    </div>

    <script>
        window.addEventListener('load', function(){
            if (window.matchMedia) {
                const m = window.matchMedia('print');
                if (!m.matches) setTimeout(()=>window.print(), 300);
            } else {
                setTimeout(()=>window.print(), 300);
            }
        });
    </script>
</body>
</html>
