<?php
namespace Home\Controller;

use Think\Controller;

class StockController extends Controller
{
    private $data = array();
    private $stock_id;
    private $drug_id;
    private $factory;
    private $amount;
    private $pihao;
    private $pizhunwenhao;
    private $sellprice;
    private $in_time;
    private $producedate;
    private $usefuldate;
    private $db;
    public function _initialize()
    {
        A("User")->loginCheck();
        $this->db = M("Stock");
    }
    public function index()
    {

        $this->show("<h2>库存类</h2>");
    }

    /**
     * 获取库存表格
     * @return array
     */
    public function getStockList(){
        isset($_POST['page'])?$page=I("post.page"):$page=1;
        isset($_POST['limit'])?$limit=I("post.limit"):$limit=20;
        $result = $this->db->join("__DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id")->page($page,$limit)->order("stock_id desc")->select();
        $count = $this->db->count()/$limit;
        $string = "";
        foreach($result as $key=>$value){
            $string .= "<tr data-toggle=\"modal\" data-target=\"#stockModal\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"点击我可以进行调价\">";
            $string .= "<td>".$value['stock_id']."</td>";
            $string .= "<td>".$value['name']."</td>";
            $string .= "<td>".$value['factory']."</td>";
            $string .= "<td>".$value['stock_amount']."</td>";
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

    /**
     * 通过药品ID查找库存
     * @param $drug_id
     * @return mixed
     */
    public function getStockByDrugId($drug_id)
    {
        return $this->db->where(array("drug_id"=>$drug_id))->find();
    }
    public function getInfo(){
        if(!empty(I("post.pinyinma"))){
            $Drug = A("Drug");
            $drugRes = $Drug->getInfoByPinYinMa(I("post.pinyinma"));
            $this->data['drug_id'] = $drugRes['drug_id'];
            $this->data = $this->db->where($this->data)->find();
            if($this->data){
                $retMsg = array("code"=>200,"msg"=>"ok","result"=>array_merge($drugRes,$this->data));
            }else{
                $retMsg = array("code"=>400,"msg"=>"库存中没有该药品:".I("get.pinyinma"),"result"=>$this->data);
            }
        }else{
            $retMsg = array("code"=>400,"msg"=>"缺少参数:pinyinma","result"=>0);
        }
        $this->ajaxReturn($retMsg,'json');
    }
    public function doChangePrice(){
        if(I("post.newprice") != "" && I("post.stock_id") != ""){
            $newPrice = I("post.newprice");
            $stockId = I("post.stock_id");
            $newData['stock_id'] = $stockId;
            $findRes = $this->db->where($newData)->find();
            if($findRes){
                $newData['sellprice'] = $newPrice;
                $saveRes = $this->db->data($newData)->save();
                if($saveRes){
                    $dbAdjust = M("Adjust");
                    $adjustData['stock_id'] = $stockId;
                    $adjustData['oldprice'] = $findRes['sellprice'];
                    $adjustData['newprice'] = $newPrice;
                    $adjustData['time'] = time();
                    $adjustData['adjust_by'] = I("session.userId");
                    $addRes = $dbAdjust->data($adjustData)->add();
                    if($addRes){
                        $retMst = array("code"=>200,"msg"=>"ok","result"=>$addRes);
                    }else{
                        $retMst = array("code"=>400,"msg"=>"调价记录写入失败","result"=>$addRes);
                    }
                }else{
                    $retMst = array("code"=>400,"msg"=>"调价失败","result"=>$saveRes);
                }
            }else{
                $retMst = array("code"=>400,"msg"=>"库存中没有该药品","result"=>0);
            }
        }else{
            $retMst = array("code"=>400,"msg"=>"参数错误!","result"=>0);
        }
        $this->ajaxReturn($retMst,'json');
    }

    /**
     * 保存库存信息
     * @return bool
     */
    public function save(){
        if(!empty($this->stock_id)){
            foreach($this->data as $key=>$item){
                if(empty($item)){
                    unset($this->data[$key]);
                }
            }
            return $this->db->data($this->data)->save();
        }else{
            return false;
        }
    }

    /**
     * 外部调用,入库处理
     * @return mixed
     */
    public function add(){
        $tempData = $this->data;
        unset($tempData['stock_id']);
        unset($tempData['amount']);
        unset($tempData['sellprice']);
        unset($tempData['in_time']);
        $stock = $this->db->where($tempData)->find();
        if($stock){//已经存在,则更新数量
            return $this->db->where($stock)->setInc('amount',$this->amount);
        }else{//不存在,新插入
            return $this->addStock();
        }
    }

    /**
     * 插入记录
     * @param array $data
     * @return mixed
     */
    public function addStock($data =array()){
        if(!empty($data)){
            $tempData = $data;
        }else{
            $tempData = $this->data;
        }
        unset($tempData['stock_id']);
        return $this->db->data($tempData)->add();
    }

    /**
     * 更新记录
     * @param array $data
     * @return bool
     */
    public function updateStock($data = array()){
        if(!empty($data)){
            $tempData = $data;
        }else{
            $tempData = $this->data;
        }
        if(empty($tempData['stock_id']) || $tempData['stock_id']==0){
            return false;
        }else{
            return $this->db->data($tempData)->save();
        }
    }

    /**
     * @param mixed $stock_id
     * @return StockController
     */
    public function setStockId($stock_id)
    {
        $this->data['stock_id'] = $stock_id;
        $this->stock_id = $stock_id;
        return $this;
    }

    /**
     * @param mixed $drug_id
     * @return StockController
     */
    public function setDrugId($drug_id)
    {
        $this->data['drug_id'] = $drug_id;
        $this->drug_id = $drug_id;
        return $this;
    }

    /**
     * @param mixed $factory
     * @return StockController
     */
    public function setFactory($factory)
    {
        $this->data['factory'] = $factory;
        $this->factory = $factory;
        return $this;
    }

    /**
     * @param mixed $amount
     * @return StockController
     */
    public function setAmount($amount)
    {
        $this->data['stock_amount'] = $amount;
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param mixed $pihao
     * @return StockController
     */
    public function setPihao($pihao)
    {
        $this->data['pihao'] = $pihao;
        $this->pihao = $pihao;
        return $this;
    }

    /**
     * @param mixed $pizhunwenhao
     * @return StockController
     */
    public function setPizhunwenhao($pizhunwenhao)
    {
        $this->data['pizhunwenhao'] = $pizhunwenhao;
        $this->pizhunwenhao = $pizhunwenhao;
        return $this;
    }

    /**
     * @param mixed $sellprice
     * @return StockController
     */
    public function setSellprice($sellprice)
    {
        $this->data['sellprice'] = $sellprice;
        $this->sellprice = $sellprice;
        return $this;
    }

    /**
     * @param mixed $in_time
     * @return StockController
     */
    public function setInTime($in_time)
    {
        $this->data['in_time'] = $in_time;
        $this->in_time = $in_time;
        return $this;
    }

    /**
     * @param mixed $producedate
     * @return StockController
     */
    public function setProducedate($producedate)
    {
        $this->data['producedate'] = $producedate;
        $this->producedate = $producedate;
        return $this;
    }

    /**
     * @param mixed $usefuldate
     * @return StockController
     */
    public function setUsefuldate($usefuldate)
    {
        $this->data['usefuldate'] = $usefuldate;
        $this->usefuldate = $usefuldate;
        return $this;
    }

}