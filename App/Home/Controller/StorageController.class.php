<?php
namespace Home\Controller;

use Think\Controller;

class StorageController extends Controller{

    public function _initialize(){
        A("User")->loginCheck();
    }

    public function index(){

        $this->show("<h2>入库单类</h2>");
    }

    public function getStorageRecord(){
        $data = array();
        $page = I("post.page",1);
        $size = I("post.size",15);
        $Storage = D("Storage");
        $list = $Storage->get_list($data,$size,$page);
        $count = $Storage->count();
        $retMsg = result(200,'ok',array('list'=>$list,'count'=>intval($count)));
        $this->ajaxReturn($retMsg,'json');
    }

    public function doAdd(){
        $piYin = I("post.pinyinma");
        $data = I('post.');
        $data['in_time'] = strtotime($data['in_time']);
        $data['producedate'] = strtotime($data['producedate']);
        $data['usefuldate'] = strtotime($data['usefuldate']);
        $data['in_by'] = I("session.userId");
        $rs = D("Storage")->storage($piYin,$data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,'no effect',$rs);
        }
        $this->ajaxReturn($retMsg, 'json');
    }
}