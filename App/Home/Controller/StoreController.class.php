<?php
namespace Home\Controller;

use Think\Controller;

class StoreController extends Controller{

    private $shopData = array();
    public function _initialize()
    {
        A("Business")->loginCheck();
        $this->shopData = ['shop_id'=>session("business.shop_id")];
    }

    public function index()
    {
        $Model = new \Think\Model();
        //药店相关
        $dbUser = M("Business");
        $User['total'] = $dbUser->where($this->shopData)->count();
        $User['admin'] = $dbUser->where($this->shopData)->where(['admin'=>1])->count();
        $User['recent'] = $dbUser->where($this->shopData)->where("lasttime>=".(time()-3600))->count();
        $this->assign("user",$User);
        //药品相关
        $dbDrug = M("Drugs");
        $dbStock = M("Stock");
        $Drug['total'] = $dbDrug->where($this->shopData)->count();//药品种类数量
        $Drug['stock'] = $dbStock->where($this->shopData)->count("DISTINCT drug_id");//库存中药品种类数量
        $subSql = $dbStock->field("DISTINCT drug_id")->where($this->shopData)->buildSql();
        $Drug['out_stock'] = $dbDrug->where("drug_id not in {$subSql}")->where($this->shopData)->select();
        $Drug['warning'] = $dbDrug->alias("drug")->join("RIGHT JOIN __STOCK__ stock ON stock.drug_id = drug.drug_id AND stock.shop_id = drug.shop_id")->group("stock.drug_id")->field("sum(stock.stock_amount) sum_amount,stock.*,drug.*")->where(array('drug.shop_id'=>$this->shopData['shop_id']))->select();
        foreach($Drug['warning'] as $key=>$value){
            if($value['sum_amount'] > $value['lowwarning']){
                unset($Drug['warning'][$key]);
            }
        }
        $this->assign("drug",$Drug);
        //销售相关
        $dbSell = M("Sell");
        $dbRet = M("Return");
        $Sell['total'] = $dbSell->where($this->shopData)->sum("sell_amount");//销售数量
        $Sell['return'] = $dbRet->where($this->shopData)->sum("ret_amount");//退货数量
        $Sell['sell_money'] = $dbSell->where($this->shopData)->sum("subtotal");
        $Sell['ret_money'] = $dbRet->where($this->shopData)->sum("totalprice");
        $Sell['real_money'] = $Sell['sell_money']-$Sell['ret_money'];
        $this->assign("sell",$Sell);
        $this->display();
    }
    public function storage(){
        $this->display();
    }
    public function adjust(){
        $this->display();
    }
    public function sell(){
        $data = array();
        $page = I("post.page",1);
        $size = I("post.size",15);
        $rs = D("Sell")->get_list($data,$size,$page);
        $this->assign("sellList",$rs['data']['list']);
        $this->assign("show",$rs['data']['show']);
        $this->display();
    }
    public function ret(){
        $this->display();
    }
    public function stock(){
        $this->display();
    }
    public function breakage(){
        $this->display();
    }
    public function profit(){
        $profitData['storage'] = M("Storage")->where($this->shopData)->sum("allprice");
        $profitData['sell'] = M("Sell")->where($this->shopData)->sum("subtotal");
        $profitData['return'] = M("Return")->where($this->shopData)->sum("totalprice");
        $profitData['breakage'] = M("Breakage")->where($this->shopData)->sum("allprice");
        $profitData['heji'] = number_format(($profitData['sell']-$profitData['storage']-$profitData['return']-$profitData['breakage']),2);
        $this->assign("profitData",$profitData);
        $this->display();
    }
    public function base(){
        A("Business")->adminCheck();
        $this->assign("store",D("Shop")->find());
        $this->display();
    }
    public function people(){
        $User = A("Business");
        $User->adminCheck();
        $this->display();
    }

    public function com(){
        $this->display();
    }
}