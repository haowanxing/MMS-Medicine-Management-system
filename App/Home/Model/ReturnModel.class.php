<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 17/1/12
 * Time: 下午1:33
 */

namespace Home\Model;


use Think\Model;

class ReturnModel extends Model{

    public function get_list($condition=array(),$size=null,$page=1){
        $this->join("LEFT JOIN __SELL__ ON __SELL__.sell_id = __RETURN__.sell_id");
        $this->join("LEFT JOIN __STOCK__ ON __STOCK__.stock_id = __SELL__.stock_id");
        $this->join("LEFT JOIN __DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id");
        $this->join("LEFT JOIN __BUSINESS__ ON __BUSINESS__.id = __RETURN__.return_by");
        if($size) $this->page($page, $size);
        if(!empty($condition)){
            $where=array();
            array_walk($condition,function($v,$k) use (&$where){$where[$this->getTableName().'.'.$k] = $v;});
            $this->where($where);
        }
        $this->order("ret_id desc");
        $rs = $this->select();
        array_walk($rs,function(&$i){
            $i['time'] = date("Y-m-d H:i:s",$i['time']);
        });
        return $rs;
    }

    public function doReturn($data){
        $shop_id = $data['shop_id'];
        $sell_id = $data['sell_id'];
        $ret_amount = $data['ret_amount'];
        $ret_price = $data['totalprice'];
        if(!empty($sell_id) && $sell_id!=0 && $ret_amount !=0 && $ret_price!=0){
            $this->startTrans();
            $dbSell = M("Sell");
            $dbStock = M("Stock");
            $sellData = array("sell_id"=>$sell_id,'shop_id'=>$shop_id);
            $findRes = $dbSell->where($sellData)->find();
            if($findRes){
                if($findRes['sell_status'] == 0 && $findRes['sell_amount'] >= $data['ret_amount']){
                    //修改销售记录状态
                    if($dbSell->where($findRes)->setInc("sell_status") && $dbStock->where(array("stock_id"=>$findRes['stock_id']))->setInc("stock_amount",$ret_amount)){
                        $data['time'] = time();
                        $addRes = $this->add($data);
                        if($addRes){
                            $this->commit();
                            return $addRes;
                        }else{
                            $this->rollback();
                            $this->error = "记录失败!";
                            return false;
                        }
                    }else{
                        $this->rollback();
                        $this->error = "修改销售单出错!";
                        return false;
                    }
                }else{
                    $this->rollback();
                    $this->error = "该销售码已经是退货状态或数量不正确!";
                    return false;
                }
            }else{
                $this->rollback();
                $this->error = "销售码不存在!";
                return false;
            }
        }
        $this->rollback();
        $this->error = "缺少必要参数或参数不合法!";
        return false;
    }

}