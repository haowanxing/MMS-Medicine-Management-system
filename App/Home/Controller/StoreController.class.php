<?php
namespace Home\Controller;

use Think\Controller;

class StoreController extends Controller{

    public function _initialize()
    {
        A("User")->loginCheck();
    }

    public function index()
    {
        $Model = new \Think\Model();
        //药店相关
        $dbUser = M("Users");
        $User['total'] = $dbUser->count();
        $User['admin'] = $dbUser->where("admin=1")->count();
        $User['recent'] = $dbUser->where("lasttime>=".(time()-3600))->count();
        $this->assign("user",$User);
        //药品相关
        $dbDrug = M("Drugs");
        $dbStock = M("Stock");
        $Drug['total'] = $dbDrug->count();//药品种类数量
        $Drug['stock'] = $dbStock->count("DISTINCT drug_id");//库存中药品种类数量
        $Drug['out_stock'] = $Model->query("SELECT * FROM __DRUGS__ WHERE drug_id NOT IN (SELECT DISTINCT drug_id FROM __STOCK__)");
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
        $Sell['sell_money'] = $dbSell->sum("subtotal");
        $Sell['ret_money'] = $dbRet->sum("totalprice");
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
        $profitData['storage'] = M("Storage")->sum("allprice");
        $profitData['sell'] = M("Sell")->sum("subtotal");
        $profitData['return'] = M("Return")->sum("totalprice");
        $profitData['breakage'] = M("Breakage")->sum("allprice");
        $profitData['heji'] = number_format(($profitData['sell']-$profitData['storage']-$profitData['return']-$profitData['breakage']),2);
        $this->assign("profitData",$profitData);
        $this->display();
    }
    public function base(){
        A("User")->adminCheck();
        $this->assign("store",M("Store")->find());
        $this->display();
    }
    public function people(){
        $User = A("User");
        $User->adminCheck();
        $this->display();
    }

    public function com(){
        $this->display();
    }
}