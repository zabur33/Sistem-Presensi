<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdleTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $timeout = (int) config('session.idle_timeout', 1800);

        if ($timeout > 0 && Auth::check()) {
            $lastActivity = (int) $request->session()->get('last_activity_time', 0);
            $now = now()->getTimestamp();

            if ($lastActivity && ($now - $lastActivity) > $timeout) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Sesi Anda berakhir karena terlalu lama tidak aktif.',
                    ], 419);
                }

                return redirect()->route('login')
                    ->withErrors(['email' => 'Sesi Anda berakhir karena tidak aktif terlalu lama. Silakan login kembali.']);
            }

            $request->session()->put('last_activity_time', $now);
        }

        return $next($request);
    }
}
