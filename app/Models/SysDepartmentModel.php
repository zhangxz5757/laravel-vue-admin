<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 部门信息表
 */
class SysDepartmentModel extends BaseModel
{
    use SoftDeletes;

    protected $table = 'sys_department';
}
