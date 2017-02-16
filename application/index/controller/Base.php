<?php
namespace app\index\controller;

use think\Controller;
use think\Lang;

class Base extends Controller
{

    /**
     * Ajax返回json数据
     * @param bool $success
     * @param array $data
     * @param string $msg
     * @param array $extra
     * @return array
     */
    public function ajaxReturn($success = true, $msg = '', $data = [], $extra = [])
    {
        $lang = empty($msg) ? Lang::get('Call success') : $msg;
        $result = ['success' => $success, 'data' => $data, 'msg' => $lang];
        return empty($extra) ? $result : array_merge($result, $extra);
    }
}
