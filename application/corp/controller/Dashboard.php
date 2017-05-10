<?php

namespace app\corp\controller;

use app\corp\model\Corp;
use app\user\model\User;
use think\Controller;
use think\Session;

class Dashboard extends Controller
{

    public function index()
    {
        if (request()->isAjax()) {

        }else{
            $cid = request()->get('cid');
            $corp = Corp::getCorp($cid);
            $number = Session::get('number');
            return $this->fetch('index', ['corp' => $corp, 'number' => $number]);
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