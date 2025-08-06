<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;


class AdminBaseController extends BaseController
{

    const SUPER_ADMIN_GROUP_ID = 1;

    /**
     * 是否超级管理员
     *
     * @return bool
     */
    protected function isSuper()
    {
        if (!Auth::guard('admin')->check()) {
            return false;
        }

        $admin = Auth::guard('admin')->user();

        return $admin->is_super == self::SUPER_ADMIN_GROUP_ID;
    }
}
