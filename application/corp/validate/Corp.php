<?php

namespace app\corp\validate;

class Corp extends \think\Validate
{
    protected $rule = [
        'corpname' => 'require',
        'univeristy' => 'require',
        'class' => 'require',
        'number' => 'require',
        'description' => 'require',
        'corppic' => 'require',
        'attach' => 'require'
    ];

    protected  $message = [
        'corpname.require' => '社团名称必填',
        'univeristy.require' => '学校名称必填',
        'class.require' => '专业班级必填',
        'number.require' => '学号必填',
        'description.require' => '社团描述必填',
        'corppic.require' => '社团图标需上传',
        'attach.require' => '附件需上传'
    ];

}