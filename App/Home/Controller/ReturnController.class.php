<?php
namespace Home\Controller;

use Think\Controller;

class ReturnController extends Controller
{
    private $data = array();
    private $db;
    public function _initialize()
    {
        $this->db = M("Return");
    }
    public function index()
    {

        $this->show("<h2>退货类</h2>");
    }

    public function getTotalPrice(){
        return $this->db->sum("totalprice");
    }
    public function getRetList(){
        isset($_POST['page'])?$page=I("post.page"):$page=1;
        isset($_POST['limit'])?$limit=I("post.limit"):$limit=20;
        $result = $this->db->join("__STOCK__ ON __STOCK__.stock_id = __RETURN__.stock_id")->join("__DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id")->join("__USERS__ ON __USERS__.id = __RETURN__.return_by")->page($page,$limit)->select();
        $count = $this->db->count()/$limit;
        $string = "";
        foreach($result as $key=>$value){
            $string .= "<tr>";
            $string .= "<td>".$value['ret_id']."</td>";
            $string .= "<td>".$value['stock_id']."</td>";
            $string .= "<td>".$value['name']."</td>";
            $string .= "<td>".$value['spec']."</td>";
            $string .= "<td>".$value['unit']."</td>";
            $string .= "<td>".$value['sellprice']."</td>";
            $string .= "<td>".$value['ret_amount']."</td>";
            $string .= "<td>".$value['totalprice']."</td>";
            $string .= "<td>".date("Y-m-d H:i:s",$value['time'])."</td>";
            $string .= "<td>".$value['realname']."</td>";
            $string .= "<td>".$value['reason']."</td>";
            $string .= "</tr>";
        }
        $retMsg = array('code'=>200,"pageCount"=>ceil($count),"table"=>$string);
        return $retMsg;
    }
}