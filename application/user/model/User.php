<?php

namespace app\user\model;

use think\Cookie;
use think\Model;
use think\Session;

class User extends Model
{

    /**
     * 判断用户是否存在
     * @param string $email 邮箱
     * @param string $password 密码
     * @param string $remember 是否记住
     * @return bool
     */
    public static function existUser($email, $password, $remember = '')
    {
        $user = static::get(['email' => $email, 'password' => md5($password)]);
        if (empty($user)){
            return false;
        }else{
            Session::set('userInfo', $user);
            Cookie::set('remember', $user);
            return true;
        }
    }

    public static function existEmail($emailAddress)
    {
        $email = static::get(['email' => $emailAddress])->value('email');
        if (empty($email)){
            return false;
        }
        return true;
    }

    public static function updatePassword($email, $password)
    {
        $user = new static();
        $status = $user->save([
            'password' => md5($password)
        ], ['email' => $email,]);
        if (false !== $status){
            return true;
        }
        return false;
    }
}