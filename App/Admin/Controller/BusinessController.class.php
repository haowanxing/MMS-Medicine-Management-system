<?php
namespace Admin\Controller;

use Think\Controller;

class BusinessController extends Controller
{

    public function _initialize(){}

    public function index()
    {

        $this->show("<h2>叽里呱啦的商户类</h2>");
    }

    public function getShopList(){
        $data = array();
        $page = I("post.page",1);
        $size = I("post.size",15);
        I("post.storename") != "" && $data['storename'] = array('like','%'.I("post.storename").'%');
        $list = D("Shop")->get_list($data,$size,$page);
        $count = D("Shop")->where($data)->count();
        $ret = result(200,'ok',array('list'=>$list,'count'=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }

    public function getUserList(){
        $data = array();
        $page = I("post.page",1);
        $size = I("post.size",15);
        I("post.username") != "" && $data['username'] = array('like','%'.I("post.username").'%');
        $list = D("Business")->get_list($data,$size,$page);
        $count = D("Business")->where($data)->count();
        $ret = result(200,'ok',array('list'=>$list,'count'=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }

    public function updateShop(){
        $data = I("post.");
        $shop_id = $data['shop_id'];unset($data['shop_id']);
        $rs = D("Shop")->edit($shop_id,$data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Shop")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }

    public function addShop(){
        $data = I("post.");
        $rs = D("Shop")->doAdd($data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Shop")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }


    public function delShop()
    {
        $shop_id = I("post.shop_id");
        $rs = D("Shop")->del($shop_id);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Shop")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }


    public function updateUser(){
        $data = I("post.");
        $id = $data['id'];unset($data['id']);
        $rs = D("Business")->edit($id,$data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Business")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }

    public function addUser(){
        $data = I("post.");
        $rs = D("Business")->doAdd($data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Business")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }


    public function delUser()
    {
        $id = I("post.id");
        $rs = D("Business")->del($id);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Business")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }
}