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
        try {
            $user = Auth::user();
            
            // Admin update route: lock every field except password
            if ($request->routeIs('admin.profile.update')) {
                $validated = $request->validate([
                    'password' => ['required','confirmed','min:6'],
                ], [
                    'password.required' => 'Password baru wajib diisi.',
                    'password.confirmed' => 'Konfirmasi password tidak sama.',
                    'password.min' => 'Password minimal :min karakter.',
                ]);

                $user->password = Hash::make($validated['password']);
                $user->save();

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => true, 'message' => 'Password berhasil diperbarui']);
                }

                return redirect()->route('admin.profile')->with('success', 'Password berhasil diperbarui');
            }

            $employee = $user->employee; // may be null if not created yet

            // Validate non-admin updates: only password & avatar allowed
            $validated = $request->validate([
                'password' => ['nullable','confirmed','min:6'],
                'avatar' => ['nullable','image','max:2048'],
            ], [
                'password.confirmed' => 'Konfirmasi password tidak sama.',
                'password.min' => 'Password minimal :min karakter.',
                'avatar.image' => 'Avatar harus berupa gambar yang valid.',
                'avatar.max' => 'Ukuran avatar maksimal 2MB.',
            ]);

            // Ensure employee relation exists if admin/user starts filling employee fields
            if (!$employee && $request->hasFile('avatar')) {
                $employee = new Employee();
                $employee->user_id = $user->id;
                $employee->active = true;
                $employee->save();
            }

            // Handle avatar upload
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                try {
                    $path = $request->file('avatar')->store('avatars', 'public');
                    if ($employee) {
                        $employee->avatar_url = $path; // store relative path under storage/app/public
                        $employee->save();
                    }
                } catch (\Exception $e) {
                    \Log::error('Avatar upload failed: ' . $e->getMessage());
                    // Continue with other updates even if avatar fails
                }
            }

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui']);
            }
            return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
            
        } catch (\Exception $e) {
            \Log::error('Profile update failed: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui profil. Silakan coba lagi.'], 500);
            }
            return back()->with('error', 'Gagal memperbarui profil. Silakan coba lagi.');
        }
    }
}
