<?php

namespace app\user\model;

use think\Cookie;
use think\Model;
use think\Session;

class User extends Model
{

    private $uid;

    private $email;

    public function __construct()
    {
        parent::__construct();
        if (Session::has('userInfo')) {
            $userInfo = Session::get('userInfo');
            $this->uid = $userInfo['uid'];
            $this->email = $userInfo['email'];
        }
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getEmail()
    {
        return $this->email;
    }

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
            if (!empty($remember)){
                Cookie::set('remember', $user);
            }
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

    public static function existUserName($username)
    {
        $name = static::get(['username' => $username])->value('username');
        if (empty($name)){
            return false;
        }
        return true;
    }

    public static function addUser($userInfo = array())
    {
        $user = new static();
        $result = $user->save(array(
            'email' => $userInfo['email'],
            'username' => $userInfo['username'],
            'password' => md5($userInfo['password']),
            'status' => 1
        ));
        return $result > 0 ? true  : false;
    }

    public static function updateAvatar($avatar, $uid)
    {
        $user = new static();
        $status = $user->save([
            'avatar' => $avatar
        ], ['uid' => $uid,]);
        if (false !== $status){
            return true;
        }
        return false;
    }
}