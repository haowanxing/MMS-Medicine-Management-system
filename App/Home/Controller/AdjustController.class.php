<?php
namespace Home\Controller;

use Think\Controller;

class AdjustController extends Controller
{
    private $data = array();
    private $db;
    public function _initialize()
    {
        A("User")->loginCheck();
        $this->db = M("Adjust");
    }
    public function index()
    {
        $this->show("<h2>调价类</h2>");
    }

    public function getAdjustList(){
        isset($_POST['page'])?$page=I("post.page"):$page=1;
        isset($_POST['limit'])?$limit=I("post.limit"):$limit=20;
        $result = $this->db->join("__STOCK__ ON __STOCK__.stock_id = __ADJUST__.stock_id")->join("__DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id")->join("__USERS__ ON __USERS__.id = __ADJUST__.adjust_by")->page($page,$limit)->select();
        $count = $this->db->count()/$limit;
        $string = "";
        foreach($result as $key=>$value){
            $string .= "<tr>";
            $string .= "<td>".$value['adjust_id']."</td>";
            $string .= "<td>".$value['stock_id']."</td>";
            $string .= "<td>".$value['name']."</td>";
            $string .= "<td>".$value['spec']."</td>";
            $string .= "<td>".$value['unit']."</td>";
            $string .= "<td>".$value['oldprice']."</td>";
            $string .= "<td>".$value['newprice']."</td>";
            $string .= "<td>".date("Y-m-d H:i:s",$value['time'])."</td>";
            $string .= "<td>".$value['realname']."</td>";
            $string .= "</tr>";
        }
        $retMsg = array('code'=>200,"pageCount"=>ceil($count),"table"=>$string);
        return $retMsg;
    }

}