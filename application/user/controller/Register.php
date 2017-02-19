<?php

namespace app\user\controller;

use app\index\controller\Base;
use app\user\model\User;
use think\Lang;

class Register extends Base
{

    /**
     * 注册
     */
    public function index()
    {
        $data = [
            'email' => request()->post('email'),
            'username' => request()->post('username'),
            'password' => request()->post('password'),
            'repassword' => request()->post('repassword'),
            'captcha' => request()->post('captcha')
        ];
        $result = $this->validate($data, 'User');
        if (true !== $result){
            return $this->ajaxReturn(false, $result);
        }
        $add = User::addUser($data);
        $lang = $add ? Lang::get('Register success') : Lang::get('Register fail');
        return $this->ajaxReturn($add, $lang);
    }
}