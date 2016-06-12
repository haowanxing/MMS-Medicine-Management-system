<?php
namespace Home\Controller;

use Think\Controller;

class UserController extends Controller
{
    private $id;
    private $username;
    private $password;
    private $realname;
    private $admin;
    private $db;

    public function _initialize()
    {
        $this->db = M("users");
        if ($this->checkLogin() === true) {
            $data['username'] = I("session.username");
            $info = $this->db->where($data)->find();
            if ($info) {
                $this->id = $info['id'];
                $this->username = $info['username'];
                $this->password = $info['password'];
                $this->realname = $info['realname'];
                $this->admin = $info['admin'];
            }
        }
    }

    public function index()
    {

        $this->show("<h2>用户类</h2>");
    }

    public function checkLogin()
    {
        if (empty(I("session.uaername"))) {
            return false;
        } else {
            return true;
        }
    }
    public function checkAdmin(){
        if (empty(I("session.admin"))) {
            return false;
        } else {
            return true;
        }
    }

    public function login()
    {
        $this->assign("actionUrl",U("user/dologin"));
        $this->display();
    }

    public function doLogin()
    {
        if ($this->checkLogin() === true) {
            $this->error("请先退出已经登录的用户!");
        }
        $user['username'] = I("post.username");
        $user['password'] = md5(I("post.password"));
        $result = $this->db->where($user)->find();
        if ($result) {
            $this->username = $result['username'];
            $this->password = $result['password'];
            $this->realname = $result['realname'];
            $this->admin = $result['admin'];
            $_SESSION['userId'] = $this->id;
            $_SESSION['username'] = $this->username;
            $_SESSION['admin'] = $this->admin;
            $this->success("登录成功!");
        } else {
            $this->error("用户名或者密码不正确");
        }
    }

    public function doLogout()
    {
        if ($this->checkLogin() === true) {
            unset($_SESSION['username']);
            unset($_SESSION['admin']);
            unset($_SESSION['userId']);
            $this->success("成功退出!");
        } else {
            $this->error("当前没有登录的用户可以退出!");
        }
    }

    public function saveInfo()
    {
        if($this->id !== I("session.userId") || $this->checkAdmin()===false){
            return false;
        }
        $info['id'] = $this->id;
        $info['username'] = $this->username;
        $info['password'] = $this->password;
        $info['realname'] = $this->realname;
        $info['admin'] = $this->admin;
        return $this->db->data($info)->save();
    }


    /**
     * @param mixed $id
     * @return UserController
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $username
     * @return UserController
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param mixed $password
     * @return UserController
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param mixed $realname
     * @return UserController
     */
    public function setRealname($realname)
    {
        $this->realname = $realname;
        return $this;
    }

    /**
     * @param mixed $admin
     * @return UserController
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
        return $this;
    }
}