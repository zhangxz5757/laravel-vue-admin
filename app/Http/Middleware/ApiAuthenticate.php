<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class ApiAuthenticate
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('api')->check()) {
            return response()->json([
                'code' => 403,
                'msg' => '未登录',
                'data' => new \stdClass()
            ]);
        }
        return $next($request);
    }
}
