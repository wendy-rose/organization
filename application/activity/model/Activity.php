<?php
namespace app\activity\model;

use think\Db;
use think\Model;
use app\activity\util\Activity as ActivityUtil;

class Activity extends Model
{

    public static function tableName()
    {
        return 'activity';
    }

    public static function addActivity($fields)
    {
        return Db::name(self::tableName())->insertGetId($fields);
    }

    public static function getActivityList($type, $time, $title, $page = 1, $limit = 4)
    {

        $query =  Db::name(self::tableName())->where('status', '=', 1)->order('createtime DESC');
        if (!empty($type)){
            $query->where('type', '=', $type);
        }
        if (!empty($time)){
            $rangeTime = ActivityUtil::getTimerRange($time);
            $query->where('starttime', '>=', $rangeTime['starttime']);
            $query->where('starttime', '<=', $rangeTime['endtime']);
        }
        if (!empty($title)){
            $query->where('title', 'like', "%{$title}%");
        }
        $list = $query->page("{$page}, {$limit}")
            ->select();
        $count = $query->count();
        return ['list' => $list, 'count' => ($count % 4 == 0) ? ($count / 4) : ceil($count/4)];
    }

    public  static function getActivityByAid($aid)
    {
        return Db::name(self::tableName())->where(['aid' => $aid])->find();
    }

    public static function addCountLikes($aid)
    {
        $likes = Db::name(self::tableName())->where(['aid' => $aid])->value('likes');
        return Db::name(self::tableName())->where(['aid' => $aid])->update(['likes' => $likes + 1]);
    }

    public static function resetCountLikes($aid)
    {
        $likes = Db::name(self::tableName())->where(['aid' => $aid])->value('likes');
        return Db::name(self::tableName())->where(['aid' => $aid])->update(['likes' => $likes - 1]);
    }
}