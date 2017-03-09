<?php

namespace app\index\controller;


class Index extends Base
{

    public function upload()
    {
        $file = request()->file('file');
    }
}