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
    public static function editActivity($aid, $fields)
    {
        return Db::name(self::tableName())->where(['aid' => $aid])->update($fields);
    }

    public static function getActivityList($type, $time, $title, $page = 1, $limit = 4)
    {

        $query =  Db::name(self::tableName())->where('status', '=', 1);
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
        $query->order('createtime DESC');
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

    public static function getPublishActivity($cid, $type, $status, $title, $likes, $page=1, $limit=8)
    {
        $query = Db::name(self::tableName())->where(['cid' => $cid]);
        if (!empty($type)){
            $query->where(['type' => $type]);
        }
        if (!empty($status)){
            if ($status == 1){
                $query->where('endtime', '<', time());
            }elseif ($status == 2){
                $query->where('starttime', '<=', time())->where('endtime', '>=', time());
            }else{
                $query->where('endtime', '>=', time());
            }
        }
        if (!empty($title)){
            $query->where('title', 'like', "%{$title}%");
        }
        if ($likes == 0){
            $query->order('likes DESC');
        }else{
            $query->order('likes ASC');
        }
        $query->order('createtime DESC');
        $list = $query->page("{$page}, {$limit}")->select();
        $count = $query->count();
        return ['list' => $list, 'count' => ($count % 8 == 0) ? ($count / 8) : ceil($count/8)];
    }

    public static function del($aid)
    {
        return Db::name(self::tableName())->where(['aid' => $aid])->delete();
    }

    public static function getField($aid, $field)
    {
        return Db::name(self::tableName())->where(['aid' => $aid])->value($field);
    }
}