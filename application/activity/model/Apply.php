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

    public static function getApplyList($uid, $status, $time, $title, $page=1, $limit=5)
    {
        $query = Db::name(self::tableName())->alias('cn')
            ->join('__ACTIVITY__ c', '`cn`.`aid` = `c`.`aid`')
            ->where('cn.uid', $uid);
        if (!empty($time)){
            if ($time == 1){
                $query->where('c.endtime', '<', time());
            }elseif ($time == 2){
                $query->where('c.starttime', '<=', time())->where('c.endtime', '>=', time());
            }else{
                $query->where('c.endtime', '>=', time());
            }
        }
        if (!empty($status)){
            $query->where(['cn.status' => $status]);
        }
        if (!empty($title)){
            $query->where('c.title', 'like', "%{$title}%");
        }
        $queryClone = clone $query;
        $list =  $query->field('c.starttime,c.endtime,c.aid,c.actpic,c.title,c.cid,cn.status')
            ->page("{$page}, {$limit}")
            ->select();
        $count = $queryClone->count();
        return ['list' => $list, 'count' => ($count % $limit == 0) ? ($count / $limit) : ceil($count/$limit)];
    }

    public static function getMyApplyByAid($aid, $uid)
    {
        return Db::name(self::tableName())->where(['aid' => $aid, 'uid' => $uid])->find();
    }

    public static function editMyApply($data)
    {
        return Db::name(self::tableName())->where(['aid' => $data['aid'], 'cid' => $data['cid']])->update($data);
    }

    public static function getApplyById($id)
    {
        return Db::name(self::tableName())->where(['id' => $id])->find();
    }
}