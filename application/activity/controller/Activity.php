<?php
namespace app\activity\controller;

use app\index\controller\Base;

class Activity extends Base
{
    public function add()
    {
        if (request()->isAjax()){

        }else{
            return $this->fetch();
        }
    }
}
