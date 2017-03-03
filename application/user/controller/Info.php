<?php

namespace app\user\controller;

use app\index\controller\Base;
use app\user\model\User;
use think\Image;
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
    
    public function Upload()
    {
        $file = request()->file('avatar');
        $info = $file->validate(['size' => 2097152, 'ext' => 'jpg,png,gif'])->move(ROOT_PATH . 'public'. DS . 'uploads');
        if ($info) {
            return $this->ajaxReturn(true, '', ['url' => $info->getSaveName()]);
        }else{
           return $this->ajaxReturn(false, $file->getError());
        }
    }

    public function Avatar()
    {
        $avatar_url = request()->post('avatar_url');
        $avatar_data = request()->post('avatar_data');
        $avatar_data = json_decode($avatar_data, true);
        $image = Image::open($avatar_url);
    }
}