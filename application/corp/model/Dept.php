<?php

namespace app\corp\model;


use think\Db;
use think\Model;

class Dept extends Model
{

    public static function addDept($cid, $name, $manager = '', $mobile = '', $status = 0, $pid = 0)
   {
       $dept = [
           'cid' => $cid,
           'name' => $name,
           'manager' => $manager,
           'mobile' => $mobile,
           'status' => $status,
           'pid' => $pid
       ];
       return Db::name('dept')->insertGetId($dept);
    }

    public static function getDeptByCid($cid)
    {
        return static::get(['cid' => $cid]);
    }
}