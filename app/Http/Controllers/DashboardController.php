<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Attendance;

class DashboardController extends Controller
{
    public function metrics(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $tz = config('app.timezone') ?: 'UTC';
        $now = Carbon::now($tz);
        $year = (int) $request->query('year', $now->year);

        // Base query: current user and selected year
        $base = Attendance::query()
            ->where('user_id', $user->id)
            ->whereYear('date', $year);

        // Per-month counts by status/location
        $byMonth = $base->clone()
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

        // Current month distribution and metrics
        $monthRows = Attendance::where('user_id', $user->id)
            ->whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->get();

        $presentDays = $monthRows->filter(function($r){ return !empty($r->time_in); })->count();
        $kantorDays = $monthRows->where('location_type', 'kantor')->count();
        $luarDays = $monthRows->where('location_type', 'luar_kantor')->count();

        // Lateness count: time_in > 08:00:00 local on that date
        $workStart = '08:00:00';
        $lateCount = 0;
        foreach ($monthRows as $r) {
            if (!$r->time_in) continue;
            try {
                $in = Carbon::parse($r->time_in, $tz);
                $dateOnly = Carbon::parse($r->date, $tz)->toDateString();
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $dateOnly.' '.$workStart, $tz);
                if ($in->greaterThan($start)) $lateCount++;
            } catch (\Throwable $e) {
                // ignore
            }
        }

        // Today status
        $todayRow = Attendance::where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();
        if (!$todayRow) {
            $todayRow = Attendance::where('user_id', $user->id)
                ->orderByDesc('date')
                ->first();
        }

        // Compute yearly working entries for stats (total recorded days in selected year)
        $totalWorkDays = (int) $base->clone()->count();

        // Build monthly_data array matching frontend expectation
        $monthlyData = [];
        foreach ($months as $m) {
            $monthlyData[] = [
                'month' => $m,
                // Map to dashboard frontend keys
                'dinas' => (int) ($series['kantor'][$m] ?? 0),
                'present' => (int) ($series['hadir'][$m] ?? 0),
                'wfh' => (int) ($series['luar_kantor'][$m] ?? 0),
            ];
        }

        // Mode summary for pie (current month)
        $modeSummary = [
            'dinas' => $kantorDays,
            'wfh' => $luarDays,
            'hadir' => $presentDays,
        ];

        // Map today's status for frontend button visibility
        $todayStatus = $todayRow ? [
            'location' => $todayRow->location_text ?: $todayRow->location_type,
            'time_in' => $todayRow->time_in ? Carbon::parse($todayRow->time_in, $tz)->format('H:i') : null,
            'time_out' => $todayRow->time_out ? Carbon::parse($todayRow->time_out, $tz)->format('H:i') : null,
            'can_checkout' => $todayRow->time_in && !$todayRow->time_out,
        ] : null;

        return response()->json([
            // Keep current shape (for potential other consumers)
            'year' => $year,
            'months' => $months,
            'series' => [
                'hadir' => array_values($series['hadir']),
                'kantor' => array_values($series['kantor']),
                'luar_kantor' => array_values($series['luar_kantor']),
            ],
            'current_month' => [
                'present_days' => $presentDays,
                'kantor_days' => $kantorDays,
                'luar_kantor_days' => $luarDays,
                'late_count' => $lateCount,
            ],

            // Fields expected by dashboard frontend
            'stats' => [
                'total_work_days' => $totalWorkDays,
                'days_present' => $presentDays,
                'remote_days' => $luarDays,
                'late_days' => $lateCount,
            ],
            'monthly_data' => $monthlyData,
            'mode_summary' => $modeSummary,
            'today_status' => $todayStatus,
        ]);
    }
}
