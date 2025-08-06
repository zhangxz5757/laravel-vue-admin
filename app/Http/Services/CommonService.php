<?php

namespace App\Http\Services;

use App\Models\SysUserModel;

class CommonService
{
    public function getOwnerDeptIds($userId)
    {
        $user = SysUserModel::query()->find($userId);
        if (!$user || !$user->owner_dept_id) {
            return [];
        }

        return array_filter(explode('|', $user->owner_dept_id)) ?? [];
    }

}
