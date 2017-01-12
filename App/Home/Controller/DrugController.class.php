<?php
namespace Home\Controller;

use Think\Controller;

class DrugController extends Controller
{

    public function _initialize()
    {
        A("User")->loginCheck();
    }
    public function index()
    {
        $this->show("<h2>药品类</h2>");
    }
    public function getDrugList(){
        $data = array();
        $page = I("post.page",1);
        $size = I("post.size",15);
        $list = D("Drugs")->get_list($data,$size,$page);
        $count = D("Drugs")->count();
        $ret = result(200,'ok',array('list'=>$list,'count'=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }

    public function doChangeInfo(){
        $drugId = I("post.drug_id");
        $data['name'] = I("post.name");
        $data['pinyinma'] = I("post.pinyinma");
        $data['spec'] = I("post.spec");
        $data['unit'] = I("post.unit");
        $data['lowwarning'] = I("post.lowwarning");
        $rs = D("Drugs")->edit($drugId,$data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Drugs")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }

    public function addDrug(){
        if(I("post.do")=="doAdd"){
            $data['name'] = I("post.name");
            $data['pinyinma'] = I("post.pinyinma");
            $data['spec'] = I("post.spec");
            $data['unit'] = I("post.unit");
            $data['lowwarning'] = I("post.lowwarning");
            $rs = D("Drugs")->doAdd($data);
            if($rs){
                $retMsg = result(200,'ok',$rs);
            }else{
                $retMsg = result(400,D("Drugs")->getError());
            }
        }else{
            $retMsg = result(300,"缺少必要参数");
        }
        $this->ajaxReturn($retMsg,'json');
    }
    public function delDrug()
    {
        if (A("User")->checkAdmin() === true) {
            if (I("post.do") == "delDrug") {
                $drug_id = I("post.drug_id");
                $rs = D("Drugs")->doDel($drug_id);
                if($rs){
                    $retMsg = result(200,'ok',$rs);
                }else{
                    $retMsg = result(400,D("Drugs")->getError());
                }
            } else {
                $retMsg = result(300,"缺少必要参数");
            }
        } else {
            $retMsg = result(302,"您没有此权限！");
        }
        $this->ajaxReturn($retMsg,'json');
    }
    public function getInfo(){
        if(!empty(I("get.pinyinma"))){
            $pinyin = I("get.pinyinma");
            $rs = D("Drugs")->getByPinYin($pinyin);
            if($rs){
                $retMsg = result(200,'ok',$rs);
            }else{
                $retMsg = result(400,'信息库无'.$pinyin,$rs);
            }
        }else{
            $retMsg = result(300,'缺少pinyinma');
        }
        $this->ajaxReturn($retMsg,'json');
    }
    public function nameTips(){
        if(!empty(I("get.input"))){
            $data['pinyinma'] = array("like","%".I("get.input")."%");
            $result = M("Drugs")->where($data)->select();
            $ret = array();
            if($result){
                foreach($result as $k=>$v){
                    $ret[] = array("id"=>$k,"value"=>$v["pinyinma"]);
                }
            }
            $this->ajaxReturn($ret,'json');
        }else{
//            $this->ajaxReturn(array('value'=>"1","data"=>"one"));
        }
    }
}