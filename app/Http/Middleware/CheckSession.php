<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Retrieve and parse the timestamp from the session
        $lastActiveAt = Carbon::parse(session('last_active_at'));

        // Calculate the difference in minutes
        $diffInMinutes = $lastActiveAt->diffInMinutes(now());

         if (Auth::check() && session('last_active_at') && $diffInMinutes > config('session.lifetime')) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('login')->with('message', 'Your session has expired. Please log in again.');

        } else if (Auth::check()) {
            session(['last_active_at' => now()]);
            return $next($request);

        } else {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('login')->with('message', 'Your session has expired. Please log in again.');

        }

    }
}
