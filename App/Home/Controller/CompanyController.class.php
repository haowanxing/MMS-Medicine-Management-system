<?php
namespace Home\Controller;

use Think\Controller;

class CompanyController extends Controller{

    private $shopData;

    public function _initialize(){
        A("Business")->loginCheck();
        $this->shopData = ['shop_id'=>session("business.shop_id")];
        $this->s_id = I("session.userId");
    }

    public function index()
    {
        $this->show("<h2>公司类</h2>");
    }

    public function nameTips(){
        if(!empty(I("get.input"))){
            $data['c_name'] = array("like","%".I("get.input")."%");
            $result = M("Company")->where($data)->select();
            $ret = array();
            if($result){
                foreach($result as $k=>$v){
                    $ret[] = array("id"=>$k,"value"=>$v["c_name"]);
                }
            }
            $this->ajaxReturn($ret,'json');
        }else{
//            $this->ajaxReturn(array('value'=>"1","data"=>"one"));
        }
    }

    public function getComList(){
        $data = array();
        $page = I("post.page",1);
        $size = I("post.size",15);
        $list = D("Company")->where($this->shopData)->get_list($data,$size,$page);
        $count = D("Company")->where($this->shopData)->count();
        $ret = result(200,'ok',array('list'=>$list,'count'=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }

    public function addCom(){
        if (I("post.do") == "addCom") {
            $info = I("post.");
            $info = array_merge($info,$this->shopData);
            $rs = D("Company")->doAdd($info);
            if($rs){
                $retMsg = result(200,'ok',$rs);
            }else{
                $retMsg = result(400,D("Company")->getError());
            }
        } else {
            $retMsg = result(300,'干啥呢');
        }
        $this->ajaxReturn($retMsg,'json');
    }

    public function delCom(){
        if (I("post.do") == "delCom") {
            $data['id'] = I("post.id");
            $data = array_merge($data,$this->shopData);
            $rs = D("Company")->doDel($data);
            if($rs){
                $retMsg = result(200,'ok',$rs);
            }else{
                $retMsg = result(400,D("Company")->getError());
            }
        } else {
            $retMsg = result(300,'干啥呢');
        }
        $this->ajaxReturn($retMsg,'json');
    }

    public function doChangeInfo(){
        $data = I("post.");
        $data = array_merge($data,$this->shopData);
        $where['id'] = $data['id'];
        $where = array_merge($where,$this->shopData);
        $rs = D("Company")->edit($where,$data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Company")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }


}