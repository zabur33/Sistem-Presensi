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
            'email' => ['required','email:rfc,dns','max:255', Rule::unique('users','email')],
            'password' => ['required','confirmed','min:6'],
            'nip' => ['required','string','max:50', Rule::unique('employees','nip')],
            'gender' => ['required','in:L,P'],
            'birth_date' => ['required','date','before_or_equal:today'],
            'address' => ['required','string','max:255'],
            'position' => ['required','string','max:100'],
            'phone' => ['required','string','max:50','regex:/^\d+$/'],
            'division' => ['required','string','max:100'],
            'is_admin' => ['nullable','boolean'],
        ], [
            'email.unique' => 'Email sudah terdaftar. Gunakan email lain.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'password.min' => 'Password minimal :min karakter.',
            'birth_date.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nip.required' => 'NIP wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'position.required' => 'Jabatan wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'division.required' => 'Divisi wajib diisi.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => (bool)($validated['is_admin'] ?? false),
        ]);

        Employee::create([
            'user_id' => $user->id,
            'nip' => $validated['nip'],
            'position' => $validated['position'],
            'division' => $validated['division'],
            'phone' => $validated['phone'],
            'avatar_url' => null,
            'active' => true,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pegawai berhasil diregistrasi!']);
        }
        return back()->with('success', 'Pegawai berhasil diregistrasi!');
    }
}
