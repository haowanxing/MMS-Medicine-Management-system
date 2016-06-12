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

    }

    /**
     * @param mixed $id
     * @return DrugController
     */
    public function setId($id)
    {
        $this->data['id'] = $id;
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