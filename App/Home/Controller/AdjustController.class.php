<?php
namespace Home\Controller;

use Think\Controller;

class AdjustController extends Controller
{
    private $shopData = array();
    public function _initialize()
    {
        A("Business")->loginCheck();
        $this->shopData = ['shop_id'=>session("business.shop_id")];
    }
    public function index()
    {
        $this->show("<h2>调价类</h2>");
    }

    public function getAdjustList(){
        $data = array();
        $data = array_merge($data,$this->shopData);
        $page = I("post.page",1);
        $size = I("post.size",15);
        $Adjust = D("Adjust");
        $list = $Adjust->get_list($data,$size,$page);
        $count = $Adjust->where($this->shopData)->count();
        $ret = result(200,'ok',array('list'=>$list,'count'=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }
}