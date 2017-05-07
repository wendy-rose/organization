<?php

namespace app\corp\util;


class Corp
{

    const CORPBELONG= [
        1 => '个人',
        2 => '学院',
        3 => '学校'
    ];

    public static function getCorpBelong($belong)
    {
        $status= self::CORPBELONG;
        return isset($status[$belong]) ? $status[$belong] : '';
    }
}