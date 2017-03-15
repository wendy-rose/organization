<?php

namespace app\corp\model;

use think\Model;

class Corp extends Model
{

    public static function addCorp($fields)
    {
        $corp = new static();
        $corp->data($fields);
        $corp->save();
        return $corp->cid;
    }
}