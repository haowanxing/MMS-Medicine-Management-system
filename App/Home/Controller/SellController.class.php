<?php
namespace Home\Controller;

use Think\Controller;

class SellController extends Controller{

    public function _initialize(){
        A("User")->loginCheck();
    }

    public function index(){

        $this->show("<h2>销售类</h2>");
    }

    public function doAdd(){
        $data = I("post.");
        $data["sell_amount"] = I("post.amount");
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
            $rs = D("Sell")->getById($id);
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