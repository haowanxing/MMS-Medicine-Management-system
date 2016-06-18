<?php
namespace Home\Controller;

use Think\Controller;

class ReturnController extends Controller
{
    private $data = array();
    private $ret_id;
    private $sell_id;
    private $ret_amount;
    private $totalprice;
    private $time;
    private $return_by;
    private $reason;
    private $db;
    public function _initialize()
    {
        A("User")->loginCheck();
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
        $result = $this->db->join("__SELL__ ON __SELL__.sell_id = __RETURN__.sell_id")->join("__STOCK__ ON __STOCK__.stock_id = __SELL__.stock_id")->join("__DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id")->join("__USERS__ ON __USERS__.id = __RETURN__.return_by")->page($page,$limit)->order("ret_id desc")->select();
        $count = $this->db->count()/$limit;
        $string = "";
        foreach($result as $key=>$value){
            $string .= "<tr>";
            $string .= "<td>".$value['ret_id']."</td>";
            $string .= "<td>".$value['sell_id']."</td>";
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
    public function doAdd(){
        $sell_id = I("post.sell_id");
        $ret_amount = I("post.ret_amount");
        $ret_price = I("post.ret_price");
        if(!empty($sell_id) && $sell_id!=0 && $ret_amount !=0 && $ret_price!=0){
            $dbSell = M("Sell");
            $dbStock = M("Stock");
            $sellData = array("sell_id"=>$sell_id);
            $findRes = $dbSell->where($sellData)->find();
            if($findRes){
                if($findRes['sell_status'] == 0){
                    //修改销售记录状态
                    if($dbSell->where($findRes)->setInc("sell_status") && $dbStock->where(array("stock_id"=>$findRes['stock_id']))->setInc("stock_amount",$ret_amount)){
                        $addRes = $this->setSellId(I("post.sell_id"))->setRetAmount(I("post.ret_amount"))->setTotalprice($ret_price)->setTime(time())->setReturnBy(I("session.userId"))->setReason(I("post.reason"))
                                ->formatData()->db->data($this->data)->add();
                        if($addRes){
                            $retMsg = array("code"=>200,"msg"=>"ok","result"=>$addRes);
                        }else{
                            $dbStock->rollback();
                            $dbSell->rollback();
                            $retMsg = array("code"=>400,"msg"=>"记录出错","result"=>$addRes);
                        }
                    }else{
                        $retMsg = array("code"=>400,"msg"=>"修改销售单出错","result"=>0);
                    }
                }else{
                    $retMsg = array("code"=>400,"msg"=>"该销售码已经是退货状态","result"=>0);
                }
            }else{
                $retMsg = array("code"=>400,"msg"=>"销售码不存在","result"=>"error");
            }
        }else{
            $retMsg = array("code"=>400,"msg"=>"缺少必要参数或参数不合法","result"=>"error");
        }
        $this->ajaxReturn($retMsg,'json');
    }

    /**
     * 格式化data,删除无用的字段
     * @return $this
     */
    public function formatData(){
        foreach($this->data as $key=>$item){
            if(empty($item)){
                unset($this->data[$key]);
            }
        }
        return $this;
    }
    /**
     * @param mixed $reason
     * @return ReturnController
     */
    public function setReason($reason)
    {
        $this->data['reason'] = $reason;
        $this->reason = $reason;
        return $this;
    }

    /**
     * @param mixed $ret_id
     * @return ReturnController
     */
    public function setRetId($ret_id)
    {
        $this->data['ret_id'] = $ret_id;
        $this->ret_id = $ret_id;
        return $this;
    }

    /**
     * @param mixed $sell_id
     * @return ReturnController
     */
    public function setSellId($sell_id)
    {
        $this->data['sell_id'] = $sell_id;
        $this->sell_id = $sell_id;
        return $this;
    }

    /**
     * @param mixed $ret_amount
     * @return ReturnController
     */
    public function setRetAmount($ret_amount)
    {
        $this->data['ret_amount'] = $ret_amount;
        $this->ret_amount = $ret_amount;
        return $this;
    }

    /**
     * @param mixed $totalprice
     * @return ReturnController
     */
    public function setTotalprice($totalprice)
    {
        $this->data['totalprice'] = $totalprice;
        $this->totalprice = $totalprice;
        return $this;
    }

    /**
     * @param mixed $time
     * @return ReturnController
     */
    public function setTime($time)
    {
        $this->data['time'] = $time;
        $this->time = $time;
        return $this;
    }

    /**
     * @param mixed $return_by
     * @return ReturnController
     */
    public function setReturnBy($return_by)
    {
        $this->data['return_by'] = $return_by;
        $this->return_by = $return_by;
        return $this;
    }

}