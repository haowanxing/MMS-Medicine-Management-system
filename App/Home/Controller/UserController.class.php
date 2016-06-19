<?php
namespace Home\Controller;

use Think\Controller;

class UserController extends Controller
{
    private $data = array();
    private $id;
    private $username;
    private $password;
    private $realname;
    private $lasttime;
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

    public function getUserList()
    {
        isset($_POST['page']) ? $page = I("post.page") : $page = 1;
        isset($_POST['limit']) ? $limit = I("post.limit") : $limit = 20;
        $result = $this->db->page($page, $limit)->order("id desc")->select();
        $count = $this->db->count() / $limit;
        $string = "";
        foreach ($result as $key => $value) {
            $string .= "<tr data-toggle=\"modal\" data-target=\"#userModal\">";
            $string .= "<td>" . $value['id'] . "</td>";
            $string .= "<td>" . $value['username'] . "</td>";
            $string .= "<td>" . $value['realname'] . "</td>";
            $string .= "<td>" . ($value['admin'] == 0 ? "普通" : "管理员") . "</td>";
            $string .= "<td>" . date("Y/m/d h:i:s", $value['lasttime']) . "</td>";
            $string .= "</tr>";
        }
        $retMsg = array('code' => 200, "pageCount" => ceil($count), "table" => $string);
        return $retMsg;
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
            return true;
        }
    }

    public function checkAdmin()
    {
        if (empty(I("session.admin"))) {
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
        $result = $this->db->where($user)->find();
        if ($result) {
            $this->db->where($result)->data(array("lasttime" => time()))->save();
            $this->id = $result['id'];
            $this->username = $result['username'];
            $this->password = $result['password'];
            $this->realname = $result['realname'];
            $this->admin = $result['admin'];
            $_SESSION['userId'] = $this->id;
            $_SESSION['username'] = $this->username;
            $_SESSION['admin'] = $this->admin;
            $this->redirect("/Home/Store/Index");
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

    public function formatData()
    {
        foreach ($this->data as $key => $item) {
            if (empty($item) && $item != 0) {
                unset($this->data[$key]);
            }
        }
        return $this;
    }

    public function saveInfo()
    {
        if ($this->id !== I("session.userId") || $this->checkAdmin() === false) {
            return false;
        }
        return $this->formatData()->db->data($this->data)->save();
    }

    public function setDataFromPost()
    {
        if (I("post.password") != "") {
            $this->setPassword(md5(I("post.password")));
        }
        return $this->setId(I("post.user_id"))->setUsername(I("post.username"))->setRealname(I("post.realname"))->setAdmin(I("post.admin"));
    }

    public function doChangeInfo()
    {
        $this->setId(I("post.user_id"));
        if (!empty($this->id) && $this->id > 0) {
            $findRes = $this->formatData()->db->where($this->data)->find();
            if ($findRes) {
                $saveRes = $this->setDataFromPost()->formatData()->db->data($this->data)->save();
                if ($saveRes) {
                    $retMsg = array("code" => 200, "msg" => "ok", "result" => $saveRes);
                } else {
                    $retMsg = array("code" => 400, "msg" => "修改失败", "result" => $this->data);
                }
            } else {
                $retMsg = array("code" => 400, "msg" => "用户不存在", "result" => 0);
            }
        } else {
            $retMsg = array("code" => 400, "msg" => "缺少必要参数或不正确", "result" => 0);
        }
        $this->ajaxReturn($retMsg, 'json');
    }

    public function addUser()
    {
        if ($this->checkAdmin() === true) {
            if (I("post.do") == "addUser") {
                $info['username'] = I("post.username");
                $info['password'] = I("post.password");
                $info['realname'] = I("post.realname");
                $info['admin'] = I("post.admin", 0);
                $res = $this->db->data($info)->add();
                if ($res) {
                    $this->ajaxReturn(array('code' => 200, 'msg' => "ok", 'result' => $res));
                } else {
                    $this->ajaxReturn(array('code' => 400, 'msg' => "add error", 'result' => $this->db->error()));
                }
            } else {
                $this->display();
            }
        } else {
            $this->error("权限错误!");
        }
    }

    public function delUser()
    {
        if ($this->checkAdmin() === true) {
            if (I("post.do") == "delUser") {
                $info['id'] = I("post.id");
                $user = $this->db->where($info)->find();
                if ($user) {
                    if ($user['admin'] == 0) {
                        $res = $this->db->where($user)->delete();
                        $this->ajaxReturn(array('code' => 200, 'msg' => "ok", 'result' => $res));
                    } else {
                        $this->ajaxReturn(array('code' => 400, 'msg' => "不能删除管理员", 'result' => ""));
                    }
                } else {
                    $this->ajaxReturn(array('code' => 400, 'msg' => "no this user", 'result' => $this->db->error()));
                }
            } else {
                $this->display();
            }
        } else {
            $this->error("权限错误!");
        }
    }


    /**
     * @param mixed $id
     * @return UserController
     */
    public function setId($id)
    {
        $this->data['id'] = $id;
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $username
     * @return UserController
     */
    public function setUsername($username)
    {
        $this->data['username'] = $username;
        $this->username = $username;
        return $this;
    }

    /**
     * @param mixed $password
     * @return UserController
     */
    public function setPassword($password)
    {
        $this->data['password'] = $password;
        $this->password = $password;
        return $this;
    }

    /**
     * @param mixed $realname
     * @return UserController
     */
    public function setRealname($realname)
    {
        $this->data['realname'] = $realname;
        $this->realname = $realname;
        return $this;
    }

    /**
     * @param mixed $admin
     * @return UserController
     */
    public function setAdmin($admin)
    {
        $this->data['admin'] = $admin;
        $this->admin = $admin;
        return $this;
    }

    /**
     * @param mixed $lasttime
     * @return UserController
     */
    public function setLasttime($lasttime)
    {
        $this->data['lasttime'] = $lasttime;
        $this->lasttime = $lasttime;
        return $this;
    }
}