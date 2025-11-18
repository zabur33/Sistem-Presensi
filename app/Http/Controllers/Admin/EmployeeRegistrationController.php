<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeRegistrationController extends Controller
{
    public function create()
    {
        return view('admin.registrasi-pegawai');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')],
            'password' => ['required','confirmed','min:6'],
            'nip' => ['nullable','string','max:50', Rule::unique('employees','nip')],
            'gender' => ['nullable','in:L,P'],
            'birth_date' => ['nullable','date'],
            'address' => ['nullable','string','max:255'],
            'position' => ['nullable','string','max:100'],
            'phone' => ['nullable','string','max:50'],
            'division' => ['nullable','string','max:100'],
            'is_admin' => ['nullable','boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => (bool)($validated['is_admin'] ?? false),
        ]);

        Employee::create([
            'user_id' => $user->id,
            'nip' => $validated['nip'] ?? null,
            'position' => $validated['position'] ?? null,
            'division' => $validated['division'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'avatar_url' => null,
            'active' => true,
        ]);

        return back()->with('success', 'Pegawai berhasil diregistrasi!');
    }
}
