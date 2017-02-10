<?php

$root = request()->root();
define('__ROOT__',str_replace('/index.php','',$root));

//user模块配置
return [
// 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__PUBLIC__' => __ROOT__.'/static/user',
        '__COMMON__' => __ROOT__.'/static/common'
    ],
];