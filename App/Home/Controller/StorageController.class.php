<?php
namespace Home\Controller;

use Think\Controller;

class StorageController extends Controller
{
    private $data = array();
    private $db;

    public function _initialize()
    {
        $this->db = M("storage");
    }
    public function index()
    {

        $this->show("<h2>入库单类</h2>");
    }

    public function getTotalPrice(){
        return $this->db->sum("allprice");
    }
    public function getStorageRecord(){
        isset($_POST['page'])?$page=I("post.page"):$page=1;
        isset($_POST['limit'])?$limit=I("post.limit"):$limit=20;
        $result = $this->db->join("__DRUGS__ ON __DRUGS__.drug_id = __STORAGE__.drug_id")->join("__USERS__ ON __USERS__.id = __STORAGE__.in_by")->page($page,$limit)->select();
        $count = $this->db->count()/$limit;
        $string = "";
        foreach($result as $key=>$value){
            $string .= "<tr>";
            $string .= "<td>".$value['storage_id']."</td>";
            $string .= "<td>".$value['name']."</td>";
            $string .= "<td>".$value['pihao']."</td>";
            $string .= "<td>".$value['pizhunwenhao']."</td>";
            $string .= "<td>".$value['amount']."</td>";
            $string .= "<td>".$value['inprice']."</td>";
            $string .= "<td>".$value['allprice']."</td>";
            $string .= "<td>".date("Y-m-d H:i:s",$value['in_time'])."</td>";
            $string .= "<td>".$value['in_from']."</td>";
            $string .= "<td>".$value['factory']."</td>";
            $string .= "<td>".$value['producedate']."</td>";
            $string .= "<td>".$value['usefuldate']."</td>";
            $string .= "<td>".$value['realname']."</td>";
            $string .= "<td>".$value['remark']."</td>";
            $string .= "</tr>";
        }
        $retMsg = array('code'=>200,"pageCount"=>ceil($count),"table"=>$string);
        return $retMsg;
    }
}