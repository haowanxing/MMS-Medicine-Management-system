<?php
namespace Home\Controller;

use Think\Controller;

class StorageController extends Controller{

    private $shopData = array();
    public function _initialize()
    {
        A("Business")->loginCheck();
        $this->shopData = ['shop_id'=>session("business.shop_id")];
    }

    public function index(){

        $this->show("<h2>入库单类</h2>");
    }

    public function getStorageRecord(){
        $data = array();
        $data = array_merge($data,$this->shopData);
        $page = I("post.page",1);
        $size = I("post.size",15);
        $Storage = D("Storage");
        $list = $Storage->get_list($data,$size,$page);
        $count = $Storage->where($this->shopData)->count();
        $retMsg = result(200,'ok',array('list'=>$list,'count'=>intval($count)));
        $this->ajaxReturn($retMsg,'json');
    }

    public function doAdd(){
        $piYin = I("post.pinyinma");
        $data = I('post.');
        $data['in_time'] = strtotime($data['in_time']);
        $data['producedate'] = strtotime($data['producedate']);
        $data['usefuldate'] = strtotime($data['usefuldate']);
        $data['in_by'] = session('business.bid');
        $data = array_merge($data,$this->shopData);
        $rs = D("Storage")->storage($piYin,$data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,'no effect',$rs);
        }
        $this->ajaxReturn($retMsg, 'json');
    }
}