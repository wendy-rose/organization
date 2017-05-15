<?php

namespace app\index\model;

use think\Image;
use think\Model;

class FileUpload extends Model implements Upload
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function upload($validate = [])
    {
        if (empty($validate)) {
            $info = $this->file->move(ROOT_PATH . 'public' . DS . 'uploads');
        }else {
            $info  = $this->file->validate($validate)->move(ROOT_PATH . 'public' . DS . 'uploads');
        }
        if ($info) {
            return $this->changeBackslash('/uploads/'. $info->getSaveName());
        }
        return $info->get;
    }

    public function thumb()
    {
        $image = Image::open($this->file);
        if (!is_dir(ROOT_PATH. 'public'. DS. 'avatar')){
            mkdir(ROOT_PATH. 'public'. DS. 'avatar', 777);
        }
        $imagePath = DS. 'avatar'. DS. md5(time()). '.'. $image->type();
        $image->thumb(120, 120)->save(ROOT_PATH. 'public'. $imagePath);
        return $this->changeBackslash($imagePath);
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * 将上传地址\转换成/
     * @param $str
     * @return mixed
     */
    private function changeBackslash($str)
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $str);
    }
}