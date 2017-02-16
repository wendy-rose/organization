<?php

namespace app\user\controller;

use think\Controller;

class Info extends Controller
{

    public function back()
    {
        return $this->fetch();
    }

    public function logon()
    {
        echo '退出登录';
    }
}