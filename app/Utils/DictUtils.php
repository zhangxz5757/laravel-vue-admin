<?php

namespace App\Utils;

use App\Models\SysDictDataModel;

class DictUtils
{
    public static function get($alias)
    {
        return SysDictDataModel::query()
            ->where('alias', $alias)
            ->orderBy('sort_id')
            ->where('status', 1)
            ->get(['label', 'value'])
            ->toArray();
    }
}