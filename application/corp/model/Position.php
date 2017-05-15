<?php

namespace app\corp\model;

use think\Db;
use think\Model;

class Position extends Model
{
    public static function tableName()
    {
        return 'position';
    }

    public static function addPositionDefault($cid)
    {
        $positions = [
            ['cid' => $cid, 'name' => '会长'],
            ['cid' => $cid, 'name' => '部长'],
            ['cid' => $cid, 'name' => '普通社员'],
        ];
        Db::name(self::tableName())->insertAll($positions);
    }

    public static function getPositionByCid($cid)
    {
        return Db::name(self::tableName())->where(['cid' => $cid])->select();
    }

    public static function getPositionName($cid, $pid)
    {
        return Db::name(self::tableName())
            ->where(['cid' => $cid, 'pid' => $pid])
            ->value('name');
    }

    public static function getAllPosition($cid)
    {
        return Db::name(self::tableName())
            ->where(['cid' => $cid])
            ->select();
    }
}