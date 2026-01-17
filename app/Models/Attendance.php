<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'time_in', 'time_out', 'status', 'location_type', 'location_text', 'lat', 'lng', 'accuracy', 'photo_path', 'activity_text', 'verification', 'device_id', 'device_fingerprint', 'last_activity_at',
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime:H:i',
        'time_out' => 'datetime:H:i',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDurationMinutesAttribute(): ?int
    {
        if (!$this->time_in || !$this->time_out) return null;
        try {
            $in = \Carbon\Carbon::parse($this->time_in);
            $out = \Carbon\Carbon::parse($this->time_out);
            return $in->diffInMinutes($out, false) >= 0 ? $in->diffInMinutes($out) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
