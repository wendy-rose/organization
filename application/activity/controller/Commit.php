<?php

namespace app\activity\controller;


use app\index\controller\Base;
use app\activity\model\Commit as CommitModel;
use app\user\model\User;

class Commit extends Base
{

    public function add()
    {
        $request = request()->post();
        $request['uid'] = User::getUid();
        $request['createtime'] = time();
        CommitModel::addCommit($request);
        return $this->ajaxReturn(true);
    }

    public function getList()
    {
        $aid = request()->get('aid');
        $page = request()->get('page');
        $listsAndCount = CommitModel::getCommitList($aid, $page);
        $return = array();
        if (!empty($listsAndCount['list'])){
            foreach ($listsAndCount['list'] as $list){
                $list['username'] = User::getAttribute('username');
                $list['avt'] = User::getAttribute('avatar');
                $list['createtime'] = date('Y-m-d', $list['createtime']);
                $return[] = $list;
            }
        }
        return $this->ajaxReturn(true, '', $return, ['allpage' => $listsAndCount['count'], 'nowpage' => $page]);
    }
}