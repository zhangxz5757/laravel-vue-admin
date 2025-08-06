<?php

namespace App\Models;


class SysLogModel extends BaseModel
{
    protected $table = "sys_log";

    public function getParamsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function sysUser()
    {
        return $this->belongsTo(SysUserModel::class);
    }
}
