<?php

namespace app\activity\model;

use think\Db;
use think\Model;

class Commit extends Model
{

    public static function tableName()
    {
        return 'commit';
    }

    public static function getCommitList($aid, $page=1, $limit=5)
    {
        $query = Db::name(self::tableName())->where(['aid' => $aid])
            ->order('createtime DESC');
        $list = $query ->page("{$page}, {$limit}")
            ->select();
        $count = $query->count();
        return ['list' => $list, 'count' => ($count % 4 == 0) ? ($count / 4) : ceil($count/4)];
    }

    public static function addCommit($data)
    {
        return Db::name(self::tableName())->insertGetId($data);
    }
}