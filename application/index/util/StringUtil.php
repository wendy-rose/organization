<?php

namespace app\index\util;

class StringUtil
{

    /**
     * 生成随机长度字符串
     * @param int $len 长度
     * @param string $format 模式
     * @return string
     */
    public static function getRandPw($len = 6 , $format = 'ALL')
    {
        switch ($format){
            case 'ALL':
                $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
                    'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',
                    't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
                    'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
                    'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',
                    '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
                break;
            case 'CHAR':
                $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
                    'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',
                    't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
                    'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
                    'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z');
                break;
            case 'NUMBER':
                $chars = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
                break;
            default:
                $chars = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        }
        $keys = array_rand($chars, $len);
        $code = '';
        for ($i = 0; $i < $len; $i++){
            $code .= $chars[$keys[$i]];
        }
        return $code;
    }

    /**
     * 将上传地址\转换成/
     * @param $str
     * @return mixed
     */
    public static function changeBackslash($str)
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $str);
    }

    /**
     * @param $array
     * @param $columnName
     * @return mixed
     */
    public static function getColumn($array, $columnName)
    {
        return array_map(function ($element) use ($columnName) {
            return $element[$columnName];
        }, $array);
    }
}