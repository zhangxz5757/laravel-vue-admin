<?php

namespace App\Http\Services;

use App\Models\SysMenuModel;
use App\Utils\TreeUtils;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    public static function getAll()
    {

        return SysMenuModel::query()->get()->toArray();
    }

    public static function getAllTree()
    {
        $menu = self::getAll();
        return TreeUtils::makeTree($menu, [
            'children_key' => 'children'
        ]);
    }
}
