<?php

namespace app\user\controller;

use app\index\controller\Base;
use app\user\model\User;
use think\Lang;
use think\Session;

class Info extends Base
{

    /**
     * 找回密码
     * @return array
     */
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
            //删除邮箱验证码
            Session::delete('code');
            $lang = $result ? Lang::get('Update password success') : Lang::get('Update password fail');
            return $this->ajaxReturn($result, $lang);
        }
    }

    /**
     * 判断用户名是否已经存在
     * @return array
     */
    public function ExsitName()
    {
        $username = input('post.username', 'trim,strip_tags');
        return ['valid' => User::existUserName($username)];
    }

    /**
     * 判断邮箱是否存在
     * @return array
     */
    public function ExsitEmail()
    {
        $email = input('post.email', 'trim,strip_tags');
        return ['valid' => User::existEmail($email)];
    }

}