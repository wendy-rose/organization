<?php

namespace app\user\model;

use think\Model;
use think\Session;

class User extends Model
{

    /**
     * 判断用户是否存在
     * @param string $email 邮箱
     * @param string $password 密码
     * @return bool
     */
    public static function existUser($email, $password)
    {
        $user = static::get(['email' => $email, 'password' => md5($password)]);
        if (empty($user)){
            return false;
        }else{
            Session::set('userInfo', $user);
            return true;
        }
    }
}