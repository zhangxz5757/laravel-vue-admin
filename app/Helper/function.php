<?php

use App\Models\SysAreacodeModel;

if (!function_exists('areaList')) {
    function areaList() {
        return SysAreacodeModel::tree();
    }
}

if (!function_exists('getAreaName')) {
    function getAreaName($code) {
        if (!$code) {
            return '';
        }
        return \Illuminate\Support\Facades\Cache::remember('area:'. $code, 86400 * 30 , function () use ($code) {
            $area = SysAreacodeModel::find($code);
            return $area->name ?? '';
        });
    }
}
if (!function_exists('fmtPrice')) {
    /**
     * 格式化金额
     * @param $num
     * @return string
     */
    function fmtPrice($num)
    {
        if ($num == 0) {
            return "0.00";
        }
        return number_format($num, 2, '.', '');
    }
}


