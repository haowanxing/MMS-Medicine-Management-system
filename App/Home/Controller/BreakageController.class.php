<?php
namespace Home\Controller;

use Think\Controller;

class BreakageController extends Controller
{
    private $shopData = array();
    public function _initialize()
    {
        A("Business")->loginCheck();
        $this->shopData = ['shop_id'=>session("business.shop_id")];
    }
    public function index()
    {

        $this->show("<h2>报损类</h2>");
    }

    public function getBreakageList(){
        $data = array();
        $data = array_merge($data,$this->shopData);
        $page = I("post.page",1);
        $size = I("post.size",15);
        $rs = D("Breakage")->get_list($data,$size,$page);
        $count = D("Breakage")->where($this->shopData)->count();
        $ret = result(200,'ok',array("list"=>$rs,"count"=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }

    public function doAdd(){
        $where['stock_id'] = I("post.stock_id");
        $where = array_merge($where,$this->shopData);
        $amount = I("post.break_amount");
        $price = I("post.allprice");
        $reason = I("post.reason","无");
        $rs = D("Breakage")->doBreak($where,$amount,$price,session('business.bid'),$reason);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Breakage")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }

}