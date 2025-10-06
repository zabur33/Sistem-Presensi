<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $division = $request->query('division');
        $active = $request->query('active'); // '1' | '0' | null

        $employees = Employee::query()->with('user')
            ->when($q, function ($query) use ($q) {
                $query->whereHas('user', function ($uq) use ($q) {
                    $uq->where('name', 'like', "%$q%")
                       ->orWhere('email', 'like', "%$q%");
                })->orWhere('division', 'like', "%$q%")
                  ->orWhere('position', 'like', "%$q%");
            })
            ->when($division, fn($qr) => $qr->where('division', $division))
            ->when($active !== null && $active !== '', fn($qr) => $qr->where('active', (bool)$active))
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Untuk filter dropdown division sederhana (ambil distinct)
        $divisions = Employee::query()->select('division')->distinct()->pluck('division')->filter()->values();

        return view('admin.kelola-pegawai', compact('employees', 'divisions', 'q', 'division', 'active'));
    }
}
