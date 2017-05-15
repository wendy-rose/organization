<?php

namespace app\activity\controller;

use app\activity\model\Activity;
use app\index\controller\Base;
use think\Session;
use app\activity\model\Apply as ApplyModel;

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

        }else{
            return $this->fetch();
        }
    }
}