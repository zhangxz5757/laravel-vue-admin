<?php

namespace App\Helper;

use Illuminate\Support\Arr;

/**
 * 递归助手
 *
 * @package App\Helpers
 */
class Recursion
{
    /**
     * Tree
     *
     * @param  array  $items
     * @param  int    $pid
     * @param  null   $sortKey
     * @param  int    $sortOrder
     * @return array
     */
    public static function tree($items = [], $pid = 0, $sortKey = null, $sortOrder = SORT_DESC)
    {
        $result = [];
        foreach ($items as $k => $item) {
            if ($item['pid'] == $pid) {
                $result[$k] = $item;
                unset($items[$k]);
                $result[$k]['children'] = self::tree($items, $item['id'], $sortKey, $sortOrder);
            }
        }
        if ($sortKey) {
            array_multisort(array_column($result, $sortKey), $sortOrder, $result);
        }
        return array_values($result);
    }

    /**
     * 获取IDs
     *
     * @param  array  $items
     * @param  int    $pid
     * @return array
     */
    public static function ids($items = [], $pid = 0)
    {
        $ids = [];
        foreach ($items as $item) {
            if ($item['pid'] == $pid) {
                $ids[] = $item['id'];
                $ids = array_merge($ids, self::ids($items, $item['id']));
            }
        }
        return array_unique($ids);
    }

    /**
     * 获取列表
     *
     * @param  array         $items
     * @param  int           $pid
     * @param  string|array  $column
     * @return array
     */
    public static function lists($items = [], $pid = 0, $column = '*')
    {
        $lists = [];
        foreach ($items as $item) {
            if ($item['pid'] == $pid) {
                $lists[] = $column === '*' ? $item : Arr::only($item->toArray(), $column);
                $lists = array_merge($lists, self::lists($items, $item['id'], $column));
            }
        }
        return $lists;
    }

    /**
     * 逆向解析 tree => array
     *
     * @param  array   $items
     * @param  string  $key
     * @return array
     */
    public static function reverse($items = [], $key = 'children')
    {
        $array = [];
        foreach ($items as $item) {
            $array[] = $item;
            if (!empty($item[$key])) {
                $array = array_merge($array, self::reverse($item[$key]));
            }
        }
        return $array;
    }
}
