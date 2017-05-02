<?php
namespace app\corp\controller;

use app\corp\model\Attach;
use app\corp\model\Corp;
use app\corp\model\CorpNumber;
use app\corp\model\Dept;
use app\corp\model\Position;
use app\index\controller\Base;
use app\index\model\FileUpload;
use app\index\util\AttachUtil;
use app\index\util\StringUtil;
use app\user\model\User;
use think\Db;
use think\File;
use think\Lang;

class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function make()
    {
        if (request()->isAjax()) {
            $fields = request()->post();
            $userid = User::getAttribute('userid');
            $result = $this->validate($fields, 'Corp');
            if (true !== $result) {
                return $this->ajaxReturn(false, $result);
            }else{
                Db::transaction(function(){
                    $userid = User::getAttribute('userid');
                    $ussr = User::fetchUserByUid($userid);
                    $fields['createuid'] = $userid;
                    $fields['createtime'] = time();
                    $cid = Corp::addCorp($fields);
                    $corp = Corp::getCorp($cid);
                    $deptid = Dept::addDept($cid, $corp['name'], $userid);
                    $pid = Position::addPositionDefault($cid);
                    CorpNumber::addNumber($userid, $ussr['username'], $ussr['email'], $ussr['password'], $ussr['mobile'], $deptid, $pid);
                });
                return $this->ajaxReturn(true, Lang::get('Corp make success'));
            }
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

    public function getUserList()
    {
        $deptid = $this->request->get('deptid');
        if (!empty($deptid)) {
            $users = [];
            for ($i=0;$i<5;$i++) {
                $users[] = [
                    'id' => $i+1,
                    'username' => '其他',
                    'dept' => '数学辅导队',
                    'position' => '普通社员',
                    'phone' => '12345678901',
                    'email' => '1104777947@qq.com'
                ];
            }
            $data['draw'] = !empty($_REQUEST['draw']) ?  $_REQUEST['draw'] : 1;
            $data['recordsTotal'] = 20;
            $data['recordsFiltered'] = 20;
            $data['data'] = $users;
            echo json_encode($data, JSON_UNESCAPED_UNICODE);exit();
        }else{
            $users = [];
            for ($i=0;$i<10;$i++) {
                $users[] = [
                    'id' => $i+1,
                    'username' => '小黄人',
                    'dept' => '数学辅导队',
                    'position' => '普通社员',
                    'phone' => '12345678901',
                    'email' => '1104777947@qq.com'
                ];
            }
            $data['draw'] = !empty($_REQUEST['draw']) ?  $_REQUEST['draw'] : 1;
            $data['recordsTotal'] = 20;
            $data['recordsFiltered'] = 20;
            $data['data'] = $users;
            echo json_encode($data, JSON_UNESCAPED_UNICODE);exit();
        }

    }

    public function my()
    {
        return $this->fetch();
    }
}
