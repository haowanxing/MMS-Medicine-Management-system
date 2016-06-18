<?php
namespace Home\Controller;

use Think\Controller;

class BreakageController extends Controller
{
    private $data = array();
    private $break_id;
    private $stock_id;
    private $break_amount;
    private $time;
    private $break_by;
    private $allprice;
    private $reason;
    private $db;
    public function _initialize()
    {
        A("User")->loginCheck();
        $this->db = M("Breakage");
    }
    public function index()
    {

        $this->show("<h2>报损类</h2>");
    }

    public function getTotalPrice(){
        return $this->db->sum("allprice");
    }
    public function getBreakageList(){
        isset($_POST['page'])?$page=I("post.page"):$page=1;
        isset($_POST['limit'])?$limit=I("post.limit"):$limit=20;
        $result = $this->db->join("__STOCK__ ON __STOCK__.stock_id = __BREAKAGE__.stock_id")->join("__DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id")->join("__USERS__ ON __USERS__.id = __BREAKAGE__.break_by")->page($page,$limit)->order("break_id desc")->select();
        $count = $this->db->count()/$limit;
        $string = "";
        foreach($result as $key=>$value){
            $string .= "<tr>";
            $string .= "<td>".$value['break_id']."</td>";
            $string .= "<td>".$value['stock_id']."</td>";
            $string .= "<td>".$value['name']."</td>";
            $string .= "<td>".$value['spec']."</td>";
            $string .= "<td>".$value['unit']."</td>";
            $string .= "<td>".$value['sellprice']."</td>";
            $string .= "<td>".$value['break_amount']."</td>";
            $string .= "<td>".$value['allprice']."</td>";
            $string .= "<td>".date("Y-m-d H:i:s",$value['time'])."</td>";
            $string .= "<td>".$value['realname']."</td>";
            $string .= "</tr>";
        }
        $retMsg = array('code'=>200,"pageCount"=>ceil($count),"table"=>$string);
        return $retMsg;
    }
    public function setDataFromPost(){
        return $this->setStockId(I("post.stock_id"))->setBreakAmount(I("post.break_amount"))->setTime(time())->setBreakBy(I("session.userId"))->setAllprice(I("post.allprice"))->setReason(I("post.reason"));
    }
    public function doAdd(){
        $Stock_id = I("post.stock_id");
        if(!empty($Stock_id) && $Stock_id > 0){
            $this->setDataFromPost();
            $dbStock = M("Stock");
            $findRes =  $dbStock->where(array("stock_id"=>$Stock_id))->find();
            if($findRes){
                if($this->break_amount > $findRes['stock_amount']){
                    $retMsg = array("code"=>400,"msg"=>"库存数量不足","result"=>0);
                }else{
                    $dbStock->where($findRes)->setDec("stock_amount",$this->break_amount);
                    $addRes = $this->db->data($this->data)->add();
                    if($addRes){
                        $retMsg = array("code"=>200,"msg"=>"ok","result"=>$addRes);
                    }else{
                        $retMsg = array("code"=>400,"msg"=>"记录失败","result"=>$addRes);
                    }
                }
            }else{
                $retMsg = array("code"=>400,"msg"=>"药品不在库中","result"=>0);
            }
        }else{
            $retMsg = array("code"=>400,"msg"=>"缺少必要参数或参数不正确","result"=>0);
        }
        $this->ajaxReturn($retMsg,'json');
    }

    /**
     * @param mixed $reason
     * @return BreakageController
     */
    public function setReason($reason)
    {
        $this->data['reason'] = $reason;
        $this->reason = $reason;
        return $this;
    }

    /**
     * @param mixed $break_id
     * @return BreakageController
     */
    public function setBreakId($break_id)
    {
        $this->data['break_id'] = $break_id;
        $this->break_id = $break_id;
        return $this;
    }

    /**
     * @param mixed $stock_id
     * @return BreakageController
     */
    public function setStockId($stock_id)
    {
        $this->data['stock_id'] = $stock_id;
        $this->stock_id = $stock_id;
        return $this;
    }

    /**
     * @param mixed $break_amount
     * @return BreakageController
     */
    public function setBreakAmount($break_amount)
    {
        $this->data['break_amount'] = $break_amount;
        $this->break_amount = $break_amount;
        return $this;
    }

    /**
     * @param mixed $time
     * @return BreakageController
     */
    public function setTime($time)
    {
        $this->data['time'] = $time;
        $this->time = $time;
        return $this;
    }

    /**
     * @param mixed $break_by
     * @return BreakageController
     */
    public function setBreakBy($break_by)
    {
        $this->data['break_by'] = $break_by;
        $this->break_by = $break_by;
        return $this;
    }

    /**
     * @param mixed $allprice
     * @return BreakageController
     */
    public function setAllprice($allprice)
    {
        $this->data['allprice'] = $allprice;
        $this->allprice = $allprice;
        return $this;
    }

}