<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OvertimeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'all'); // all | unread
        $q = trim((string) $request->query('q'));
        $start = $request->query('start_date');
        $end = $request->query('end_date');
        $status = $request->query('status'); // pending|approved|rejected|null

        $query = OvertimeRequest::query()->with('user')->latest();

        if ($tab === 'unread') {
            $query->whereNull('read_at');
        }
        if ($q) {
            $query->whereHas('user', function ($uq) use ($q) {
                $uq->where('name', 'like', "%$q%");
            })->orWhere('reason', 'like', "%$q%");
        }
        if ($start) {
            $query->whereDate('date', '>=', $start);
        }
        if ($end) {
            $query->whereDate('date', '<=', $end);
        }
        if (in_array($status, ['pending','approved','rejected'], true)) {
            $query->where('status', $status);
        }

        $items = $query->paginate(12)->withQueryString();

        return view('admin.validasi-lembur', compact('items', 'tab', 'q', 'start', 'end', 'status'));
    }

    public function markRead(OvertimeRequest $overtime)
    {
        if (!$overtime->read_at) {
            $overtime->update(['read_at' => now()]);
        }
        return back();
    }

    public function markAllRead(Request $request)
    {
        OvertimeRequest::whereNull('read_at')->update(['read_at' => now()]);
        return back();
    }

    public function approve(OvertimeRequest $overtime)
    {
        $overtime->update(['status' => 'approved', 'read_at' => $overtime->read_at ?: now()]);
        return back();
    }

    public function reject(OvertimeRequest $overtime)
    {
        $overtime->update(['status' => 'rejected', 'read_at' => $overtime->read_at ?: now()]);
        return back();
    }
}
