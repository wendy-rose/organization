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

    public function upload()
    {
        $info = $this->file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if ($info) {
            return '/uploads/'. $info->getSaveName();
        }
        return null;
    }

    public function thumb()
    {
        $image = Image::open($this->file);
        $imagePath = DS. 'avatar'. DS. md5(time()). $image->type();
        $image->thumb(120, 120)->save(ROOT_PATH. 'public'. $imagePath);
        return $imagePath;
    }
}