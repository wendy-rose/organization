<?php

namespace app\corp\util;


class Corp
{


    public static function getCorpBelong($belong)
    {
        $status= self::corpBelong();
        return isset($status[$belong]) ? $status[$belong] : '';
    }

    public static function corpBelong()
    {
        return [
            1 => '个人',
            2 => '学院',
            3 => '学校',
        ];
    }
}