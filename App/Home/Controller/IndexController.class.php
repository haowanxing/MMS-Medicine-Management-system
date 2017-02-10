<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $User = A("Business");
        if(!$User->checkLogin()){
            $this->redirect("Business/login");
        }else{
            $this->redirect("Store/index");
        }
    }
    /*public function getSellList(){
        $data = array();
        $page = I("post.page",1);
        $size = I("post.size",15);
        $Sell = D("Sell");
        $rs = $Sell->get_list($data,$size,$page);
        $count = $Sell->count();
        $ret = result(200,'ok',array("list"=>$rs,"count"=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }*/
    /*public function getAdjustList(){
        $data = array();
        $page = I("post.page",1);
        $size = I("post.size",15);
        $Adjust = D("Adjust");
        $list = $Adjust->get_list($data,$size,$page);
        $count = $Adjust->count();
        $ret = result(200,'ok',array('list'=>$list,'count'=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }*/

}