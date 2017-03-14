<?php
namespace app\corp\controller;

use app\corp\model\Attach;
use app\index\controller\Base;
use app\index\model\FileUpload;
use app\index\util\AttachUtil;
use app\index\util\StringUtil;
use think\File;

class Index extends Base
{

    public function make()
    {
        if (request()->isAjax()) {
            $fields = request()->post();
            var_dump($fields);die;
        }else{
            return $this->fetch();
        }
    }

    public function UploadCorp()
    {
        $corpImg = request()->file('corpImg');
        $validate = ['size'=>2048000,'ext'=>'jpg,png,jpeg'];
        $fileUpload = new FileUpload($corpImg);
        $filePath = $fileUpload->upload($validate);
        if (substr($filePath, 0, 1) != '/') {
            return $this->ajaxReturn(false, $filePath);
        }
        $fileUpload->setFile(ROOT_PATH. 'public'. $filePath);
        $thumbPath = $fileUpload->thumb();
        echo json_encode(array(
            'success' => true,
            'thumb' => $thumbPath
        ));
        exit();
    }

    public function UploadAttach()
    {
        $tempFile = $_FILES['uploadAttach']['tmp_name'];
        $fileName = $_FILES['uploadAttach']['name'];
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $file = new File($tempFile, 'rw');
        $info = $file->move(ROOT_PATH. 'public'. DS. 'attach', md5(time()). '.'. $extension);
        if ($info) {
            $attachPath = '/attach/'. StringUtil::changeBackslash($info->getSaveName());
            $icon = AttachUtil::getTypeIcon(strtolower($extension));
            $attachid = Attach::addAttach($fileName,$attachPath, $icon);
            return json(array(
                'success' => true,
                'data' => array(
                    'attachid' => $attachid,
                    'path' => $attachPath,
                    'icon' => $icon,
                    'name' => $fileName
                )
            ));
        }else{
            return json(array(
                'success' => false,
                'msg' => $file->getError()
            ));
        }
    }

    public function deleteAttach()
    {
        $attachid = request()->post('attachid');
        Attach::deleteAttach($attachid);
        return $this->ajaxReturn(true);
    }
}
