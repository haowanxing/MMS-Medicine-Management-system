<?php
namespace Home\Controller;

use Think\Controller;

class UserController extends Controller
{

    public function _initialize(){}

    public function index()
    {

        $this->show("<h2>用户类</h2>");
    }

    public function getUserList(){
        $data = array();
        $page = I("post.page",1);
        $size = I("post.size",15);
        $list = D("User")->get_list($data,$size,$page);
        $count = D("User")->count();
        $ret = result(200,'ok',array('list'=>$list,'count'=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }

    /**
     *其他模块调用,检测用户登陆状态,未登录则提示登录
     */
    public function loginCheck()
    {
        if (!$this->checkLogin()) {
            $this->error("请登录后再试!", U("Home/User/login"), 2);
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
        if (empty(I("session.username"))) {
            return false;
        } else {
            $freshTime = I("session.freshTime");
            if (time() > ($freshTime + 30 * 60)) {    //空闲时间超过30分钟,自动退出登录
                $this->logout();
                return false;
            }
            if (time() > ($freshTime + 5 * 60)) {    //登录时间超过5分钟,需要更新一下session
                $info = M("Users")->where("id=" . I("session.userId"))->find();
                $_SESSION['username'] = $info['username'];
                $_SESSION['admin'] = $info['admin'];
                $_SESSION['freshTime'] = time();
            }
            return true;
        }
    }

    public function checkAdmin()
    {
        if (empty(I("session.admin")) || !$this->checkLogin()) {
            return false;
        } else {
            return true;
        }
    }

    public function login()
    {
        $this->assign("actionUrl", U("user/dologin"));
        $this->display();
    }

    public function doLogin()
    {
        if ($this->checkLogin() === true) {
            $this->error("请先退出已经登录的用户!");
        }
        $user['username'] = I("post.username");
        $user['password'] = md5(I("post.password"));
        $result = M("Users")->where($user)->find();
        if ($result) {
            M("Users")->where($result)->data(array("lasttime" => time()))->save();
            $_SESSION['userId'] = $result['id'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['realname'] = $result['realname'];
            $_SESSION['admin'] = $result['admin'];
            $_SESSION['freshTime'] = time();
            $this->redirect("/Home/Store/Index");
        } else {
            $this->error("用户名或者密码不正确");
        }
    }

    public function logout()
    {
        unset($_SESSION['username']);
        unset($_SESSION['realname']);
        unset($_SESSION['admin']);
        unset($_SESSION['userId']);
        unset($_SESSION['freshTime']);
        return $this;
    }

    public function doLogout()
    {
        if ($this->checkLogin() === true) {
            $this->logout()->success("成功退出!");
        } else {
            $this->error("当前没有登录的用户可以退出!");
        }
    }

    public function doChangeInfo(){
        $data = I("post.");
        $user_id = $data['user_id'];unset($data['user_id']);
        if($data['password'] == "") unset($data['password']);
        else $data['password'] = md5($data['password']);
        $rs = D("User")->edit($user_id,$data);
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("User")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }

    public function chPass(){
        $this->loginCheck();
        if(I("post.do") == "chpassword"){
            if(I("post.new") == I("post.old") || I("post.new") == ""){
                $this->error("新旧密码不能相同或为空!");
            }else{
                $user_id = I("session.userId");
                $old_password = md5(I("post.old"));
                $new_password = md5(I("post.new"));
                $findRes = M("Users")->where(array("id"=>$user_id,"password"=>$old_password))->find();
                if($findRes){
                    $saveRes = D("User")->edit($user_id,array("password"=>$new_password));
                    if($saveRes){
                        $this->logout()->success("修改成功!请使用新密码重新登录");
                    }else{
                        $this->error(D("User")->getError());
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
                $rs = D("User")->doAdd($info);
                if($rs){
                    $retMsg = result(200,'ok',$rs);
                }else{
                    $retMsg = result(400,D("User")->getError());
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
        if ($this->checkAdmin() === true) {
            if (I("post.do") == "delUser") {
                $user_id = I("post.user_id");
                $rs = D("User")->del($user_id);
                if($rs){
                    $retMsg = result(200,'ok',$rs);
                }else{
                    $retMsg = result(400,D("User")->getError());
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