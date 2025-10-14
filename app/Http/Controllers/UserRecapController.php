<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;

class UserRecapController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $query = Attendance::query()
            ->where('user_id', $user->id)
            ->orderByDesc('date');

        // Optional filters (date range, status, location)
        $dateFrom = $request->query('dateFrom');
        $dateTo = $request->query('dateTo');
        $status = $request->query('status');
        $location = $request->query('location'); // kantor | luar_kantor

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }
        if (in_array($status, ['Hadir', 'Tanpa Keterangan'], true)) {
            $query->where('status', $status);
        }
        if (in_array($location, ['kantor','luar_kantor'], true)) {
            $query->where('location_type', $location);
        }

        $rows = $query->limit(200)->get();

        return view('rekap-keseluruhan', [
            'rows' => $rows,
        ]);
    }
}
