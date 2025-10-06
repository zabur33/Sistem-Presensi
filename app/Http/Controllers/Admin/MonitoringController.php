<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->query('start_date'); // yyyy-mm-dd
        $end = $request->query('end_date');     // yyyy-mm-dd
        $location = $request->query('location'); // kantor | luar_kantor | null
        $status = $request->query('status'); // Berhasil | Pending | Ditolak | null

        $query = Attendance::query()->with('user')
            ->when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->when(in_array($location, ['kantor','luar_kantor'], true), fn($q) => $q->where('location_type', $location))
            ->when(in_array($status, ['Berhasil','Pending','Ditolak'], true), fn($q) => $q->where('verification', $status))
            ->orderByDesc('date')
            ->orderByDesc('id');

        $rows = $query->paginate(15)->withQueryString();

        return view('admin.validasi-monitoring', [
            'rows' => $rows,
            'start' => $start,
            'end' => $end,
            'location' => $location,
            'status' => $status,
        ]);
    }
}
