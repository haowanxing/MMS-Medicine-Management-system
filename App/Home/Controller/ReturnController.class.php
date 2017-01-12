<?php
namespace Home\Controller;

use Think\Controller;

class ReturnController extends Controller
{
    public function _initialize()
    {
        A("User")->loginCheck();
    }
    public function index()
    {
        $this->show("<h2>退货类</h2>");
    }
    public function getRetList(){
        $data = array();
        $page = I("post.page",1);
        $size = I("post.size",15);
        $Return = D("Return");
        $rs = $Return->get_list($data,$size,$page);
        $count = $Return->count();
        $ret = result(200,'ok',array("list"=>$rs,"count"=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }
    public function doAdd(){
        $data = I("post.");
        $data['return_by'] = I("session.userId");
        $rs = D("Return")->doReturn($data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Return")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }

}