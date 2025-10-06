<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $year = (int)($request->query('year') ?: now()->year);
        $month = (int)($request->query('month') ?: now()->month);
        $location = $request->query('location'); // kantor | luar_kantor | null

        // Query DB
        $query = Attendance::query()
            ->with('user')
            ->whereYear('date', $year)
            ->whereMonth('date', $month);

        if (in_array($location, ['kantor','luar_kantor'], true)) {
            $query->where('location_type', $location);
        }

        $rows = $query->orderByDesc('date')->limit(200)->get();

        $items = $rows->map(function ($a) {
            return [
                'id' => $a->id,
                'name' => optional($a->user)->name ?? '—',
                'date' => optional($a->date)->format('d-m-Y') ?? (string) $a->date,
                'time' => $a->time_in ? (\Carbon\Carbon::parse($a->time_in)->format('H.i')) : '-',
                'status' => $a->status,
                'verification' => $a->verification ?? '—',
                'verification_type' => ($a->verification === 'Berhasil') ? 'success' : 'neutral',
            ];
        })->toArray();

        $summary = [
            'hadir' => $rows->where('status', 'Hadir')->count(),
            'tanpa_ket' => $rows->where('status', 'Tanpa Keterangan')->count(),
        ];

        return view('admin.kelola-presensi', [
            'year' => $year,
            'month' => $month,
            'items' => $items,
            'summary' => $summary,
        ]);
    }

    public function verify(Request $request, \App\Models\Attendance $attendance)
    {
        $attendance->update(['verification' => 'Berhasil']);
        return back()->with('status', 'Presensi diverifikasi.');
    }

    public function reject(Request $request, \App\Models\Attendance $attendance)
    {
        $attendance->update(['verification' => 'Ditolak']);
        return back()->with('status', 'Presensi ditolak.');
    }
}
