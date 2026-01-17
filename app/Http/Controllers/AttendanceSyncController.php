<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
// use Illuminate\Support\Carbon; // not used
use Carbon\Carbon;

class AttendanceSyncController extends Controller
{
    /**
     * Generate device ID from request headers and user agent
     */
    private function getDeviceFingerprint(Request $request): string
    {
        $userAgent = $request->header('User-Agent', '');
        $ip = $request->ip();
        
        // Create a unique device ID based on user agent and IP hash
        return substr(md5($userAgent . $ip . $request->user()->id), 0, 20);
    }
    
    /**
     * Generate more detailed device fingerprint
     */
    private function generateDeviceFingerprint(Request $request): string
    {
        $userAgent = $request->header('User-Agent', '');
        $ip = $request->ip();
        $acceptLanguage = $request->header('Accept-Language', '');
        $accept = $request->header('Accept', '');
        
        return md5($userAgent . $ip . $acceptLanguage . $accept . date('Y-m-d'));
    }

    /**
     * Build a Carbon datetime based on today's date in the given timezone
     * and the provided client_time (HH:MM:SS). Fallback to now($tz).
     */
    private function resolveClientDateTime(?string $clientTime, string $tz): Carbon
    {
        try {
            if ($clientTime && preg_match('/^\d{2}:\d{2}(?::\d{2})?$/', $clientTime)) {
                // Normalize to HH:MM:SS
                if (strlen($clientTime) === 5) { // HH:MM
                    $clientTime .= ':00';
                }
                $today = now($tz)->toDateString();
                $dt = Carbon::createFromFormat('Y-m-d H:i:s', $today . ' ' . $clientTime, $tz);
                return $dt ?: now($tz);
            }
        } catch (\Throwable $e) {
            // ignore and fallback
        }
        return now($tz);
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get device fingerprint
        $deviceId = $this->getDeviceFingerprint($request);
        $deviceFingerprint = $this->generateDeviceFingerprint($request);

        $request->validate([
            'location_type' => 'required|in:kantor,luar_kantor',
            'client_time' => 'nullable|string', // expected format HH:MM:SS
        ]);

        $tz = config('app.timezone') ?: 'UTC';
        $today = now($tz)->toDateString();
        $location = $request->input('location_type');

        // Check if user already has active attendance from another device today
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->where('time_in', '!=', null)
            ->where('time_out', null)
            ->where(function($query) use ($deviceId, $deviceFingerprint) {
                $query->where('device_id', '!=', $deviceId)
                      ->orWhere('device_fingerprint', '!=', $deviceFingerprint);
            })
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'error' => 'Anda sudah melakukan check-in dari device lain. Silakan check-out terlebih dahulu.',
                'existing_device' => $existingAttendance->device_id,
                'existing_time' => $existingAttendance->time_in,
                'conflict' => true
            ], 409); // 409 Conflict
        }

        // Find or create today's attendance for this user and device
        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => $user->id, 
                'date' => $today,
                'device_id' => $deviceId
            ],
            [
                'status' => 'Hadir',
                'location_type' => $location,
                'device_fingerprint' => $deviceFingerprint,
                'last_activity_at' => now(),
            ]
        );

        // If already checked in, keep original time_in
        if (!$attendance->time_in) {
            $attendance->time_in = $this->resolveClientDateTime($request->input('client_time'), $tz);
        }
        
        // Always update device tracking and location
        $attendance->device_id = $deviceId;
        $attendance->device_fingerprint = $deviceFingerprint;
        $attendance->location_type = $location;
        $attendance->last_activity_at = now();
        
        // Ensure status is set
        if (!$attendance->status) {
            $attendance->status = 'Hadir';
        }

        $attendance->save();

        return response()->json([
            'message' => 'Check-in recorded',
            'attendance_id' => $attendance->id,
            'time_in' => optional($attendance->time_in)->format('H:i:s'),
            'device_id' => $deviceId,
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
            'client_time' => 'nullable|string', // expected format HH:MM:SS
        ]);
        $tz = config('app.timezone') ?: 'UTC';
        $today = now($tz)->toDateString();
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            // If no check-in yet, create then set both times
            $checkoutAt = $this->resolveClientDateTime($request->input('client_time'), $tz);
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'status' => 'Hadir',
                'location_type' => $request->input('location_type'),
                'time_in' => $checkoutAt,
                'time_out' => $checkoutAt,
            ]);
        } else {
            if (!$attendance->time_in) {
                $attendance->time_in = $this->resolveClientDateTime($request->input('client_time'), $tz);
            }
            $attendance->time_out = $this->resolveClientDateTime($request->input('client_time'), $tz);
            // Always set/update location type based on current request context
            $attendance->location_type = $request->input('location_type');
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

        $tz = config('app.timezone') ?: 'UTC';
        $today = now($tz)->toDateString();
        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['status' => 'Hadir', 'location_type' => $request->input('location_type')]
        );


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
        // Always set/update location type based on current request context
        $attendance->location_type = $request->input('location_type');
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
