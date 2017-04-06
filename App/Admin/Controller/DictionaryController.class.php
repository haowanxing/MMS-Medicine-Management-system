<?php
namespace Admin\Controller;
use Think\Controller;
class DictionaryController extends Controller {

    public function getDrugList(){
        $data = array();
        $page = I("post.page",1);
        $size = I("post.size",15);
        I("post.input_code") != "" && $data['input_code'] = array('like','%'.I("post.input_code").'%');
        $list = D("Dictionary")->get_list($data,$size,$page);
        $count = D("Dictionary")->where($data)->count();
        $ret = result(200,'ok',array('list'=>$list,'count'=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }

    public function updateDrug(){
        $data = I("post.");
        $id = $data['id'];unset($data['id']);
        $rs = D("Dictionary")->edit($id,$data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Dictionary")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }

    public function addDrug(){
        $data = I("post.");
        $rs = D("Dictionary")->doAdd($data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Dictionary")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }


    public function delDrug()
    {
        $id = I("post.id");
        $rs = D("Dictionary")->del($id);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Dictionary")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }
}