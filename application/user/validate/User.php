<?php

namespace app\user\validate;

use app\user\model\User as UserModel;
use think\Validate;

class User extends Validate
{

    protected $rule = [
        'email' => 'require|email|existEmail:',
        'username' => 'require|existName',
        'password' => 'require|length:6,16',
        'repassword' => 'require|length:6,16|confirm:password',
        'captcha' => 'require|checkCaptcha:'
    ];

    protected $message = [
        'email.require' => '邮箱必填',
        'email.email' => '邮箱格式错误',
        'email.existEmail' => '邮箱已经存在，请直接登录',
        'username.require' => '用户名必填',
        'username.existName' => '用户名已经存在，请重新填写',
        'password.require' => '密码必填',
        'password.between' => '密码长度应6-16位',
        'repassword.require' => '确认密码必填',
        'repassword.between' => '确认密码长度应6-16位',
        'repassword.confirm' => '两次密码不一致',
        'captcha.require' => '验证码必填',
        'captcha.checkCaptcha' => '验证码不正确',
    ];

    protected function existEmail($value)
    {
        return UserModel::existEmail($value);
    }

    protected function existName($value)
    {
        return UserModel::existUserName($value);
    }

    protected function checkCaptcha($value)
    {
        $captcha = trim(strtolower($value));
        return captcha_check($captcha);
    }
}