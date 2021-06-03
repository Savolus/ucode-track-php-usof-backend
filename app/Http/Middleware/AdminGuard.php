<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminGuard {
    public function handle(Request $request, Closure $next) {
        $user = Auth::user();

        if (empty($user)) {
            return response([
                'message' => 'User is unauthorized'
            ], 403);
        }

        if ($user['role'] === 'admin') {
            return $next($request);
        }

        return response([
            'message' => 'Access is denied'
        ], 403);
    }
}
