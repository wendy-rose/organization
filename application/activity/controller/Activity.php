<?php
namespace app\activity\controller;

use app\index\controller\Base;

class Activity extends Base
{
    public function add()
    {
        if (request()->isAjax()){
            $activity = request()->post();
            var_dump($activity);die;
        }else{
            return $this->fetch();
        }
    }

    public function index()
    {
        $nowpage = request()->get('page');
        $status = request()->get('status');
        $time = request()->get('time');
        $title = request()->get('tile');
        $array = [
            ['id' => 1, 'name' => 'wendy'],
            ['id' => 2, 'name' => 'rose'],
            ['id' => 3, 'name' => 'test'],
        ];
        return $this->ajaxReturn(true, '', $array, ['allpage' => 3, 'nowpage' => $nowpage]);
    }
}
