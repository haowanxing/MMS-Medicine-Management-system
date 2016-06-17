<?php
namespace Home\Controller;

use Think\Controller;

class DrugController extends Controller
{
    private $data = array();
    private $db;

    public function _initialize()
    {
        $this->db = M("drugs");
    }
    public function index()
    {
        $this->show("<h2>药品类</h2>");
    }

    public function drugList(){
        $list = $this->db->select();
        return $list;
    }
    public function getDrugList(){
        isset($_POST['page'])?$page=I("post.page"):$page=1;
        isset($_POST['limit'])?$limit=I("post.limit"):$limit=10;
        $data = $this->db->page($page,$limit)->select();
        $count = $this->db->count()/$limit;
        $string = "";
        foreach($data as $key=>$value){
            $string .= "<tr>";
            $string .= "<td>".$value['drug_id']."</td>";
            $string .= "<td>".$value['name']."</td>";
            $string .= "<td>".$value['pinyinma']."</td>";
            $string .= "<td>".$value['spec']."</td>";
            $string .= "<td>".$value['unit']."</td>";
            $string .= "<td>".$value['lowwarning']."</td>";
            $string .= "</tr>";
        }
        $retMsg = array('code'=>200,"pageCount"=>ceil($count),"table"=>$string);
        return $retMsg;
    }

    public function saveInfo(){

    }

    public function add(){
        if(I("post.do")=="doAdd"){
            $this->data['name'] = I("post.name");
            $this->data['pinyinma'] = I("post.pinyinma");
            $this->data['spec'] = I("post.spec");
            $this->data['unit'] = I("post.unit");
            $this->data['lowwarning'] = I("post.lowwarning");
            $res = $this->db->data($this->data)->add();
            if($res){
                $this->ajaxReturn(array('code'=>200,'msg'=>"ok",'result'=>$res));
            }else{
                $this->ajaxReturn(array('code'=>400,'msg'=>"add error",'result'=>$this->db->error()));
            }
        }else{
            $this->display();
        }
    }
    public function delete(){
        if(I("post.do")=="doDel"){
            $this->data['id'] = I("post.id");
            $res = $this->db->data($this->data)->add();
            if($res){
                $this->ajaxReturn(array('code'=>200,'msg'=>"ok",'result'=>$res));
            }else{
                $this->ajaxReturn(array('code'=>400,'msg'=>"add error",'result'=>$this->db->error()));
            }
        }else{
            $this->display();
        }
    }
    public function getInfo(){
        if(!empty(I("get.pinyinma"))){
            $this->data['pinyinma'] = I("get.pinyinma");
            $this->data = $this->db->where($this->data)->find();
            if($this->data){
                $retMsg = array("code"=>200,"msg"=>"ok","result"=>$this->data);
            }else{
                $retMsg = array("code"=>400,"msg"=>"没有该药品:".I("get.pinyinma"),"result"=>$this->data);
            }
        }else{
            $retMsg = array("code"=>400,"msg"=>"缺少参数:pinyinma","result"=>0);
        }
        $this->ajaxReturn($retMsg,'json');
    }
    public function nameTips(){
        if(!empty(I("get.input"))){
            $data['pinyinma'] = array("like","%".I("get.input")."%");
            $result = $this->db->where($data)->select();
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
    public function getInfoByPinYinMa($pin){
        return $this->db->where(array("pinyinma"=>$pin))->find();
    }

    /**
     * @param mixed $id
     * @return DrugController
     */
    public function setId($id)
    {
        $this->data['drug_id'] = $id;
        return $this;
    }

    /**
     * @param mixed $name
     * @return DrugController
     */
    public function setName($name)
    {
        $this->data['name'] = $name;
        return $this;
    }

    /**
     * @param mixed $pinyinma
     * @return DrugController
     */
    public function setPinyinma($pinyinma)
    {
        $this->data['pinyinma'] = $pinyinma;
        return $this;
    }

    /**
     * @param mixed $spec
     * @return DrugController
     */
    public function setSpec($spec)
    {
        $this->data['spec'] = $spec;
        return $this;
    }

    /**
     * @param mixed $unit
     * @return DrugController
     */
    public function setUnit($unit)
    {
        $this->data['unit'] = $unit;
        return $this;
    }

    /**
     * @param mixed $lowwarning
     * @return DrugController
     */
    public function setLowwarning($lowwarning)
    {
        $this->data['lowwarning'] = $lowwarning;
        return $this;
    }

}