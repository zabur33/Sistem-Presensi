<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee; // may be null if not created yet

        // Validate optional updates
        $validated = $request->validate([
            'name' => ['nullable','string','max:255'],
            'email' => ['nullable','email','max:255'],
            'password' => ['nullable','confirmed','min:6'],
            'gender' => ['nullable','in:L,P'],
            'birth_date' => ['nullable','date','before_or_equal:today'],
            'address' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'position' => ['nullable','string','max:100'],
            'division' => ['nullable','string','max:100'],
        ]);

        // Ensure employee relation exists if admin/user starts filling employee fields
        if (!$employee && (
            $request->filled('gender') || $request->filled('birth_date') || $request->filled('address') ||
            $request->filled('phone') || $request->filled('position') || $request->filled('division') ||
            $request->hasFile('avatar')
        )) {
            $employee = new Employee();
            $employee->user_id = $user->id;
            $employee->active = true;
            $employee->save();
        }

        // Handle avatar upload
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $path = $request->file('avatar')->store('avatars', 'public');
            if ($employee) {
                $employee->avatar_url = $path; // store relative path under storage/app/public
                $employee->save();
            }
        }

        // Optional: update simple fields if present (no validation for brevity)
        if ($employee) {
            $employee->gender = $validated['gender'] ?? $employee->gender;
            $employee->birth_date = $validated['birth_date'] ?? $employee->birth_date;
            $employee->address = $validated['address'] ?? $employee->address;
            $employee->phone = $validated['phone'] ?? $employee->phone;
            $employee->position = $validated['position'] ?? $employee->position;
            $employee->division = $validated['division'] ?? $employee->division;
            $employee->save();
        }

        // Name/email could also be updated
        if (!empty($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (!empty($validated['email'])) {
            $user->email = $validated['email'];
        }
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Redirect back to the same profile page (admin scope)
        if ($request->routeIs('admin.profile.update')) {
            return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui');
        }
        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
    }
}
