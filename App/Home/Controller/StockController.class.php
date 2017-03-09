<?php
namespace Home\Controller;

use Think\Controller;

class StockController extends Controller
{
    private $shopData = array();
    public function _initialize()
    {
        A("Business")->loginCheck();
        $this->shopData = ['shop_id'=>session("business.shop_id")];
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
        $data = array();
        I("post.pinyinma")==''?:$data['pinyinma'] = I("post.pinyinma");
        $data = array_merge($data,$this->shopData);
        $page = I("post.page",1);
        $size = I("post.size",15);
        $rs = D("Stock")->get_list($data,$size,$page);
        $count = D("Stock")->where($this->shopData)->count();
        $ret = result(200,'ok',array("list"=>$rs,"count"=>intval($count)));
        $this->ajaxReturn($ret,'json');
    }

    public function getInfo(){
        if(!empty(I("post.pinyinma"))){
            $drugRes = D("Drugs")->getByPinYin(I("post.pinyinma"));
            $data['drug_id'] = $drugRes['drug_id'];
            $data = M("Stock")->where($data)->find();
            if($data){

                $retMsg = result(200,'ok',array_merge($drugRes,$data));
            }else{
                $retMsg = result(400,"库存中没有该药品:".I("post.pinyinma"),$data);
            }
        }else{
            $retMsg = result(300,'缺少参数:pinyinma');
        }
        $this->ajaxReturn($retMsg,'json');
    }
    public function doChangePrice(){
        $stock_id = I("post.stock_id");
        $newPrice = I("post.newprice");
        $where['stock_id'] = $stock_id;
        $where = array_merge($where,$this->shopData);
        $rs = D("Stock")->changePrice($where,$newPrice,session("business.bid"));
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Stock")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }

}