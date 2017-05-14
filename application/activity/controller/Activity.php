<?php
namespace app\activity\controller;

use app\activity\model\Apply;
use app\activity\model\Like;
use app\corp\model\Corp;
use app\index\controller\Base;
use app\user\model\User;
use think\Session;
use app\activity\model\Activity as ActvityModel;
use app\activity\util\Activity as ActvityUtil;

class Activity extends Base
{
    public function add()
    {
        if (request()->isAjax()){
            $activity = request()->post();
            $corp = Session::get('corp');
            $activity['starttime'] = strtotime($activity['starttime']);
            $activity['endtime'] = strtotime($activity['endtime']);
            if ($activity['starttime'] < time()){
                return $this->ajaxReturn(false, '活动开始时间不能少于当前时间');
            }
            if ($activity['starttime'] > $activity['endtime']){
                return $this->ajaxReturn(false, '开始时间不能大于结束时间');
            }
            if (isset($activity['openapply'])) {
                $activity['begin'] = strtotime($activity['begin']);
                $activity['end'] = strtotime($activity['end']);
                if ($activity['begin'] > $activity['end']){
                    return $this->ajaxReturn(false, '报名开始时间不能大于报名结束时间');
                }
                if ($activity['begin'] > $activity['starttime'] || $activity['end'] > $activity['starttime']) {
                    return $this->ajaxReturn(false, '报名开始结束时间不能大于活动开始时间');
                }
            }
            $activity['createtime'] =$activity['updatetime'] = time();
            $activity['cid'] = $corp['cid'];
            $activity['uid'] = User::getUid();
            unset($activity['corpImg']);
            ActvityModel::addActivity($activity);
            return $this->ajaxReturn(true, '创建活动成功');
        }else{
            return $this->fetch();
        }
    }

    public function index()
    {
        $page = request()->get('page');
        $type = request()->get('type');
        $time = request()->get('time');
        $title = request()->get('title');
        $lists = ActvityModel::getActivityList($type, $time, $title, $page);
        $activityLists = array();
        $uid = User::getUid();
        if (!empty($lists['list'])) {
            foreach ($lists['list'] as $list) {
                if ($list['openapply'] == 1){
                    if ($list['begin']< time() && $list['end'] >time()) {
                        $oldrest = ceil((time()-$list['begin']) / 86400);
                        $allrest = ceil(($list['end']-$list['begin']) / 86400);
                        $list['desc'] = (round(($oldrest/$allrest), 2) * 100). '%';
                        $restdys = ceil(($list['end']-time()) / 86400);
                        $list['restdys'] = '剩'. $restdys. '天';
                        $list['apply'] = true;
                    }elseif ($list['begin'] > time()){
                        $list['desc'] = '0%';
                        $list['restdys'] = '未开始';
                        $list['apply'] = false;
                    }elseif ($list['end'] < time()) {
                        $list['desc'] = '0%';
                        $list['restdys'] = '已过期';
                        $list['apply'] = false;
                    }
                }else{
                    $list['desc'] = '0%';
                    $list['restdys'] = '';
                    $list['apply'] = false;
                }
                $list['likecount'] = Like::countLikeByAid($list['aid']);
                $list['number'] = empty($list['number']) ? '不限' : $list['number'];
                $list['corpname'] = Corp::getCorpNameByCid($list['cid']);
                $list['like'] = Like::isLike($uid, $list['aid']);
                $activityLists[] = $list;
            }
        }
        return $this->ajaxReturn(true, '', $activityLists, ['allpage' => $lists['count'], 'nowpage' => $page]);
    }

    public function template()
    {
        return $this->fetch();
    }

    public function detail()
    {
        $aid = request()->get('aid');
        $activity = ActvityModel::getActivityByAid($aid);
        if ($activity['openapply'] == 1){
            if ($activity['begin']< time() && $activity['end'] >time()) {
                $oldrest = ceil((time()-$activity['begin']) / 86400);
                $allrest = ceil(($activity['end']-$activity['begin']) / 86400);
                $activity['desc'] = (round(($oldrest/$allrest), 2) * 100). '%';
                $restdys = ceil(($activity['end']-time()) / 86400);
                $activity['restdys'] = '剩'. $restdys. '天';
                $activity['apply'] = true;
            }elseif ($activity['begin'] > time()){
                $activity['desc'] = '0%';
                $activity['restdys'] = '未开始';
                $activity['apply'] = false;
            }elseif ($activity['end'] < time()) {
                $activity['desc'] = '0%';
                $activity['restdys'] = '已过期';
                $activity['apply'] = false;
            }
            $activity['begin'] = date('Y-m-d H:i', $activity['begin']);
            $activity['end'] = date('Y-m-d H:i', $activity['end']);
        }else{
            $activity['desc'] = '0%';
            $activity['restdys'] = '';
            $activity['apply'] = false;
        }
        $activity['createtime'] = date('Y-m-d H:i', $activity['createtime']);
        $activity['endtime'] = date('Y-m-d H:i', $activity['endtime']);
        $activity['like'] = Like::countLikeByAid($aid);
        $activity['corpname'] = Corp::getCorpNameByCid($activity['cid']);
        $activity['type'] = ActvityUtil::getTypeText($activity['type']);
        $activity['number'] = empty($activity['number']) ? '不限' : $activity['number'];
        return $this->fetch('detail', ['activity' => $activity]);
    }

    public function like()
    {
        $aid = request()->post('aid');
        $uid = User::getUid();
        Like::addLike($aid, $uid);
        return $this->ajaxReturn(true, '点赞成功', ['like'=> Like::countLikeByAid($aid)]);
    }

    public function resetLike()
    {
        $aid = request()->post('aid');
        $uid = User::getUid();
        Like::resetLike($uid, $aid);
        return $this->ajaxReturn(true, '取消点赞成功', ['like' =>  Like::countLikeByAid($aid)]);
    }
    public function apply()
    {
        $apply = request()->post();
        Apply::addApply($apply);
        return $this->ajaxReturn(true, '报名成功');
    }

    public function isApply()
    {
        $aid = request()->post('aid');
        $cid = request()->post('cid');
        $uid = User::getUid();
        $isApply = Apply::isApply($cid, $aid, $uid);
        if ($isApply){
            $lang = '你已经报名该活动';
        }else{
            $lang = '';
        }
        return $this->ajaxReturn($isApply, $lang);
    }
}
