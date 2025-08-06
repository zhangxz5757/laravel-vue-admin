<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 角色菜单关联表
 */
class SysRoleMenuModel extends BaseModel
{
    use SoftDeletes;

    protected $table = 'sys_role_menu';
}
