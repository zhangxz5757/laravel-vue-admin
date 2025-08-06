<?php

namespace App\Http\Middleware;

use App\Models\SysLogModel;
use Illuminate\Support\Facades\Auth;
use Closure;

/*
 * 系统日志
 */
class SysLog
{
    public function handle($request, Closure $next)
    {
        $actionName = request()->getPathInfo();

        SysLogModel::query()->create([
            'url' => $actionName,
            'params' => json_encode($request->all()),
            'user_id' => Auth::guard('admin')->id()
        ]);

        return $next($request);
    }
}
