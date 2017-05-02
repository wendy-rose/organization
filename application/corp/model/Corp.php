<?php

namespace app\corp\model;

use think\Db;
use think\Model;

class Corp extends Model
{

    public static function addCorp($fields)
    {
        return Db::name('dept')->insertGetId($fields);
    }

    public static function getCorp($cid)
    {
        return static::get(['cid' => $cid]);
    }
}