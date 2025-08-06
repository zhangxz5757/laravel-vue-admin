<?php

namespace App\Http\Services;

use App\Models\SysDictDataModel;

class DictService
{
    public static $dictArr = [];


    public function initDictArr()
    {
        $list = SysDictDataModel::query()->get();
        $retData = [];

        foreach ($list as $item) {
            if (!isset($retData[$item->alias])) {
                $retData[$item->alias] = [];
            }

            $retData[$item->alias][$item->value] = $item->label;
        }
        self::$dictArr = $retData;
    }

    public function getLabel($alias, $value)
    {
        if (empty(self::$dictArr)) {
            $this->initDictArr();
        }

        return self::$dictArr[$alias][$value] ?? null;
    }


    public function checkValueInSet($alias, $value)
    {
        if (empty(self::$dictArr)) {
            $this->initDictArr();
        }
        if (!isset(self::$dictArr[$alias])) {
            return false;
        }

        return in_array($value, self::$dictArr[$alias]);
    }

    public function getValue($alias, $value)
    {
        if (empty(self::$dictArr)) {
            $this->initDictArr();
        }
        if (!isset(self::$dictArr[$alias])) {
            return null;
        }
        $key = array_search($value, self::$dictArr[$alias]);
        return $key !== false ? $key : null;
    }
}