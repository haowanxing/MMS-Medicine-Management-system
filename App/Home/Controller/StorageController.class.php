<?php
namespace Home\Controller;

use Think\Controller;

class StorageController extends Controller
{
    private $data = array();
    private $storage_id;
    private $drug_id;
    private $pihao;
    private $pizhunwenhao;
    private $amount;
    private $inprice;
    private $allprice;
    private $in_time;
    private $in_from;
    private $factory;
    private $producedate;
    private $usefuldate;
    private $in_by;
    private $remark;
    private $db;

    public function _initialize()
    {
        A("User")->loginCheck();
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
        $result = $this->db->join("__DRUGS__ ON __DRUGS__.drug_id = __STORAGE__.drug_id")->join("__USERS__ ON __USERS__.id = __STORAGE__.in_by")->page($page,$limit)->order("storage_id desc")->select();
        $count = $this->db->count()/$limit;
        $string = "";
        foreach($result as $key=>$value){
            $string .= "<tr>";
            $string .= "<td>".$value['storage_id']."</td>";
            $string .= "<td>".$value['name']."</td>";
            $string .= "<td>".$value['pihao']."</td>";
            $string .= "<td>".$value['pizhunwenhao']."</td>";
            $string .= "<td>".$value['storage_amount']."</td>";
            $string .= "<td>".$value['inprice']."</td>";
            $string .= "<td>".$value['allprice']."</td>";
            $string .= "<td>".date("Y/m/d",$value['in_time'])."</td>";
            $string .= "<td>".$value['in_from']."</td>";
            $string .= "<td>".$value['factory']."</td>";
            $string .= "<td>".date("Y/m/d",$value['producedate'])."</td>";
            $string .= "<td>".date("Y/m/d",$value['usefuldate'])."</td>";
            $string .= "<td>".$value['realname']."</td>";
            $string .= "<td>".$value['remark']."</td>";
            $string .= "</tr>";
        }
        $retMsg = array('code'=>200,"pageCount"=>ceil($count),"table"=>$string);
        return $retMsg;
    }
    public function setDataFromPost(){
        $this->setPihao(I('post.pihao'))->setPizhunwenhao(I('post.pizhunwenhao'))->setAmount(I("post.amount"))
                ->setInprice(I("post.inprice"))->setAllprice(I("post.allprice")!=''?I("post.allprice"):$this->inprice*$this->amount)->setInTime(strtotime(I("post.in_time")))->setInFrom(I("post.in_from"))
                ->setFactory(I("post.factory"))->setProducedate(strtotime(I("post.producedate")))->setUsefuldate(strtotime(I("post.usefuldate")))->setInBy(I("session.userId"))
                ->setRemark(I("post.remark"));
        return $this;
    }
    public function doAdd(){
        $piYin = I("post.pinyinma");
        $dbDrugs = M("Drugs");
        $drug = $dbDrugs->where(array("pinyinma"=>$piYin))->find();
        if($drug){
            $this->setDrugId($drug['drug_id'])->setDataFromPost();
            //写入库记录单
            $addResult = $this->addStorage();
            if($addResult){
                //写库存 首先找是否存在已经入库的,否则新增
                $Stock = A("Stock");
                $stockRes = $Stock->setDrugId($drug['drug_id'])->setFactory($this->factory)->setAmount($this->amount)->setPizhunwenhao($this->pizhunwenhao)->setPihao($this->pihao)->setSellprice($this->inprice)->setInTime($this->in_time)->setProducedate($this->producedate)->setUsefuldate($this->usefuldate)->add();
                if($stockRes){
                    $retMsg = array("code"=>200,"msg"=>"ok","result"=>array("Srorage"=>$addResult,"Stock"=>$stockRes));
                }else{
                    $retMsg = array("code"=>401,"msg"=>"提交成功,库存保存失败","result"=>$addResult);
                }
            }else{
                $retMsg = array("code"=>400,"msg"=>"入库失败,请联系管理员","result"=>$addResult);
            }
        }else{
            $retMsg = array("code"=>400,"result"=>'','msg'=>"药品信息中没有该药品");
        }
        $this->ajaxReturn($retMsg,'json');
    }
    public function addStorage($data = array()){
        if(!empty($data)){
            $tempData = $data;
        }else{
            $tempData = $this->data;
        }
        unset($tempData['storage_id']);
        return $this->db->data($tempData)->add();
    }


    /**
     * @param mixed $storage_id
     * @return StorageController
     */
    public function setStorageId($storage_id)
    {
        $this->data['storage_id'] = $storage_id;
        $this->storage_id = $storage_id;
        return $this;
    }

    /**
     * @param mixed $drug_id
     * @return StorageController
     */
    public function setDrugId($drug_id)
    {
        $this->data['drug_id'] = $drug_id;
        $this->drug_id = $drug_id;
        return $this;
    }

    /**
     * @param mixed $pihao
     * @return StorageController
     */
    public function setPihao($pihao)
    {
        $this->data['pihao'] = $pihao;
        $this->pihao = $pihao;
        return $this;
    }

    /**
     * @param mixed $pizhunwenhao
     * @return StorageController
     */
    public function setPizhunwenhao($pizhunwenhao)
    {
        $this->data['pizhunwenhao'] = $pizhunwenhao;
        $this->pizhunwenhao = $pizhunwenhao;
        return $this;
    }

    /**
     * @param mixed $amount
     * @return StorageController
     */
    public function setAmount($amount)
    {
        $this->data['storage_amount'] = $amount;
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param mixed $inprice
     * @return StorageController
     */
    public function setInprice($inprice)
    {
        $this->data['inprice'] = $inprice;
        $this->inprice = $inprice;
        return $this;
    }

    /**
     * @param mixed $allprice
     * @return StorageController
     */
    public function setAllprice($allprice)
    {
        $this->data['allprice'] = $allprice;
        $this->allprice = $allprice;
        return $this;
    }

    /**
     * @param mixed $in_time
     * @return StorageController
     */
    public function setInTime($in_time)
    {
        $this->data['in_time'] = $in_time;
        $this->in_time = $in_time;
        return $this;
    }

    /**
     * @param mixed $in_from
     * @return StorageController
     */
    public function setInFrom($in_from)
    {
        $this->data['in_from'] = $in_from;
        $this->in_from = $in_from;
        return $this;
    }

    /**
     * @param mixed $factory
     * @return StorageController
     */
    public function setFactory($factory)
    {
        $this->data['factory'] = $factory;
        $this->factory = $factory;
        return $this;
    }

    /**
     * @param mixed $producedate
     * @return StorageController
     */
    public function setProducedate($producedate)
    {
        $this->data['producedate'] = $producedate;
        $this->producedate = $producedate;
        return $this;
    }

    /**
     * @param mixed $usefuldate
     * @return StorageController
     */
    public function setUsefuldate($usefuldate)
    {
        $this->data['usefuldate'] = $usefuldate;
        $this->usefuldate = $usefuldate;
        return $this;
    }

    /**
     * @param mixed $in_by
     * @return StorageController
     */
    public function setInBy($in_by)
    {
        $this->data['in_by'] = $in_by;
        $this->in_by = $in_by;
        return $this;
    }

    /**
     * @param mixed $remark
     * @return StorageController
     */
    public function setRemark($remark)
    {
        $this->data['remark'] = $remark;
        $this->remark = $remark;
        return $this;
    }
}