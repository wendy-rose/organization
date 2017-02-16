<?php

namespace app\index\model;


interface Send
{
    public function sendCode($address);
}