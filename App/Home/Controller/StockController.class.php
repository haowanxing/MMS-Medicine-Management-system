<?php
namespace Home\Controller;

use Think\Controller;

class StockController extends Controller
{
    private $data = array();
    private $db;
    public function _initialize()
    {
        $this->db = M("Stock");
    }
    public function index()
    {

        $this->show("<h2>库存类</h2>");
    }
    public function getStockList(){
        isset($_POST['page'])?$page=I("post.page"):$page=1;
        isset($_POST['limit'])?$limit=I("post.limit"):$limit=20;
        $result = $this->db->join("__DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id")->page($page,$limit)->select();
        $count = $this->db->count()/$limit;
        $string = "";
        foreach($result as $key=>$value){
            $string .= "<tr>";
            $string .= "<td>".$value['stock_id']."</td>";
            $string .= "<td>".$value['name']."</td>";
            $string .= "<td>".$value['factory']."</td>";
            $string .= "<td>".$value['amount']."</td>";
            $string .= "<td>".$value['pihao']."</td>";
            $string .= "<td>".$value['pizhunwenhao']."</td>";
            $string .= "<td>".$value['sellprice']."</td>";
            $string .= "<td>".date("Y-m-d H:i:s",$value['in_time'])."</td>";
            $string .= "<td>".date("Y-m-d",$value['producedate'])."</td>";
            $string .= "<td>".date("Y-m-d",$value['usefuldate'])."</td>";
            $string .= "</tr>";
        }
        $retMsg = array('code'=>200,"pageCount"=>ceil($count),"table"=>$string);
        return $retMsg;
    }
}