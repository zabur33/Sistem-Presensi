<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecapController extends Controller
{
    protected function baseRecapQuery(int $year, int $month, ?string $location, ?string $status)
    {
        $lateThreshold = '08:00:00';

        $attendanceQuery = Attendance::query()
            ->select([
                'attendances.user_id',
                DB::raw("SUM(CASE WHEN status='Hadir' THEN 1 ELSE 0 END) as hadir"),
                DB::raw("SUM(CASE WHEN status='Tanpa Keterangan' THEN 1 ELSE 0 END) as tanpa_keterangan"),
                DB::raw("SUM(CASE WHEN status='Hadir' AND time_in > '".$lateThreshold."' THEN 1 ELSE 0 END) as terlambat"),
            ])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->when(in_array($location, ['kantor','luar_kantor'], true), function ($q) use ($location) {
                $q->where('location_type', $location);
            })
            ->groupBy('attendances.user_id');

        $overtimeQuery = DB::table('overtime_requests')
            ->select([
                'overtime_requests.user_id',
                DB::raw('COUNT(*) as lembur'),
            ])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', 'approved')
            ->groupBy('overtime_requests.user_id');

        $query = DB::query()
            ->fromSub($attendanceQuery, 'recaps')
            ->leftJoinSub($overtimeQuery, 'ot_counts', 'recaps.user_id', '=', 'ot_counts.user_id')
            ->join('employees', 'employees.user_id', '=', 'recaps.user_id')
            ->join('users', 'users.id', '=', 'recaps.user_id')
            ->select([
                'users.name', 'users.email',
                'employees.nip', 'employees.position', 'employees.division',
                'recaps.hadir', 'recaps.tanpa_keterangan', 'recaps.terlambat',
                DB::raw('COALESCE(ot_counts.lembur, 0) as lembur'),
            ]);

        return $query->when($status, function ($q) use ($status) {
            switch ($status) {
                case 'Hadir':
                    $q->where('recaps.hadir', '>', 0);
                    break;
                case 'Terlambat':
                    $q->where('recaps.terlambat', '>', 0);
                    break;
                case 'Tanpa Keterangan':
                    $q->where('recaps.tanpa_keterangan', '>', 0);
                    break;
                case 'Lembur':
                    $q->where('ot_counts.lembur', '>', 0);
                    break;
            }
        });
    }

    public function index(Request $request)
    {
        $year = (int)($request->query('year') ?: now()->year);
        $month = (int)($request->query('month') ?: now()->month);
        $location = $request->query('location'); // kantor | luar_kantor | null
        $status = $request->query('status'); // Hadir | Terlambat | Tanpa Keterangan | Lembur | null

        $rows = $this->baseRecapQuery($year, $month, $location, $status)
            ->orderBy('users.name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.rekap-pegawai', [
            'rows' => $rows,
            'year' => $year,
            'month' => $month,
            'location' => $location,
            'status' => $status,
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $year = (int)($request->query('year') ?: now()->year);
        $month = (int)($request->query('month') ?: now()->month);
        $location = $request->query('location');
        $status = $request->query('status');

        $filename = "rekap-pegawai-{$year}-".str_pad((string)$month,2,'0',STR_PAD_LEFT).".csv";

        $query = $this->baseRecapQuery($year, $month, $location, $status)->orderBy('users.name');
        $rows = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nama','Email','NIP','Divisi','Jabatan','Hadir','Terlambat','Tanpa Keterangan','Lembur']);
            foreach ($rows as $r) {
                fputcsv($handle, [
                    $r->name, $r->email, $r->nip, $r->division, $r->position,
                    $r->hadir, $r->terlambat, $r->tanpa_keterangan, $r->lembur,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $year = (int)($request->query('year') ?: now()->year);
        $month = (int)($request->query('month') ?: now()->month);
        $location = $request->query('location');
        $status = $request->query('status');

        $rows = $this->baseRecapQuery($year, $month, $location, $status)
            ->orderBy('users.name')
            ->get();

        return view('admin.print.rekap-pegawai', compact('rows','year','month','location','status'));
    }
}
