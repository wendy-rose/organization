<?php

namespace app\user\controller;

use app\index\controller\Base;
use app\user\model\User;
use think\Lang;
use think\Session;

class Info extends Base
{

    public function back()
    {
        $email = request()->post('email');
        $code = request()->post('code');
        $pwd = request()->post('pwd');
        $repwd = request()->post('repwd');
        if (!User::existEmail($email)){
            return $this->ajaxReturn(false, Lang::get('Email is not exsit'));
        }elseif ($pwd != $repwd){
            return $this->ajaxReturn(false, Lang::get('Two password is not equal'));
        }elseif (Session::get('code') && Session::get('code') != $code){
            return $this->ajaxReturn(false, Lang::get('Code is error'));
        }else{
            $result = User::updatePassword($email, $repwd);
            $lang = $result ? Lang::get('Update password success') : Lang::get('Update password fail');
            return $this->ajaxReturn($result, $lang);
        }
    }

    public function logon()
    {
        echo '退出登录';
    }
}