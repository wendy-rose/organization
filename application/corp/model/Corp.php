<?php

namespace app\corp\model;

use think\Db;
use think\Model;

class Corp extends Model
{

    public static function tableName()
    {
        return 'corp';
    }

    public static function addCorp($fields)
    {
        return Db::name(self::tableName())->insertGetId($fields);
    }

    public static function getCorp($cid)
    {
        return Db::name(self::tableName())->where('cid', $cid)->find();
    }

    public static function getCorpNameByCid($cid)
    {
        return Db::name(self::tableName())->where('cid', '=', $cid)->value('corpname');
    }
}