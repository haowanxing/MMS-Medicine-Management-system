<?php
namespace Home\Controller;

use Think\Controller;

class StoreController extends Controller
{
    private $data = array();
    private $db;

    public function _initialize()
    {
        A("User")->loginCheck();
        $this->db = M("store");
        $info = $this->db->find();
        if ($info) {
            $this->data = $info;
        } else {
            return false;
        }
    }

    public function index()
    {
        //药店相关
        $dbUser = M("Users");
        $User['total'] = $dbUser->count();
        $User['admin'] = $dbUser->where("admin=1")->count();
        $User['recent'] = $dbUser->where("lasttime>=".(time()-3600))->count();
        $this->assign("user",$User);
        //药品相关
        $dbDrug = M("Drugs");
        $dbStock = M("Stock");
        $Drug['total'] = $dbDrug->count();
        $Drug['stock'] = $dbStock->count("DISTINCT drug_id");
        $Drug['outstock'] = $Drug['total']-$Drug['stock'];
        $Drug['warning'] = $dbDrug->alias("drug")->join("RIGHT JOIN __STOCK__ stock ON stock.drug_id = drug.drug_id")->group("stock.drug_id")->field("sum(stock.stock_amount) sum_amount,stock.*,drug.*")->select();
        foreach($Drug['warning'] as $key=>$value){
            if($value['sum_amount'] > $value['lowwarning']){
                unset($Drug['warning'][$key]);
            }
        }
        $this->assign("drug",$Drug);
        //销售相关
        $dbSell = M("Sell");
        $dbRet = M("Return");
        $Sell['total'] = $dbSell->sum("sell_amount");//销售数量
        $Sell['return'] = $dbRet->sum("ret_amount");//退货数量
        $Sell['sell_money'] = $dbSell->sum("allprice");
        $Sell['ret_money'] = $dbRet->sum("totalprice");
        $Sell['real_money'] = $Sell['sell_money']-$Sell['ret_money'];
        $this->assign("sell",$Sell);
        $this->display();
    }

    public function getInfo()
    {
        return $this->data;
    }

    public function saveInfo()
    {
        return $this->db->save($this->data);
    }

    /**
     * @param mixed $address
     * @return StoreController
     */
    public function setAddress($address)
    {
        $this->data['address'] = $address;
        return $this;
    }

    /**
     * @param mixed $mobile
     * @return StoreController
     */
    public function setMobile($mobile)
    {
        $this->data['mobile'] = $mobile;
        return $this;
    }

    /**
     * @param mixed $name
     * @return StoreController
     */
    public function setName($name)
    {
        $this->data['storename'] = $name;
        return $this;
    }

    /**
     * @param mixed $telephone
     * @return StoreController
     */
    public function setTelephone($telephone)
    {
        $this->data['telephone'] = $telephone;
        return $this;
    }
    public function storage(){
        $Drug = A("Drug");
        $Storage = A("Storage");
        $drugList = $Drug->getDrugList();
        $this->assign("drugList",$drugList['table']);
        $this->assign("drugListCount",$drugList['pageCount']);
        $storageList = $Storage->getStorageRecord();
        $this->assign("storageList",$storageList['table']);
        $this->assign("storageListCount",$storageList['pageCount']);
        $this->display();
    }
    public function adjust(){
        $Adjust = A("Adjust");
        $adjustList = $Adjust->getAdjustList();
        $this->assign("adjustList",$adjustList['table']);
        $this->assign("adjustListCount",$adjustList['pageCount']);
        $this->display();
    }
    public function sell(){
        $Sell = A("Sell");
        $sellList = $Sell->getSellList();
        $this->assign("sellList",$sellList['table']);
        $this->assign("sellListCount",$sellList['pageCount']);
        $this->display();
    }
    public function ret(){
        $Return = A("Return");
        $returnList = $Return->getRetList();
        $this->assign("returnList",$returnList['table']);
        $this->assign("returnListCount",$returnList['pageCount']);
        $this->display();
    }
    public function stock(){
        $stock = A("Stock");
        $List = $stock->getStockList();
        $this->assign("stockList",$List['table']);
        $this->assign("stockListCount",$List['pageCount']);
        $this->display();
    }
    public function breakage(){
        $breakage = A("Breakage");
        $List = $breakage->getBreakageList();
        $this->assign("breakageList",$List['table']);
        $this->assign("breakageListCount",$List['pageCount']);
        $this->display();
    }
    public function profit(){
        $Storage = A("Storage");  //进货单
        $Sell = A("Sell");
        $Return = A("Return");
        $Breakage = A("Breakage");
        $profitData['storage'] = $Storage->getTotalPrice();
        $profitData['sell'] = $Sell->getTotalPrice();
        $profitData['return'] = $Return->getTotalPrice();
        $profitData['breakage'] = $Breakage->getTotalPrice();
        $profitData['heji'] = number_format(($profitData['sell']-$profitData['storage']-$profitData['return']-$profitData['breakage']),2);
        $this->assign("profitData",$profitData);
        $this->display();
    }
    public function base(){
        A("User")->adminCheck();
        $Drug = A("Drug");
        $List = $Drug->getDrugList();
        $this->assign("drugList",$List['table']);
        $this->assign("drugListCount",$List['pageCount']);
        $this->assign("store",$this->data);
        $this->display();
    }
    public function people(){
        $User = A("User");
        $User->adminCheck();
        $List = $User->getUserList();
        $this->assign("userList",$List['table']);
        $this->assign("userListCount",$List['pageCount']);
        $this->display();
    }
}