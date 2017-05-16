<?php
/**
 * @link http://api.ibos.cn/
 * @copyright Copyright (c) 2016 IBOS Inc
 */

namespace app\activity\util;


class Apply
{

    public static function getAllStatus()
    {
        return [
            1 => '被退回',
            2 => '已通过',
            3 => '未审核',
        ];
    }

    public static function getStatusText($status)
    {
        $all = self::getAllStatus();
        return isset($all[$status]) ? $all[$status] : end($all);
    }
}