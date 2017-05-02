<?php

namespace app\corp\model;

use think\Db;
use think\Model;

class Position extends Model
{

    public static function addPositionDefault($cid)
    {
        $positions = [
            ['cid' => $cid, 'name' => '会长'],
            ['cid' => $cid, 'name' => '副会长'],
            ['cid' => $cid, 'name' => '部长'],
            ['cid' => $cid, 'name' => '副部长'],
        ];
        Db::name('position')->insertAll($positions);
    }

    public static function getPositionByCid($cid)
    {
        return static::get(['cid' => $cid]);
    }
}