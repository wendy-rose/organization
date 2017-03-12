<?php

namespace app\index\util;


class AttachUtil
{
    public static $types = [
        '7z', 'ai', 'bat', 'bmp', 'css', 'directory', 'doc',
        'exe', 'fla', 'fw', 'gif', 'html', 'jpg', 'js', 'mp3',
        'pdf', 'png', 'ppt', 'psd', 'rar', 'swf', 'txt', 'unknown',
        'video', 'wma', 'xls', 'zip', 'docx', 'pptx', 'xlsx'

    ];

    public static function getTypeIcon($type)
    {
        if (in_array($type, self::$types)) {
            return '/static/lib/img/file/i_'. $type. '_lt.png';
        }else{
            return '/static/lib/img/file/i_unknown_lt.png';
        }
    }
}