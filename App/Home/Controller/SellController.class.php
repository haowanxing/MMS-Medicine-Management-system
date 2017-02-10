<?php
namespace Home\Controller;

use Think\Controller;

class SellController extends Controller{
    private $shopData = array();
    public function _initialize()
    {
        A("Business")->loginCheck();
        $this->shopData = ['shop_id'=>session("business.shop_id")];
    }

    public function index(){

        $this->show("<h2>销售类</h2>");
    }
    public function getSellList(){
        $data = array();
        $data = array_merge($data,$this->shopData);
        $page = I("post.page",1);
        $size = I("post.size",15);
        $Sell = D("Sell");
        $rs = $Sell->get_list($data,$size,$page);
        $count = $Sell->where($this->shopData)->count();
        $ret = result(200,'ok',array("list"=>$rs,"count"=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }
    public function doAdd(){
        $data = I("post.");
        $data["sell_amount"] = I("post.amount");
        $data = array_merge($data,$this->shopData);
        $sellRes = D('Sell')->sell($data);
        if ($sellRes){
            $retMsg = result(200,'ok',U('Print/sales_slip',array('order'=>$sellRes)));
        }else{
            $retMsg = result(400,D('Sell')->getError());
        }
        $this->ajaxReturn($retMsg, 'json');
    }

    public function getInfo(){
        if (!empty(I("post.sell_id"))){
            $id = I("post.sell_id");
//            $rs = D("Sell")->getById($id);
            $rs = D("Sell")->getInfo(array_merge(['sell_id'=>$id],$this->shopData));
            if ($rs){
                $retMsg = result(200,'ok',$rs);
            }else{
                $retMsg = result(400,"编号".I("post.sell_id")."不存在",$rs);
            }
        }else{
            $retMsg = result(300,'缺少必要参数');
        }
        $this->ajaxReturn($retMsg, 'json');
    }

}