<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 系统菜单表
 */
class SysMenuModel extends BaseModel
{
    use SoftDeletes;

    protected $table = 'sys_menu';

}
