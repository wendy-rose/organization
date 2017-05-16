<?php
namespace app\activity\controller;

use app\activity\model\Apply;
use app\activity\model\Like;
use app\corp\model\Corp;
use app\index\controller\Base;
use app\index\model\FileUpload;
use app\user\model\User;
use think\Session;
use app\activity\model\Activity as ActvityModel;
use app\activity\util\Activity as ActvityUtil;
use think\View;

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
                        $isApply = Apply::isApply($list['cid'], $list['aid'], $uid);
                        if ($isApply){
                            $list['apply'] = false;
                        }else {
                            $list['apply'] = true;
                        }
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
        $uid = User::getUid();
        if ($activity['openapply'] == 1){
            if ($activity['begin']< time() && $activity['end'] >time()) {
                $oldrest = ceil((time()-$activity['begin']) / 86400);
                $allrest = ceil(($activity['end']-$activity['begin']) / 86400);
                $activity['desc'] = (round(($oldrest/$allrest), 2) * 100). '%';
                $restdys = ceil(($activity['end']-time()) / 86400);
                $activity['restdys'] = '剩'. $restdys. '天';
                $isApply = Apply::isApply($activity['cid'], $activity['aid'], $uid);
                if ($isApply){
                    $activity['apply'] = false;
                    $activity['isrest'] = true;
                }else {
                    $activity['apply'] = true;
                    $activity['isrest'] = false;
                }
            }elseif ($activity['begin'] > time()){
                $activity['desc'] = '0%';
                $activity['restdys'] = '未开始';
                $activity['apply'] = false;
                $activity['isrest'] = false;
            }elseif ($activity['end'] < time()) {
                $activity['desc'] = '0%';
                $activity['restdys'] = '已过期';
                $activity['apply'] = false;
                $activity['isrest'] = false;
            }
            $activity['begin'] = date('Y-m-d H:i', $activity['begin']);
            $activity['end'] = date('Y-m-d H:i', $activity['end']);
        }else{
            $activity['desc'] = '0%';
            $activity['restdys'] = '';
            $activity['apply'] = false;
            $activity['isrest'] = false;
        }
        $activity['createtime'] = date('Y-m-d H:i', $activity['createtime']);
        $activity['endtime'] = date('Y-m-d H:i', $activity['endtime']);
        $activity['like'] = Like::countLikeByAid($aid);
        $activity['corpname'] = Corp::getCorpNameByCid($activity['cid']);
        $activity['type'] = ActvityUtil::getTypeText($activity['type']);
        $activity['number'] = empty($activity['number']) ? '不限' : $activity['number'];
        $activity['islike'] = Like::isLike($uid, $activity['aid']);
        $view = new View([
            // 模板引擎类型 支持 php think 支持扩展
            'type'         => 'Think',
            // 模板路径
            'view_path'    => '',
            // 模板后缀
            'view_suffix'  => 'html',
            // 模板文件名分隔符
            'view_depr'    => DS,
            // 模板引擎普通标签开始标记
            'tpl_begin'    => '<{',
            // 模板引擎普通标签结束标记
            'tpl_end'      => '}>',
            // 标签库标签开始标记
            'taglib_begin' => '<{',
            // 标签库标签结束标记
            'taglib_end'   => '}>',
        ]);
        return $view->fetch('detail', ['activity' => $activity]);
    }

    public function like()
    {
        $aid = request()->post('aid');
        $uid = User::getUid();
        Like::addLike($aid, $uid);
        ActvityModel::addCountLikes($aid);
        return $this->ajaxReturn(true, '点赞成功', ['like'=> Like::countLikeByAid($aid)]);
    }

    public function resetLike()
    {
        $aid = request()->post('aid');
        $uid = User::getUid();
        Like::resetLike($uid, $aid);
        ActvityModel::resetCountLikes($aid);
        return $this->ajaxReturn(true, '取消点赞成功', ['like' =>  Like::countLikeByAid($aid)]);
    }
    public function apply()
    {
        $apply = request()->post();
        $apply['uid'] =  User::getUid();
        Apply::addApply($apply);
        return $this->ajaxReturn(true, '正在审核中');
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

    public function search()
    {
        if (request()->isAjax()){
            $request = request()->get();
            $corp = Session::get('corp');
            $lists = ActvityModel::getPublishActivity($corp['cid'], $request['type'], $request['status'], $request['title'], $request['likes'], $request['page']);
            $return = [];
            $time = time();
            if (!empty($lists['list'])){
                foreach ($lists['list'] as $list){
                    $list['countApply'] = Apply::countApply($corp['cid'], $list['aid']);
                    if ($list['starttime'] <= $time && $time <= $list['endtime']){
                        $list['status'] = '进行中';
                    }elseif($time < $list['starttime']){
                        $list['status'] = '未开始';
                    }elseif ($time > $list['endtime']){
                        $list['status'] = '已结束';
                    }
                    $list['starttime'] = date('Y-m-d H:i', $list['starttime']);
                    $return[] = $list;
                }
            }
            return $this->ajaxReturn(true, '', $return, ['count' => $lists['count'], 'page' => $request['page']]);
        }else{
           return $this->fetch();
        }
    }

    public function upload()
    {
        $corpImg = request()->file('corpImg');
        $info = $corpImg->getInfo();
        $validate = ['size'=>2048000,'ext'=>'jpg,png,jpeg'];
        $fileUpload = new FileUpload($corpImg);
        $filePath = $fileUpload->upload($validate);
        if (substr($filePath, 0, 1) != '/') {
            return $this->ajaxReturn(false, $filePath);
        }
        echo json_encode(array(
            'success' => true,
            'thumb' => $filePath,
            'imgname' => $info['name'],
        ));
        exit();
    }

    public function resetApply()
    {
        $aid = request()->post('aid');
        $cid = request()->post('cid');
        Apply::delApply($aid, $cid);
        return $this->ajaxReturn(true, '取消报名成功');
    }

    public function del()
    {
        $aid = request()->post('aid');
        ActvityModel::del($aid);
        return $this->ajaxReturn(true, '删除成功');
    }

    public function edit()
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
            $activity['updatetime'] = time();
            $activity['cid'] = $corp['cid'];
            $activity['uid'] = User::getUid();
            unset($activity['corpImg']);
            ActvityModel::editActivity($activity['aid'], $activity);
            return $this->ajaxReturn(true, '编辑成功');
        }else{
            $aid = request()->get('aid');
            $activity = ActvityModel::getActivityByAid($aid);
            return $this->fetch('edit', $activity);
        }
    }

    public function number()
    {
        if (request()->isAjax()){
            $page = request()->get('page');
            $status = request()->get('status');
            $aid = request()->get('aid');
            $apply = array();
            $lists = Apply::getApplyByAid($aid, $status, $page);
            if (!empty($lists['list'])){
                foreach ($lists['list'] as $list){
                    $list['createtime'] = date('Y-m-d H:i', $list['createtime']);
                    $apply[] = $list;
                }
            }
            return $this->ajaxReturn(true, '', $apply, ['count' => $lists['count'], 'page' => $page]);
        }else{
            return $this->fetch('number', ['aid' => request()->get('aid')]);
        }
    }
}
