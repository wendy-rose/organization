<?php
namespace app\activity\controller;

use app\index\controller\Base;

class Activity extends Base
{
    public function add()
    {
        if (request()->isAjax()){
            $activity = request()->post();

        }else{
            return $this->fetch();
        }
    }
}
