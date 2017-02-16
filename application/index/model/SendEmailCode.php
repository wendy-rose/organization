<?php

namespace app\index\model;

use app\index\util\StringUtil;
use phpmailer\phpmailer;
use think\Config;
use think\console\command\make\Model;
use think\Lang;
use think\Session;
use think\Validate;

class SendEmailCode extends Model implements Send
{
    public function sendCode($emailAddress)
    {
        $validate = new Validate([
            'email', 'require|email', Lang::get('Email format error')
        ]);
        if (!$validate->check(array('email' => $emailAddress))){
            return $validate->getError();
        }
        $code = StringUtil::getRandPw(6);
        Session::set('code', $code);
        $emailConfig = Config::get('email');
        $phpmailer = new phpmailer();
        $phpmailer->Host = $emailConfig['host'];
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = $emailConfig['username'];
        $phpmailer->Password = $emailConfig['password'];
        $phpmailer->From = $emailConfig['from'];
        $phpmailer->FromName = $emailConfig['fromname'];
        $phpmailer->CharSet = $emailConfig['charset'];
        $phpmailer->Subject = Lang::get('Eamil subject');
        $phpmailer->Body = Lang::get('Email body', array('code' => $code));
        $phpmailer->AltBody = Lang::get('Email altbody');
        $phpmailer->WordWrap = $emailConfig['wordwrap'];
        $phpmailer->isSMTP();
        $phpmailer->isHTML(true);
        $phpmailer->addAddress($emailAddress);
        return $phpmailer->send();

    }
}