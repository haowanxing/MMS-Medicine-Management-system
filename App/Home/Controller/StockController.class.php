<?php
namespace Home\Controller;

use Think\Controller;

class StockController extends Controller
{
    public function _initialize()
    {
        A("User")->loginCheck();
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
        $page = I("post.page",1);
        $size = I("post.size",15);
        $rs = D("Stock")->get_list($data,$size,$page);
        $count = D("Stock")->count();
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
        $rs = D("Stock")->changePrice($stock_id,$newPrice,I("session.user.Id"));
        if($rs){
            $retMsg = result(200,'ok',$rs);
        }else{
            $retMsg = result(400,D("Stock")->getError());
        }
        $this->ajaxReturn($retMsg,'json');
    }

}