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
        $user  = collection(static::all(['email' => $email, 'password' => md5($password)]))->toArray();
        if (empty($user[0])){
            return false;
        }else{
            Session::set('userInfo', $user[0]);
            if (!empty($remember)){
                Cookie::set('remember', $user[0]);
            }
            self::updateMakeCredit(1, $user[0]['userid']);
            CreditLog::addCreditLog(1, $user[0]['userid']);
            return true;
        }
    }

    public static function getAttribute($attr)
    {
        if (Session::has('userInfo')) {
            $user = Session::get('userInfo');
            return isset($user[$attr]) ? $user[$attr] : null;
        }
        return null;
    }

    public static function existEmail($emailAddress)
    {
        $email = static::get(['email' => $emailAddress]);
        if (empty($email)){
            return true;
        }
        return false;
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
        $name = static::get(['username' => $username]);
        if (empty($name)){
            return true;
        }
        return false;
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
        ], ['userid' => $uid,]);
        if (false !== $status){
            return true;
        }
        return false;
    }

    public static function fetchUserByUid($uid)
    {
        $user = (new static())->get(['userid' => $uid])->toArray();
        $creditType = Credit::getCreditByNumber($user['credit']);
        $high = Credit::getHighByNumber($user['credit']);
        $user['creditType'] = $creditType;
        $user['ratio'] = $user['credit']. '/'. $high;
        $user['sorce'] = intval(($user['credit']/$high) * 100). '%';
        return $user;
    }

    public static function updateCredit($credit, $userid)
    {
        $user = new static();
        $userInfo = $user->get(['userid' => $userid]);
        $user->save(
            ['credit' => $userInfo['credit'] + $credit],
            ['userid' => $userid]
        );
    }

    /**
     * @param integer $type 1表示经验，2表示金钱，3表示贡献
     * @param integer $userid 用户id
     */
    public static function updateMakeCredit($type,$userid)
    {
        $user = new static();
        $where = ['userid' => $userid];
        $userInfo = $user->get($where);
        if($type == 1) {
            $update = ['experience' => $userInfo['experience'] + 1];
        }elseif ($type == 2) {
            $update = ['money' => $userInfo['money'] + 2];
        }else {
            $update = ['donation' => $userInfo['donation'] + 3];
        }
        $user->update($update, $where);
        $userInfomation = new static();
        $info = $userInfomation->get($where);
        $credit = $info['experience'] + $info['money'] + $info['donation'];
        $userInfomation->update(['credit' => $credit], $where);
    }

    public static function updateUser($params)
    {
        $user = new static();
        $user->update($params, ['email' => $params['email']]);
        $userInfo = static::get(['email' => $params['email']])->toArray();
        Session::set('userInfo', $userInfo);
        Cookie::set('remember', $userInfo);
        return $userInfo;
    }
}