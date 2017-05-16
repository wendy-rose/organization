<?php
/**
 * @link http://api.ibos.cn/
 * @copyright Copyright (c) 2016 IBOS Inc
 */

namespace app\activity\model;


use think\Db;
use think\Model;

class Info extends Model
{

    public static function tableName()
    {
        return 'info';
    }

    public static function addInfo($uid, $content, $cid, $iscorp=0)
    {
        return Db::name(self::tableName())->insertGetId([
            'uid' => $uid,
            'content' => $content,
            'cid' => $cid,
            'iscorp' => $iscorp,
            'createtime' => time(),
        ]);
    }
}