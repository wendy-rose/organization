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
            'status' => $status
        ];
        return Db::name('corp_number')->insertGetId($number);
    }
}