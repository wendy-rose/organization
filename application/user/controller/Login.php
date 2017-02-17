<?php

namespace app\user\controller;

use app\index\controller\Base;
use app\user\model\User;
use think\Lang;
use think\Session;

class Login extends Base
{

    public function index()
    {
        return $this->fetch();
    }

    public function checkout()
    {
        $email = request()->post('email');
        $password = request()->post('password');
        $remember = request()->post('remember');
        $existUser = User::existUser($email, $password, $remember);
        $lang = $existUser ? Lang::get('Login success') : Lang::get('Email or password is not fail');
        return $this->ajaxReturn($existUser, $lang);
    }

    public function logon()
    {
        Session::delete('userinfo');
        return $this->ajaxReturn(true, Lang::get('Logon success'));
    }

    public function callback()
    {

    }
}