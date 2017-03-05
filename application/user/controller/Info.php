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
        if (empty($avatar_url) && empty($avatar_data)) {
            return $this->ajaxReturn(false, Lang::get('Select picture'));
        }
        $avatar_data = json_decode($avatar_data, true);
        $uid = User::getAttribute('userid');
        $image = Image::open(ROOT_PATH. 'public'. $avatar_url);
        $imageName = explode('\\', $avatar_url);
        if (!is_dir(ROOT_PATH. 'public'. DS. 'avatar')) {
            mkdir(ROOT_PATH. 'public'. DS. 'avatar', 777, true);
        }
        $save_url = ROOT_PATH. 'public'. DS. 'avatar'. DS . $imageName[1];
        $image->crop((int)$avatar_data['width'], (int)$avatar_data['height'], (int)$avatar_data['x'], (int)$avatar_data['y'])->save($save_url);
        User::updateAvatar('/avatar/'. $imageName[1], $uid);
        return $this->ajaxReturn(true, Lang::get('Upload avatar success'), ['imgUrl' => '/avatar/'. $imageName[1]]);
    }

    public function edit()
    {
        $email = request()->post('email');
        $password = request()->post('password');
        $username = request()->post('username');
        $captch = request()->post('captch');
        $params = array();
        $params['email'] = $email;
        if (!empty($password)) {
            if (strlen($password) <6 || strlen($password > 16)) {
                return $this->ajaxReturn(false, Lang::get('Password length from six to sixteen'));
            }else{
                $params['password'] = md5($password);
            }
        }
        if (empty($username)) {
            return $this->ajaxReturn(false, Lang::get('Username is not empty'));
        }else{
            if (User::existUserName($username)) {
                $params['username'] = $username;
            }else{
                return $this->ajaxReturn(false, Lang::get('Username is exist'));
            }
        }
        if (empty($captch)) {
            return $this->ajaxReturn(false, Lang::get('Captch is not empt'));
        }else{
            if (!captcha_check($captch)){
                return $this->ajaxReturn(false, Lang::get('Captch is fail'));
            }
        }
        $userInfo = User::updateUser($params);
        return $this->ajaxReturn(true, Lang::get('Update user info success'), $userInfo);
    }
}