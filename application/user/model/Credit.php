<?php

namespace app\user\model;

use think\Model;

class Credit extends Model
{

    public static function getCreditByNumber($credit)
    {
        $creditTye = static::where('low', '<=', $credit)->where('high', '>=', $credit)->value('credit');
        return $creditTye;
    }

    public static function getHighByNumber($number)
    {
        $credit = static::where('low', '<=', $number)->where('high', '>=', $number)->value('high');
        return $credit;
    }
}