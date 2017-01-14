<?php
namespace Home\Controller;

use Think\Controller;

class BreakageController extends Controller
{
    public function index()
    {

        $this->show("<h2>报损类</h2>");
    }

    public function getBreakageList(){
        $data = array();
        $page = I("post.page",1);
        $size = I("post.size",15);
        $rs = D("Breakage")->get_list($data,$size,$page);
        $count = D("Breakage")->count();
        $ret = result(200,'ok',array("list"=>$rs,"count"=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }

    public function doAdd(){
        $stock_id = I("post.stock_id");
        $amount = I("post.break_amount");
        $price = I("post.allprice");
        $reason = I("post.reason","无");
        $rs = D("Breakage")->doBreak($stock_id,$amount,$price,I("session.userId"),$reason);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Breakage")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }

}