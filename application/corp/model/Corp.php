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

    public static function updateNumByCid($cid, $isadd = true)
    {
        $num = Db::name(self::tableName())->where(['cid' => $cid])->value('num');
        if ($isadd){
            $num = $num +1;
        }else{
            $num = $num - 1;
        }
        return Db::name(self::tableName())->where(['cid' => $cid])->update(['num' => $num]);
    }

    public static function getCorpList($type, $num, $belong, $corpname, $page = 1, $limit = 8)
    {
        $query = Db::name(self::tableName());
        if (!empty($type)){
            $query->where(['type' => $type]);
        }

        if (!empty($belong)){
            $query->where(['belong' => $belong]);
        }

        if (!empty($corpname)){
            $query->where('corpname', 'like', "%{$corpname}%");
        }

        if ($num == 1){
            $query->order(['createtime' => 'desc', 'num' => 'desc']);
        }else{
            $query->order(['createtime' => 'desc', 'num' => 'asc']);
        }

        $queryClone = clone $query;
        $list = $query->page("{$page}, {$limit}")->select();
        $count = $query->count();
        return ['list' => $list, 'count' => ($count % $limit == 0) ? ($count / $limit) : ceil($count/$limit)];
    }

    public static function updateCorpByCid($cid, $data)
    {
        return Db::name(self::tableName())->where(['cid' => $cid])->update($data);
    }
}