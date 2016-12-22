<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 16/12/22
 * Time: 下午1:40
 */

namespace Home\Model;


class PrintModel{

    public function sales($order){
        $order_info = M('order')->where(array('orderno'=>$order))->find();
        if($order_info){
            $order_info['time'] = date('Y-m-d H:i:s',$order_info['time']);
            $slip = M('sell')->alias('sl')->join("LEFT JOIN __STOCK__ AS stk ON stk.stock_id=sl.stock_id")->join("LEFT JOIN __DRUGS__ AS dg ON dg.drug_id=stk.drug_id")->where(array('orderno'=>$order))->select();
            $ret = array('order'=>$order_info,'details'=>$slip);
            return $ret;
        }else{
            return false;
        }
    }

}