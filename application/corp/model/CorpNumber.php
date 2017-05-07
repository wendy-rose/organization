<?php

namespace app\corp\model;

use think\Db;
use think\Model;

class CorpNumber extends Model
{

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
        return Db::name('corp_number')->insertGetId($number);
    }

    public static function countNumberByCid($cid)
    {
        return Db::name('corp_number')->where('cid', $cid)->count();
    }

    public static function joinCorp($uid)
    {
        return Db::name('corp_number')->alias('cn')
            ->field('cn.createtime,c.name.c.cid,c.belong')
            ->join('__CORP__ c', 'cn.cid = c.cid')
            ->where('cn.uid', $uid)
            ->select();
    }
}