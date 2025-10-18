<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OvertimeRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserOvertimeController extends Controller
{
    public function submit(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $data = $request->validate([
            'nama' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:1000',
            'jam' => 'required|string',
            'deskripsi' => 'required|string',
            // base64 fallbacks
            'foto_wajah_data' => 'nullable|string',
            'foto_pendukung_data' => 'nullable|string',
            // optional upload
            'support_file' => 'nullable|file|image|max:5120', // 5MB
        ]);

        // Basic mapping: we store minimal fields used by admin list
        $today = now()->toDateString();
        $startTime = substr($data['jam'], 0, 5) . ':00';
        $endTime = $startTime; // end_time not nullable per migration; set same as start for request stage
        $reason = Str::limit((string)$data['deskripsi'], 255, '');

        $req = OvertimeRequest::create([
            'user_id' => $user->id,
            'date' => $today,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_minutes' => null,
            'reason' => $reason,
            'status' => 'pending',
            'read_at' => null,
        ]);

        // Save face/support photos if provided
        $facePath = null; $supportPath = null;
        if (!empty($data['foto_wajah_data'])) {
            $facePath = $this->storeBase64Image($data['foto_wajah_data'], 'face');
        }
        if ($request->hasFile('support_file')) {
            $supportPath = $request->file('support_file')->store('overtime', 'public');
        } elseif (!empty($data['foto_pendukung_data'])) {
            $supportPath = $this->storeBase64Image($data['foto_pendukung_data'], 'support');
        }

        if ($facePath || $supportPath || !empty($data['alamat'])) {
            $req->update([
                'face_photo_path' => $facePath,
                'support_photo_path' => $supportPath,
                'address' => $data['alamat'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Pengajuan lembur terkirim', 'id' => $req->id]);
    }

    public function notifications(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([], 200);
        }

        // Return latest approved/rejected requests for this user
        $items = OvertimeRequest::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved','rejected'])
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get(['id','status','reason','updated_at']);

        return response()->json($items);
    }

    private function storeBase64Image(string $dataUrl, string $prefix): ?string
    {
        if (!str_contains($dataUrl, ';base64,')) return null;
        [$meta, $content] = explode(';base64,', $dataUrl);
        $ext = 'jpg';
        if (preg_match('/data:image\/(\w+)/', $meta, $m)) {
            $ext = strtolower($m[1]);
            if (!in_array($ext, ['jpg','jpeg','png','webp'])) $ext = 'jpg';
        }
        $bin = base64_decode($content);
        if ($bin === false) return null;
        $name = sprintf('overtime/%s_%s.%s', $prefix, now()->format('Ymd_His').'_'.Str::random(6), $ext);
        Storage::disk('public')->put($name, $bin);
        return $name;
    }
}
