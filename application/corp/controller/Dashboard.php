<?php

namespace app\corp\controller;

use app\corp\model\Corp;
use app\corp\model\Position;
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
            if (empty($cid)) {
                $corp = Session::get('corp');
            }else{
                $corp = Corp::getCorp($cid);
            }
            $number = Session::get('number');
            $number['pname'] = Position::getPositionName($cid, $number['pid']);
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