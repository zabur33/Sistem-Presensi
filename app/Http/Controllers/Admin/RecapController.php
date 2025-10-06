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

        $query = Attendance::query()
            ->select([
                'attendances.user_id',
                DB::raw("SUM(CASE WHEN status='Hadir' THEN 1 ELSE 0 END) as hadir"),
                DB::raw("SUM(CASE WHEN status='Tanpa Keterangan' THEN 1 ELSE 0 END) as tanpa_keterangan"),
                DB::raw("SUM(CASE WHEN status='Hadir' AND time_in > '".$lateThreshold."' THEN 1 ELSE 0 END) as terlambat"),
                DB::raw('0 as lembur'),
            ])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->when(in_array($location, ['kantor','luar_kantor'], true), function ($q) use ($location) {
                $q->where('location_type', $location);
            })
            ->when(in_array($status, ['Hadir','Tanpa Keterangan'], true), function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->groupBy('attendances.user_id');

        return DB::table(DB::raw("({$query->toSql()}) as recaps"))
            ->mergeBindings($query->getQuery())
            ->join('employees', 'employees.user_id', '=', 'recaps.user_id')
            ->join('users', 'users.id', '=', 'recaps.user_id')
            ->select([
                'users.name', 'users.email',
                'employees.nip', 'employees.position', 'employees.division',
                'recaps.hadir', 'recaps.tanpa_keterangan', 'recaps.terlambat', 'recaps.lembur',
            ]);
    }

    public function index(Request $request)
    {
        $year = (int)($request->query('year') ?: now()->year);
        $month = (int)($request->query('month') ?: now()->month);
        $location = $request->query('location'); // kantor | luar_kantor | null
        $status = $request->query('status'); // Hadir | Tanpa Keterangan | null

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
