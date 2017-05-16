<?php
/**
 * @link http://api.ibos.cn/
 * @copyright Copyright (c) 2016 IBOS Inc
 */

namespace app\activity\controller;


use app\activity\model\Activity;
use app\index\controller\Base;
use app\user\model\User;
use app\activity\model\Info as InfoModel;
use think\Url;

class Info extends Base
{

    public function add()
    {
        $uid = User::getUid();
        $cid = request()->post('cid');
        $aid = request()->post('aid');
        $actvity = Activity::getActivityByAid($aid);
        $content = request()->post('content');
        $url = Url::build('activity/activity/number', ['aid' => $aid]);
        if (empty($content)){
            $content = "<a href='{$url}'>你好！请你尽快审核{$actvity['title']}活动的申请！谢谢！</a>";
        }else{
            $content = "<a href='{$url}'>". $content. "</a>";
        }
        InfoModel::addInfo($uid, $content, $cid);
        return $this->ajaxReturn(true, '催办成功');
    }
}