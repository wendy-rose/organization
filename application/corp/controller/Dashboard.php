<?php

namespace app\corp\controller;

use think\Controller;

class Dashboard extends Controller
{

    public function index()
    {
        if (request()->isAjax()) {

        }else{
            return $this->fetch();
        }
    }

    public function manager()
    {
        return $this->fetch();
    }

    public function dept()
    {
        return $this->fetch();
    }
}