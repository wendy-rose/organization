<?php

namespace app\index\controller;

use app\index\model\SendEmailCode;
use app\user\model\User;
use think\Lang;
use think\Session;

class Code extends Base
{

    public function Send()
    {
        $address = input('post.email','','trim,strip_tags');
        if(!User::existEmail($address)){
            return $this->ajaxReturn(false, Lang::get('Email is not exsit'));
        }
        $sendEmailCode = new SendEmailCode();
        $status = $sendEmailCode->sendCode($address);
        $lang = $status ? Lang::get('Send code success') : Lang::get('Send code fail');
        return $this->ajaxReturn($status, $lang);
    }

    public function check()
    {
        $code = input('post.code', 'trim,strtolower');
        if (Session::has('code') && Session::get('code') == $code){
            return ['valid' => true];
        }else{
            return ['valid' => false];
        }
    }
}