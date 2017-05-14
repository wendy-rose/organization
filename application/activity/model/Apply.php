<?php

namespace app\activity\model;


use think\Db;
use think\Model;

class Apply extends Model
{

    public static function tableName()
    {
        return 'apply';
    }

    public static function isApply($cid, $aid, $uid)
    {
        $apply = Db::name(self::tableName())->where([
            'cid' => $cid,
            'aid' => $aid,
            'uid' => $uid
        ])->find();
        return empty($apply) ? false : true;
    }

    public static function addApply($data)
    {
        return Db::name(self::tableName())->insertGetId($data);
    }
}