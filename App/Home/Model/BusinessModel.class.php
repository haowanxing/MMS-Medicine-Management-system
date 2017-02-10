<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 17/2/9
 * Time: 下午2:20
 */

namespace Home\Model;

use Think\Model;

class BusinessModel extends Model{

    public function get_list(array $condition=array(),$size=null,$page=1){
        if($size) $this->page($page, $size);
        if(!empty($condition)) $this->where($condition);
        $this->order("id DESC");
        $rs = $this->select();
        array_walk($rs,function(&$i){
            $i['lasttime'] = $i['lasttime']==0?"从未登录过本系统":date("Y-m-d H:i:s",$i['lasttime']);
        });
        return $rs;
    }

    public function edit($where,$data){
        if (!empty($where) && $where['id'] > 0) {
            $findRes = $this->where($where)->find();
            if ($findRes) {
                $data['id'] = $where['id'];
                $saveRes = $this->data($data)->save();
                if ($saveRes) {
                    return $saveRes;
                } else {
                    $this->error = "没有任何变化!".$this->getError();
                    return false;
                }
            } else {
                $this->error = "用户不存在!";
                return false;
            }
        } else {
            $this->error = "缺少必要参数或不正确!";
            return false;
        }
    }

    public function doAdd($data){
        $username = $data['username'];
        $password = $data['password'];
        $realname = $data['realname'];
        $admin = $data['admin'];
        $shop_id = $data['shop_id'];
        if($username == "" || $password == "" || $realname ==""){
            $this->error = "表单必须填写完整,不得出现空项!";
            return false;
        }
        $info['username'] = $username;
        $info['password'] = md5($password);
        $info['realname'] = $realname;
        $info['admin'] = $admin;
        foreach ($info as $k => $v) {
            if (empty($v) && $v < 0) {
                $this->error($k . "为必填项");
            }
        }
        $info['shop_id'] = $shop_id;
        if ($this->where(array("username" => $info['username']))->find()) {
            $this->error = "登录名重复!";
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

    public function del($where){
        $info['id'] = $where['user_id'];
        $info['shop_id'] = $where['shop_id'];
        $user = $this->where($info)->find();
        if ($user) {
            if ($user['admin'] == 0) {
                $res = $this->where($user)->delete();
                return $res;
            } else {
                $this->error = "不能删除管理员!";
                return false;
            }
        } else {
            $this->error = "用户不存在!";
            return false;
        }
    }
}