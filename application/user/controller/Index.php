<?php
namespace app\user\controller;

use app\index\controller\Base;
use app\user\model\Credit;
use app\user\model\User;
use think\Lang;
use think\Session;

class Index extends Base
{
    public function _initialize()
    {
        if (!Session::has('userInfo')){
            $this->redirect(url('user/login/index'));
        }
    }

    public function index()
    {
        return $this->fetch();
    }

    public function login()
    {
        return $this->fetch();
    }

    public function start()
    {
    	$userid = User::getAttribute('userid');
    	$user = User::fetchUserByUid($userid);
    	if (empty($userid)){
    	    return $this->ajaxReturn(false, Lang::get('Please login again'));
        }
    	$data = array(
    	    'user' => $user,
            'other' => array()
        );
    	return $this->ajaxReturn(true, '', ['data' => $data]);
    }

}
