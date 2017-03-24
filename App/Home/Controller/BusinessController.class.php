<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 17/2/9
 * Time: 下午2:18
 */

namespace Home\Controller;


use Think\Controller;

class BusinessController extends Controller{
    private $shopData = array();
    public function _initialize()
    {
        $this->shopData = ['shop_id'=>session("business.shop_id")];
    }
    public function index()
    {

        $this->show("<h2>商户类</h2>");
    }

    public function getUserList(){
        $data = array();
        $page = I("post.page",1);
        $size = I("post.size",15);
        $list = D("Business")->where($this->shopData)->get_list($data,$size,$page);
        $count = D("Business")->where($this->shopData)->count();
        $ret = result(200,'ok',array('list'=>$list,'count'=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }

    /**
     *其他模块调用,检测用户登陆状态,未登录则提示登录
     */
    public function loginCheck()
    {
        if (!$this->checkLogin()) {
            $this->error("请登录后再试!", U("Business/login"), 2);
        }
    }

    /**
     *其他模块调用,检测用户身份状态
     */
    public function adminCheck()
    {
        if (!$this->checkAdmin()) {
            $this->error("权限不够!");
        }
    }

    public function checkLogin()
    {
        if (empty(session("business.username"))) {
            return false;
        } else {
            $freshTime = session("business.freshTime");
            /*if (time() > ($freshTime + 30 * 60)) {    //空闲时间超过30分钟,自动退出登录
                $this->logout();
                return false;
            }*/
            if (time() > ($freshTime + 5 * 60)) {    //登录时间超过5分钟,需要更新一下session
                $info = M("Business")->where("id=" . session("business.bid"))->find();
                session("business.username",$info['username']);
                session("business.admin",$info['admin']);
                session("business.freshTime",time());
            }
            return true;
        }
    }

    public function checkAdmin()
    {
        if (empty(session("business.admin")) || !$this->checkLogin()) {
            return false;
        } else {
            return true;
        }
    }

    public function login()
    {
        $shop_id = I("get.shop")?I("get.shop"):session('tmp.shop_id');
        $this->assign("shops",M("Shop")->field("storename,shop_id")->select());
        $this->assign("actionUrl", U("Business/dologin"));
        $this->assign("shop_id",$shop_id);
        $this->display();
    }

    public function doLogin()
    {
        if ($this->checkLogin() === true) {
            $this->error("请先退出已经登录的用户!");
        }
        $user['username'] = I("post.username");
        $user['password'] = md5(I("post.password"));
        $user['shop_id'] = I("post.shop_id");
        $check_shop = M("Shop")->where(['shop_id'=>$user['shop_id']])->find();
        if(empty($check_shop))
            $this->error("商户参数错误！");
        $result = M("Business")->where($user)->find();
        if ($result) {
            M("Business")->where($result)->data(array("lasttime" => time()))->save();
            session("business.bid",$result['id']);
            session("business.shop_id",$result['shop_id']);
            session("business.username",$result['username']);
            session("business.realname",$result['realname']);
            session("business.admin",$result['admin']);
            session("business.freshTime",time());
            $this->success('欢迎登录！',U("Store/index"));
        } else {
            $this->error("用户名或者密码不正确!");
        }
    }

    public function logout()
    {
        $shop_id = session("business.shop_id");
        session(null);
        session('tmp.shop_id',$shop_id);
        return $this;
    }

    public function doLogout()
    {
        if ($this->checkLogin() === true) {
            $this->logout();
            $this->success("成功退出!",U("login"));
        } else {
            $this->error("当前没有登录的用户可以退出!");
        }
    }

    public function doChangeInfo(){
        $data = I("post.");
        $user_id = $data['user_id'];unset($data['user_id']);
        if($data['password'] == "") unset($data['password']);
        else $data['password'] = md5($data['password']);
        $where = array_merge(array('id'=>$user_id),$this->shopData);
        $rs = D("Business")->edit($where,$data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Business")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }

    public function chPass(){
        $this->loginCheck();
        if(I("post.do") == "chpassword"){
            if(I("post.new") == I("post.old") || I("post.new") == ""){
                $this->error("新旧密码不能相同或为空!");
            }else{
                $user_id = session("business.bid");
                $old_password = md5(I("post.old"));
                $new_password = md5(I("post.new"));
                $findRes = M("Business")->where(array("id"=>$user_id,"password"=>$old_password))->find();
                if($findRes){
                    $saveRes = D("Business")->edit($user_id,array("password"=>$new_password));
                    if($saveRes){
                        $this->logout()->success("修改成功!请使用新密码重新登录");
                    }else{
                        $this->error(D("Business")->getError());
                    }
                }else{
                    $this->error("原密码错误!");
                }
            }
        }else{
            $this->display();
        }
    }

    public function addUser()
    {
        if ($this->checkAdmin() === true) {
            if (I("post.do") == "addUser") {
                $info['username'] = I("post.username");
                $info['password'] = I("post.password");
                $info['realname'] = I("post.realname");
                $info['admin'] = I("post.admin",0);
                $info = array_merge($info,$this->shopData);
                $rs = D("Business")->doAdd($info);
                if($rs){
                    $retMsg = result(200,'ok',$rs);
                }else{
                    $retMsg = result(400,D("Business")->getError());
                }
            } else {
                $retMsg = result(300,'干啥呢');
            }
        } else {
            $retMsg = result(300,'干啥呢');
        }
        $this->ajaxReturn($retMsg,'json');
    }

    public function delUser()
    {
        if ($this->checkAdmin() === true || A("User")->checkAdmin()) {
            if (I("post.do") == "delUser") {
                $where['user_id'] = I("post.user_id");
                $where = array_merge($where,$this->shopData);
                $rs = D("Business")->del($where);
                if($rs){
                    $retMsg = result(200,'ok',$rs);
                }else{
                    $retMsg = result(400,D("Business")->getError());
                }
            } else {
                $retMsg = result(300,"缺少必要参数");
            }
        } else {
            $retMsg = result(302,"权限错误");
        }
        $this->ajaxReturn($retMsg,'json');
    }
}