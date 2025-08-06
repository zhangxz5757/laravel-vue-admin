<?php

namespace App\Utils;

class CommonUtils
{
    public static function checkMobile($mobile)
    {
        return preg_match('/^1\d{10}$/', $mobile);
    }
}