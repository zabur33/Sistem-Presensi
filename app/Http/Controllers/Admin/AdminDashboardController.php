<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Employee;

class AdminDashboardController extends Controller
{
    public function metrics(Request $request)
    {
        $tz = config('app.timezone') ?: 'UTC';
        $now = Carbon::now($tz);
        $year = (int) $request->query('year', $now->year);

        $totalEmployees = (int) Employee::count();

        // Per-month counts (all employees)
        $byMonth = Attendance::query()
            ->whereYear('date', $year)
            ->selectRaw('MONTH(date) as m,
                SUM(CASE WHEN time_in IS NOT NULL THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN location_type = "kantor" THEN 1 ELSE 0 END) as kantor,
                SUM(CASE WHEN location_type = "luar_kantor" THEN 1 ELSE 0 END) as luar_kantor')
            ->groupBy('m')
            ->orderBy('m')
            ->get();

        $months = range(1, 12);
        $series = [
            'hadir' => array_fill(1, 12, 0),
            'kantor' => array_fill(1, 12, 0),
            'luar_kantor' => array_fill(1, 12, 0),
        ];
        foreach ($byMonth as $row) {
            $m = (int) $row->m;
            $series['hadir'][$m] = (int) $row->hadir;
            $series['kantor'][$m] = (int) $row->kantor;
            $series['luar_kantor'][$m] = (int) $row->luar_kantor;
        }

        // Today stats (unique employees)
        $today = $now->toDateString();
        $todayRows = Attendance::where('date', $today)->get(['user_id','time_in','location_type']);
        $presentUserIds = $todayRows->filter(fn($r) => !empty($r->time_in))->pluck('user_id')->unique();
        $present = $presentUserIds->count();
        $absent = max(0, $totalEmployees - $present);
        $kantorToday = $todayRows->filter(function($r){ return $r->location_type === 'kantor' && !empty($r->time_in); })->pluck('user_id')->unique()->count();
        $luarToday = $todayRows->filter(function($r){ return $r->location_type === 'luar_kantor' && !empty($r->time_in); })->pluck('user_id')->unique()->count();

        return response()->json([
            'year' => $year,
            'months' => $months,
            'series' => [
                'hadir' => array_values($series['hadir']),
                'kantor' => array_values($series['kantor']),
                'luar_kantor' => array_values($series['luar_kantor']),
            ],
            'today' => [
                'total_employees' => $totalEmployees,
                'present' => $present,
                'absent' => $absent,
                'kantor' => $kantorToday,
                'luar_kantor' => $luarToday,
                'overtime_approved' => 0,
            ],
        ]);
    }
}
