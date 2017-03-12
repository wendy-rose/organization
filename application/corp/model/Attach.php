<?php
namespace app\corp\model;

use think\Model;

class Attach extends Model
{

    public static function addAttach($attachName, $attachUrl, $attachIcon = '')
    {
        $attach = [
            'attachname' => $attachName,
            'attachurl' => $attachUrl,
            'attachicon' => $attachIcon
        ];
        $static = new static();
        $static->data($attach);
        $static->save();
        return $static->attachid;
    }
}