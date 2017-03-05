<?php

namespace app\user\model;

use think\Model;

class CreditLog extends Model
{

    public static function addCreditLog($type, $userid)
    {
        $creditLog = new static();
        $creditLog->save([
            'userid' => $userid,
            'type' => $type,
            'time' => time()
        ]);
    }
}