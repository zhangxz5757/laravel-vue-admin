<?php

namespace App\Utils;

use App\Models\SysConfigModel;

class ConfigUtils
{
    public static function getValue($name, $default = null)
    {
        $config = SysConfigModel::where('varname', $name)->first();
        return $config->varvalue ?? $default;
    }
}
