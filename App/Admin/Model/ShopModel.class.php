<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 17/1/12
 * Time: 下午4:15
 */

namespace Admin\Model;


use Think\Model;

class ShopModel extends Model{

    public function get_list(array $condition=array(),$size=null,$page=1){
        if($size) $this->page($page, $size);
        if(!empty($condition)) $this->where($condition);
        $this->order("shop_id DESC");
        $rs = $this->select();
        return $rs;
    }

    public function edit($shop_id,$data){
        if (!empty($shop_id) && $shop_id > 0) {
            $findRes = $this->where(array('id'=>$shop_id))->find();
            if ($findRes) {
                $data['shop_id'] = $shop_id;
                $saveRes = $this->data($data)->save();
                if ($saveRes) {
                    return $saveRes;
                } else {
                    $this->error = "没有任何变化!".$this->getError();
                    return false;
                }
            } else {
                $this->error = "店铺不存在!";
                return false;
            }
        } else {
            $this->error = "缺少必要参数或不正确!";
            return false;
        }
    }

    public function doAdd($data){
        $storename = $data['storename'];
        $telephone = $data['telephone'];
        $mobile = $data['mobile'];
        $address = $data['address'];
        if($storename == "" || $telephone == "" || $mobile =="" || $address ==""){
            $this->error = "表单必须填写完整,不得出现空项!";
            return false;
        }
        $info['storename'] = $storename;
        $info['telephone'] = $telephone;
        $info['mobile'] = $mobile;
        $info['address'] = $address;
        foreach ($info as $k => $v) {
            if (empty($v) && $v < 0) {
                $this->error($k . "为必填项");
            }
        }
        if ($this->where(array("storename" => $info['storename']))->find()) {
            $this->error = "店铺名重复!";
            return false;
        }
        $res = $this->data($info)->add();
        if ($res) {
            return $res;
        } else {
            $this->error = "写入出错!".$this->getError();
            return false;
        }
    }

    public function del($shop_id){
        $info['shop_id'] = $shop_id;
        $user = $this->where($info)->find();
        if ($user) {
            $res = $this->where($user)->delete();
            return $res;
        } else {
            $this->error = "店铺不存在!";
            return false;
        }
    }
}