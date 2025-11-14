<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee; // may be null if not created yet

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
            $employee->gender = $request->input('gender', $employee->gender);
            $employee->birth_date = $request->input('birth_date', $employee->birth_date);
            $employee->address = $request->input('address', $employee->address);
            $employee->phone = $request->input('phone', $employee->phone);
            $employee->position = $request->input('position', $employee->position);
            $employee->division = $request->input('division', $employee->division);
            $employee->save();
        }

        // Name/email could also be updated
        if ($request->filled('name')) {
            $user->name = $request->input('name');
        }
        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }
        $user->save();

        return redirect()->route('profile');
    }
}
