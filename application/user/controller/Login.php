<?php

namespace app\user\controller;

use app\index\controller\Base;
use app\user\model\User;
use think\Lang;

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
        return $this->ajaxReturn($existUser, [], $lang);
    }

    public function _empty()
    {
        if (request()->isAjax()){
            $this->ajaxReturn(false, ['islogin' => false], Lang::get('Please login again'));
        }else{
            $this->error(Lang::get('Please login again'), url('user/login/index'));
        }
    }
}