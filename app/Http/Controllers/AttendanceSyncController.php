<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
// use Illuminate\Support\Carbon; // not used

class AttendanceSyncController extends Controller
{
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'location_type' => 'required|in:kantor,luar_kantor',
            'client_time' => 'nullable|string',
        ]);

        $today = now()->toDateString();
        $location = $request->input('location_type');

        // Find or create today's attendance for this user
        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            [
                'status' => 'Hadir',
                'location_type' => $location,
            ]
        );

        // If already checked in, keep original time_in
        if (!$attendance->time_in) {
            $attendance->time_in = now();
        }
        // Update location if not set yet
        if (!$attendance->location_type) {
            $attendance->location_type = $location;
        }
        // Ensure status is set
        if (!$attendance->status) {
            $attendance->status = 'Hadir';
        }

        $attendance->save();

        return response()->json([
            'message' => 'Check-in recorded',
            'attendance_id' => $attendance->id,
            'time_in' => optional($attendance->time_in)->format('H:i:s'),
        ]);
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'location_type' => 'required|in:kantor,luar_kantor',
            'client_time' => 'nullable|string',
        ]);

        $today = now()->toDateString();
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            // If no check-in yet, create then set both times
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'status' => 'Hadir',
                'location_type' => $request->input('location_type'),
                'time_in' => now(),
                'time_out' => now(),
            ]);
        } else {
            if (!$attendance->time_in) {
                $attendance->time_in = now();
            }
            $attendance->time_out = now();
            if (!$attendance->location_type) {
                $attendance->location_type = $request->input('location_type');
            }
            if (!$attendance->status) {
                $attendance->status = 'Hadir';
            }
            $attendance->save();
        }

        return response()->json([
            'message' => 'Check-out recorded',
            'attendance_id' => $attendance->id,
            'time_out' => optional($attendance->time_out)->format('H:i:s'),
        ]);
    }

    public function proof(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'photo' => 'nullable|image|max:5120', // up to ~5MB; nullable to allow metadata-only updates
            'location_text' => 'nullable|string|max:255',
            'activity_text' => 'nullable|string',
            'location_type' => 'required|in:kantor,luar_kantor',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric|min:0',
        ]);

        $today = now()->toDateString();
        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['status' => 'Hadir', 'location_type' => $request->input('location_type')]
        );

        if (!$attendance->time_in) {
            $attendance->time_in = now();
        }

        // Only store photo for luar_kantor
        if ($request->input('location_type') === 'luar_kantor' && $request->hasFile('photo')) {
            $path = $request->file('photo')->store('attendances', 'public');
            $attendance->photo_path = $path;
        }
        // Update location_text if provided
        if ($request->filled('location_text')) {
            $attendance->location_text = $request->input('location_text');
        }
        // Store geo if provided
        if ($request->filled('lat')) { $attendance->lat = $request->input('lat'); }
        if ($request->filled('lng')) { $attendance->lng = $request->input('lng'); }
        if ($request->filled('accuracy')) { $attendance->accuracy = $request->input('accuracy'); }
        // Only luar_kantor should store activity_text
        if ($request->input('location_type') === 'luar_kantor' && $request->filled('activity_text')) {
            $attendance->activity_text = $request->input('activity_text');
        }
        if (!$attendance->location_type) {
            $attendance->location_type = $request->input('location_type');
        }
        if (!$attendance->status) {
            $attendance->status = 'Hadir';
        }
        $attendance->save();

        return response()->json([
            'message' => 'Proof updated',
            'photo_path' => $attendance->photo_path,
        ]);
    }
}
