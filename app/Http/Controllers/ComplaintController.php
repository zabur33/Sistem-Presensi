<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:5000',
            'location_type' => 'nullable|string|max:100',
            'client_time' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();
        $complaint = Complaint::create([
            'user_id' => $user ? $user->id : null,
            'message' => $data['message'],
            'location_type' => $data['location_type'] ?? null,
            'client_time' => $data['client_time'] ?? null,
            'status' => 'baru',
        ]);

        return response()->json(['ok' => true, 'id' => $complaint->id]);
    }
}
