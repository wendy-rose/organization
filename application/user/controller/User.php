<?php

namespace app\user\controller;

use think\Controller;

class User extends Controller
{

    public function login()
    {
        return $this->fetch();
    }

    public function logon()
    {
        echo '退出登录';
    }
}