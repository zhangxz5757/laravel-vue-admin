<?php

namespace App\Utils;

use Prophecy\Exception\Doubler\ClassNotFoundException;

class ContainerUtils
{
    public static $classContainer = [];

    /**
     * @return mixed
     */
    public static function getClass($class, $params = [])
    {
        if (!class_exists($class)) {
            throw new ClassNotFoundException("类不存在", $class);
        }
        if (empty(self::$classContainer[$class])) {
            self::$classContainer[$class] = app($class, $params);
        }
        return self::$classContainer[$class];
    }
}
