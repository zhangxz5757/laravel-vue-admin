<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 角色信息表
 */
class SysRoleModel extends BaseModel
{
    use SoftDeletes;

    protected $table = 'sys_role';

}
