<?php

namespace app\activity\model;


use think\Db;
use think\Model;

class Like extends Model
{

    public static function tableName()
    {
        return 'like';
    }

    public static function countLikeByAid($aid)
    {
        return Db::name(self::tableName())->where('aid', '=', $aid)->count();
    }

    public static function addLike($aid, $uid)
    {
        return Db::name(self::tableName())->insert([
            'aid' => $aid,
            'uid' => $uid
        ]);
    }

    public static function isLike($uid, $aid)
    {
        $islike = Db::name(self::tableName())->where([
            'uid' => $uid,
            'aid' => $aid
        ])->find();
        return empty($islike) ? true : false;
    }

    public static function resetLike($uid, $aid)
    {
        return Db::name(self::tableName())->where([
            'uid' => $uid,
            'aid' => $aid
        ])->delete();
    }
}