<?php
namespace app\corp\controller;

use app\index\controller\Base;

class Index extends Base
{

    public function make()
    {
        if (request()->isAjax()) {

        }else{
            return $this->fetch();
        }
    }
}
