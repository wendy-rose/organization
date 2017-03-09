<?php
namespace app\corp\controller;

use app\index\controller\Base;
use app\index\model\FileUpload;

class Index extends Base
{

    public function make()
    {
        if (request()->isAjax()) {

        }else{
            return $this->fetch();
        }
    }

    public function UploadCorp()
    {
        $file = request()->file('picture');
        $fileUpload = new FileUpload($file);
        $thumbPath = $fileUpload->thumb();
        return $this->ajaxReturn(true, '', ['data' => $thumbPath]);
    }
}
