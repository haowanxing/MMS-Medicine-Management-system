<?php
namespace Home\Controller;

use Think\Controller;

class SellController extends Controller
{
    private $data = array();
    private $sell_id;
    private $stock_id;
    private $price;
    private $sell_amount;
    private $allprice;
    private $time;
    private $sell_by;
    private $db;
    public function _initialize()
    {
        $this->db = M("Sell");
    }
    public function index()
    {

        $this->show("<h2>销售类</h2>");
    }

    public function getTotalPrice(){
        return $this->db->sum("allprice");
    }
    public function getSellList(){
        isset($_POST['page'])?$page=I("post.page"):$page=1;
        isset($_POST['limit'])?$limit=I("post.limit"):$limit=20;
        $result = $this->db->join("__STOCK__ ON __STOCK__.stock_id = __SELL__.stock_id")->join("__DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id")->join("__USERS__ ON __USERS__.id = __SELL__.sell_by")->page($page,$limit)->order("sell_id DESC")->select();
        $count = $this->db->count()/$limit;
        $string = "";
        foreach($result as $key=>$value){
            $string .= "<tr>";
            $string .= "<td>".$value['sell_id']."</td>";
            $string .= "<td>".$value['stock_id']."</td>";
            $string .= "<td>".$value['name']."</td>";
            $string .= "<td>".$value['spec']."</td>";
            $string .= "<td>".$value['unit']."</td>";
            $string .= "<td>".$value['price']."</td>";
            $string .= "<td>".$value['sell_amount']."</td>";
            $string .= "<td>".$value['allprice']."</td>";
            $string .= "<td>".date("Y-m-d H:i:s",$value['time'])."</td>";
            $string .= "<td>".$value['realname']."</td>";
            $string .= "</tr>";
        }
        $retMsg = array('code'=>200,"pageCount"=>ceil($count),"table"=>$string);
        return $retMsg;
    }
    public function setDataFromPost(){
        $this->setStockId(I("post.stock_id"))->setPrice(I("post.price"))->setSellAmount(I("post.amount"))->setAllprice($this->price*$this->sell_amount)->setTime(time())->setSellBy(I("session.userId"));
        return $this;
    }
    public function doAdd(){
        $this->setDataFromPost();
        $dbStock = M("Stock");
        $stock = $dbStock->where(array("stock_id"=>$this->stock_id))->find();
        if($stock){
            if($this->sell_amount>$stock['stock_amount']){
                $retMsg = array("code"=>400,"msg"=>"库存不足,剩余:".$stock['stock_amount'],"result"=>0);
            }else{
                $stockRes = $dbStock->where($stock)->setDec("stock_amount",$this->sell_amount);
                if($stockRes){
                    $addRes = $this->addSell();
                    if($addRes){    //销售记录成功
                        $retMsg = array("code"=>200,"msg"=>"ok","result"=>$addRes);
                    }else{
                        $retMsg = array("code"=>400,"msg"=>"记录失败","result"=>$addRes);
                    }
                }else{
                    $retMsg = array("code"=>400,"msg"=>"扣除库存失败","result"=>0);
                }
            }
        }else{
            $retMsg = array("code"=>400,"msg"=>"库存中未找到药品".$dbStock->getLastSql(),"result"=>$_POST);
        }
        $this->ajaxReturn($retMsg,'json');
    }

    public function addSell($data = array()){
        if(!empty($data)){
            $tempData = $data;
        }else{
            $tempData = $this->data;
        }
        unset($tempData['sell_id']);
        return $this->db->data($tempData)->add();
    }

    /**
     * @param mixed $sell_by
     * @return SellController
     */
    public function setSellBy($sell_by)
    {
        $this->data['sell_by'] = $sell_by;
        $this->sell_by = $sell_by;
        return $this;
    }

    /**
     * @param mixed $sell_id
     * @return SellController
     */
    public function setSellId($sell_id)
    {
        $this->data['sell_id'] = $sell_id;
        $this->sell_id = $sell_id;
        return $this;
    }

    /**
     * @param mixed $stock_id
     * @return SellController
     */
    public function setStockId($stock_id)
    {
        $this->data['stock_id'] = $stock_id;
        $this->stock_id = $stock_id;
        return $this;
    }

    /**
     * @param mixed $price
     * @return SellController
     */
    public function setPrice($price)
    {
        $this->data['price'] = $price;
        $this->price = $price;
        return $this;
    }

    /**
     * @param mixed $sell_amount
     * @return SellController
     */
    public function setSellAmount($sell_amount)
    {
        $this->data['sell_amount'] = $sell_amount;
        $this->sell_amount = $sell_amount;
        return $this;
    }

    /**
     * @param mixed $allprice
     * @return SellController
     */
    public function setAllprice($allprice)
    {
        $this->data['allprice'] = $allprice;
        $this->allprice = $allprice;
        return $this;
    }

    /**
     * @param mixed $time
     * @return SellController
     */
    public function setTime($time)
    {
        $this->data['time'] = $time;
        $this->time = $time;
        return $this;
    }


}