<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 16/12/22
 * Time: 下午1:34
 */

namespace Home\Controller;


use Think\Controller;

class PrintController extends Controller{

    public function sales_slip($order){
        $rs = D('Print')->sales($order);
        $this->assign("order",$rs['order']);
        $this->assign("order_details",$rs['details']);
        $this->display();
    }

}