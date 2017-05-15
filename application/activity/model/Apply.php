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

    public static function delApply($aid, $cid)
    {
        return Db::name(self::tableName())->where(['cid' => $cid, 'aid' => $aid])->delete();
    }

    public static function countApply($cid, $aid)
    {
        return Db::name(self::tableName())->where(['cid' => $cid, 'aid' => $aid])->count();
    }

    public static function getApplyByAid($aid, $status, $page=1, $limit=10)
    {
        $query =  Db::name(self::tableName())->where('aid', '=', $aid)->order('createtime DESC');
        if ($status != 0){
            $query->where(['status' => $status]);
        }

        $list = $query->page("{$page}, {$limit}")
            ->select();
        if ($status != 0){
            $count = $query->where(['status' => $status])->count();
        }else{
            $count = $query->count();
        }
        return ['list' => $list, 'count' => ($count % $limit == 0) ? ($count / $limit) : ceil($count/$limit)];
    }

    public static function passApply($id, $cid)
    {;
        return Db::name(self::tableName())->where('id', 'IN', $id)->where(['cid' => $cid])->update(['status' => 2]);
    }

    public static function backApply($id, $cid, $reason)
    {
        return Db::name(self::tableName())->where('id', 'IN', $id)->where(['cid' => $cid])->update(['status' => 1, 'reason' => $reason]);
    }

    public static function getExportData($aid)
    {
        return Db::name(self::tableName())->field('realname,project,mobile,email,createtime,remark')->where(['aid' => $aid])->select();
    }
}