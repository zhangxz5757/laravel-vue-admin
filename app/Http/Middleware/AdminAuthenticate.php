<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class AdminAuthenticate
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('admin')->check()) {
            return response()->json([
                'code' => 403,
                'msg' => '请重新登录',
                'data' => null
            ]);
        }
        return $next($request);
    }
}
