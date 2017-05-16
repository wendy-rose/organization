<?php

namespace app\corp\model;

use think\Db;
use think\Model;

class CorpNumber extends Model
{

    public static  function tableName()
    {
        return 'corp_number';
    }

    public static function addNumber($cid, $uid, $username, $email, $password, $mobile, $did, $pid, $status = 0)
    {
        $number = [
            'cid' => $cid,
            'uid' => $uid,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'mobile' => $mobile,
            'deptid' => $did,
            'pid' => $pid,
            'status' => $status,
            'createtime' => time(),
        ];
        return Db::name(static::tableName())->insertGetId($number);
    }

    public static function countNumberByCid($cid)
    {
        return Db::name(static::tableName())->where('cid', $cid)->count();
    }

    public static function getJoinCorp($uid, $offset = 1, $limit = 4)
    {
        $list =  Db::name('corp_number')->alias('cn')
            ->field('cn.createtime,c.corpname,c.cid,cn.deptid,c.corppic,c.belong')
            ->join('__CORP__ c', '`cn`.`cid` = `c`.`cid`')
            ->where('cn.uid', $uid)
            ->page("{$offset}, {$limit}")
            ->select();
        $count = Db::name(static::tableName())->alias('cn')
            ->join('__CORP__ c', '`cn`.`cid` = `c`.`cid`')
            ->where('cn.uid', $uid)
            ->count();
        return ['list' => $list, 'count' => ($count % 4 == 0) ? ($count / 4) : ceil($count/4)];
    }

    public static function getNumber($cid, $email, $password)
    {
        $where = [
            'cid' => $cid,
            'email' => $email,
            'password' => md5($password),
        ];
        $number = Db::name(static::tableName())->where($where)->find();
        return $number;
    }

    public static function deleteNumber($cid, $uid)
    {
        Db::name(static::tableName())->delete([
            'cid' => $cid,
            'uid' => $uid,
        ]);
    }

    public static function isExistByUidAndCid($uid, $cid)
    {
        $number = Db::name(self::tableName())->where(['uid' => $uid, 'cid' => $cid])->find();
        return empty($number) ? false : true;
    }
}