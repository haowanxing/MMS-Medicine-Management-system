<?php
namespace Home\Model;


use Think\Model;

class CompanyModel extends Model{

    public function get_list(array $condition=array(),$size=null,$page=1){
        if($size) $this->page($page, $size);
        if(!empty($condition)) $this->where($condition);
        $this->order("id desc");
        $rs = $this->select();
        return $rs;
    }

    public function doAdd($data){
        $check_data = array("c_name"=>$data['c_name'],"shop_id"=>$data['shop_id']);
        $check = $this->where($check_data)->find();
        if(!empty($check)){
            $this->error = "名称已经存在！";
            return false;
        }
        $rs = $this->data($data)->add();
        if($rs){
            return $rs;
        }else{
            return false;
        }
    }

    public function edit($check_data,$data){
        $check = $this->where($check_data)->find();
        if(empty($check)){
            $this->error = "公司不存在！";
            return false;
        }
        $rs = $this->where($check)->save($data);
        if($rs){
            return $rs;
        }else{
            $this->error = "没有变化和影响!";
            return false;
        }
    }

    public function doDel($data){
        $user = $this->where($data)->find();
        if ($user) {
            $res = $this->where($user)->delete();
            return $res;
        } else {
            $this->error = "小伙伴不存在!";
            return false;
        }
    }
}