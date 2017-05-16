<?php
namespace app\corp\controller;

use app\activity\model\Activity;
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
use app\corp\util\Corp as CorpUtil;
use think\Db;
use think\File;
use think\Lang;
use think\Session;
use think\Url;
use think\View;

class Index extends Base
{
    public function index()
    {
        if (request()->isAjax()){
            $request = request()->get();
            $lists = Corp::getCorpList($request['type'], $request['num'], $request['belong'], $request['corpname'], $request['page']);
            $return = array();
            if (!empty($lists['list'])){
                foreach ($lists['list'] as $list){
                    $list['createtime'] = date('Y-m-d');
                    $list['belong'] = CorpUtil::getCorpBelong($list['belong']);
                    $return[] = $list;
                }
            }
            return $this->ajaxReturn(true, '', $return, ['allpage' => $lists['count'], 'nowpage' => $request['page']]);
        }else{
            $view = new View([
                // 模板引擎类型 支持 php think 支持扩展
                'type'         => 'Think',
                // 模板路径
                'view_path'    => '',
                // 模板后缀
                'view_suffix'  => 'html',
                // 模板文件名分隔符
                'view_depr'    => DS,
                // 模板引擎普通标签开始标记
                'tpl_begin'    => '<{',
                // 模板引擎普通标签结束标记
                'tpl_end'      => '}>',
                // 标签库标签开始标记
                'taglib_begin' => '<{',
                // 标签库标签结束标记
                'taglib_end'   => '}>',
            ]);
            return $view->fetch();
        }
    }

    public function make()
    {
        if (request()->isAjax()) {
            $fields = request()->post();
            $result = $this->validate($fields, 'Corp');
            if (true !== $result) {
                return $this->ajaxReturn(false, $result);
            }else{
                Db::transaction(function(){
                    $fields = request()->post();
                    $userid = User::getAttribute('userid');
                    $ussr = User::fetchUserByUid($userid);
                    $fields['createuid'] = $userid;
                    $fields['createtime'] = time();
                    $cid = Corp::addCorp($fields);
                    Corp::updateNumByCid($cid);
                    $corp = Corp::getCorp($cid);
                    $deptid = Dept::addDept($cid, $corp['corpname'], $userid);
                    Position::addPositionDefault($cid);
                    $position = Position::getPositionByCid($cid);
                    $pids = StringUtil::getColumn($position, 'pid');
                    CorpNumber::addNumber($cid, $userid, $ussr['username'], $ussr['email'], $ussr['password'], $ussr['mobile'], $deptid, end($pids));
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
        $info = $corpImg->getInfo();
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
            'thumb' => $thumbPath,
            'imgname' => $info['name'],
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
        if (request()->isAjax()){
            $uid = User::getUid();
            $page = request()->get('page');
            $myCorp = CorpNumber::getJoinCorp($uid, $page);
            $corp = array();
            if(!empty($myCorp['list'])) {
                foreach ($myCorp['list'] as $value) {
                    $value['name'] = Dept::getDeptName($value['deptid'], $value['cid']);
                    $value['belong'] = CorpUtil::getCorpBelong($value['belong']);
                    $value['createtime'] = date('Y-m-d', $value['createtime']);
                    $value['url'] = Url::build('corp/index/detail', "cid={$value['cid']}");
                    $corp[] = $value;
                }
            }
            return $this->ajaxReturn(true, '', $corp, array('page' => $page, 'count' => $myCorp['count']));
        }else{
            return $this->fetch();
        }
    }

    public function login()
    {
        if (request()->isAjax()) {
            $cid = request()->post('cid');
            $email = request()->post('email');
            $password = request()->post('password');
            $number = CorpNumber::getNumber($cid, $email, $password);
            if (!empty($number)){
                $lang = '';
                $isExist = true;
                $corp = Corp::getCorp($cid);
                Session::set('number', $number);
                Session::set('corp', $corp);
            }else{
                $lang = Lang::get('Email or pasword is error');
                $isExist = false;
            }
            return $this->ajaxReturn($isExist, $lang, [], ['cid' => $cid]);
        }else{
            $cid = request()->get('cid');
            $corp = Corp::getCorp($cid);
            return $this->fetch('login', $corp);
        }
    }

    public function exitCorp()
    {
        $cid = request()->post('cid');
        $uid = User::getUid();
        CorpNumber::deleteNumber($cid, $uid);
        return $this->ajaxReturn(true, Lang::get('Exit corp succuess'));
    }

    public function getCorp()
    {
        $corp = Session::get('corp');
        $number = Session::get('number');
        return $this->ajaxReturn(true, '', array_merge($corp, $number));
    }

    public function detail()
    {
        $cid = request()->get('cid');
        $corp= Corp::getCorp($cid);
        $corp['count'] = Activity::countActivityByCid($cid);
        $corp['createtime'] = date('Y-m-d', $corp['createtime']);
        $corp['belongText'] = CorpUtil::getCorpBelong($corp['belong']);
        return $this->fetch('detail', $corp);
    }

    public function getOrg()
    {
        $cid = request()->get('cid');
        $depts = Dept::getDeptByCid($cid);
        $deptLists = [];
        foreach ($depts as $dept){
            $deptLists[] = [
                'id' => $dept['deptid'],
                'pid' => $dept['pid'],
                'name' => $dept['name'],
            ];
        }
        $this->returnArray($deptLists);
    }

    private function returnArray($result){

        $newResult = array();
        if( !empty($result) ){

            foreach ($result as $k => $v) {

                $arrTep = $v;
                $arrTep['childrens'] = array();

                //父类ID是0时，代表没有父类ID，为根节点
                if( 0 == $v['pid'] ){
                    $newResult[] = $arrTep;
                    continue;
                }

                if( 0 != $v['pid']){
                    //添加不入数组中的子节点，可能是没有父类节点，那么将其当成根节点
                    if(false === $this->updateArray($newResult, $arrTep) ){
                        $arrTep = array('id'=> $arrTep['id'], 'pid'=>0, 'name'=>$arrTep['name'], 'childrens'=>array($arrTep));
                        $newResult[] = $arrTep;
                    }

                }
            }
        }
        //测试数组是否生成树形数组
        //return $newResult;
        echo json_encode($newResult);
    }

    private function updateArray( &$newResult, $arrTep ){

        if( !empty($newResult) ){
            foreach ($newResult as $k => $v) {
                //查询当前节点的id是否与新的树形数组的id一致，如果是，那么将当前节点存放在树形数组的childrens字段中
                if( $v['id'] == $arrTep['pid']){

                    $newResult[$k]['childrens'][] = $arrTep;
                    return true;

                }elseif( !empty($v['childrens']) ){
                    //递归调用，查询树形数组的子节点与当前节点的关系
                    if(true === $this->updateArray( $newResult[$k]['childrens'], $arrTep )){
                        return true;
                    }

                }

            }
        }
        return false;
    }
}
