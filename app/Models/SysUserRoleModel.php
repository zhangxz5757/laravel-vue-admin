<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 管理员角色关联表
 */
class SysUserRoleModel extends BaseModel
{
    use SoftDeletes;

    protected $table = 'sys_user_role';
}
