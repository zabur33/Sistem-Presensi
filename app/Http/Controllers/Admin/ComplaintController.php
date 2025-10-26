<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    public function notifications()
    {
        $items = Complaint::with('user')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(function($c){
                return [
                    'id' => $c->id,
                    'user_id' => $c->user_id,
                    'user_name' => optional($c->user)->name ?? 'Pegawai',
                    'message' => $c->message,
                    'status' => $c->status,
                    'created_at' => $c->created_at?->toISOString() ?? now()->toISOString(),
                ];
            });
        return response()->json($items);
    }
}
