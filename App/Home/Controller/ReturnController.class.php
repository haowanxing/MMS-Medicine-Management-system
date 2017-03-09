<?php
namespace Home\Controller;

use Think\Controller;

class ReturnController extends Controller
{
    private $shopData = array();
    public function _initialize()
    {
        A("Business")->loginCheck();
        $this->shopData = ['shop_id'=>session("business.shop_id")];
    }
    public function index()
    {
        $this->show("<h2>退货类</h2>");
    }
    public function getRetList(){
        $data = array();
        $data = array_merge($data,$this->shopData);
        $page = I("post.page",1);
        $size = I("post.size",15);
        $Return = D("Return");
        $rs = $Return->get_list($data,$size,$page);
        $count = $Return->where($this->shopData)->count();

        $ret = result(200,'ok',array("list"=>$rs,"count"=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }
    public function doAdd(){
        $data = I("post.");
        $data['return_by'] = session('business.bid');
        $data = array_merge($data,$this->shopData);
        $rs = D("Return")->doReturn($data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Return")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }

}