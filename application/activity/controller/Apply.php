<?php

namespace app\activity\controller;

use app\activity\model\Activity;
use app\activity\model\Info;
use app\index\controller\Base;
use app\user\model\User;
use think\Session;
use app\activity\model\Apply as ApplyModel;
use app\activity\util\Apply as ApplyUtil;
use think\Url;
use think\View;

class Apply extends Base
{

    public function pass()
    {
        $id = request()->post('id');
        $corp = Session::get('corp');
        ApplyModel::passApply($id, $corp['cid']);
        return $this->ajaxReturn(true, '操作成功');
    }

    public function back()
    {
        $id = request()->post('id');
        $corp = Session::get('corp');
        $reason = request()->post('reason');
        ApplyModel::backApply($id, $corp['cid'], $reason);
        $idArray = explode(',', $id);
        foreach ($idArray as $value){
            $apply = ApplyModel::getApplyById($value);
            $url =Url::build('/activity/apply/my', array('aid' => $apply['aid']));
            $content = "<a href='{$url}'>". $value['reason']. "</a>";
            Info::addInfo($apply['uid'], $content, $apply['cid'], 1);
        }
        return $this->ajaxReturn(true, '操作成功');
    }

    public function export()
    {
        $aid = request()->get('aid');
        $name = Activity::getField($aid, 'title');
        $header=['真实姓名','专业班级', '手机号', '邮箱', '报名时间', '备注'];
        $datas = ApplyModel::getExportData($aid);
        $lists = [];
        if (!empty($datas)){
            foreach ($datas as $data){
                $data['createtime'] = date('Y-m-d H:i', $data['createtime']);
                $lists[] = $data;
            }
        }
        excelExport($name,$header,$lists);
    }

    public function my()
    {
        if (request()->isAjax()){
            $request = request()->get();
            $uid = User::getUid();
            $lists = ApplyModel::getApplyList($uid, $request['status'], $request['time'], $request['title'], $request['page']);
            $apply = [];
            if (!empty($lists['list'])){
                foreach ($lists['list'] as $list){
                    $list['starttime'] = date('Y-m-d H:i', $list['starttime']);
                    $list['endtime'] = date('Y-m-d H:i', $list['endtime']);
                    $list['status'] = ApplyUtil::getStatusText($list['status']);
                    $apply[] = $list;
                }
            }
            return $this->ajaxReturn(true, '', $apply, ['count' => $lists['count'], 'page' => $request['page']]);
        }else{
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
            return $view->fetch();
        }
    }

    public function getMyApply()
    {
        $aid = request()->post('aid');
        $uid = User::getUid();
        $apply = ApplyModel::getMyApplyByAid($aid, $uid);
        return $this->ajaxReturn(true, '', $apply);
    }

    public function editApply()
    {
        $request = request()->post();
        ApplyModel::editMyApply($request);
        return $this->ajaxReturn(true, '报名成功');
    }
}