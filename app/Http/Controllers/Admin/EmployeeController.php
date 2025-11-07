<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
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

        $showId = $request->query('show');
        $editId = $request->query('edit');
        $selected = null;
        if ($showId || $editId) {
            $selected = Employee::with('user')->find($showId ?: $editId);
        }

        return view('admin.kelola-pegawai', compact('employees', 'divisions', 'q', 'division', 'active', 'selected', 'showId', 'editId'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'division' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'active' => 'nullable|in:0,1',
        ]);

        // Update related user if provided
        if ($employee->user) {
            if (isset($validated['name'])) { $employee->user->name = $validated['name']; }
            if (isset($validated['email'])) { $employee->user->email = $validated['email']; }
            $employee->user->save();
        }

        if (isset($validated['division'])) { $employee->division = $validated['division']; }
        if (isset($validated['position'])) { $employee->position = $validated['position']; }
        if (isset($validated['active'])) { $employee->active = (bool) ((int) $validated['active']); }
        $employee->save();

        return redirect()->route('admin.kelola-pegawai', array_filter(['q'=>$request->query('q'),'division'=>$request->query('division'),'active'=>$request->query('active')]))
            ->with('status', 'Pegawai berhasil diperbarui.');
    }

    public function destroy(Request $request, Employee $employee)
    {
        $employee->delete();
        return redirect()->route('admin.kelola-pegawai', array_filter(['q'=>$request->query('q'),'division'=>$request->query('division'),'active'=>$request->query('active')]))
            ->with('status', 'Pegawai berhasil dihapus.');
    }
}
