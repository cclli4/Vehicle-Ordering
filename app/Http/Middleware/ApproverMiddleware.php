<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApproverMiddleware
{
    public function handle(Request $request, Closure $next)
{
    Log::info('ApproverMiddleware running', [
        'is_authenticated' => Auth::check(),
        'user_role' => Auth::check() ? Auth::user()->role : null
    ]);

    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please login first.');
    }

    if (Auth::user()->role !== 'approver') {
        Log::warning('Unauthorized access attempt', [
            'user_id' => Auth::id(),
            'role' => Auth::user()->role
        ]);
        abort(403, 'Unauthorized. Approver access required.');
    }

    return $next($request);
}
}