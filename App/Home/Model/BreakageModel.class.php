<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 17/1/12
 * Time: 下午2:47
 */

namespace Home\Model;


use Think\Model;

class BreakageModel extends Model{

    public function get_list(array $condition=array(),$size=null,$page=1){
        $this->join("LEFT JOIN __STOCK__ ON __STOCK__.stock_id = __BREAKAGE__.stock_id");
        $this->join("LEFT JOIN __DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id");
        $this->join("LEFT JOIN __BUSINESS__ ON __BUSINESS__.id = __BREAKAGE__.break_by");
        if($size) $this->page($page, $size);
        if(!empty($condition)){
            $where=array();
            array_walk($condition,function($v,$k) use (&$where){$where[$this->getTableName().'.'.$k] = $v;});
            $this->where($where);
        }
        $this->order("break_id desc");
        $rs = $this->select();
        array_walk($rs,function(&$i){
            $i['time'] = date("Y-m-d H:i:s",$i['time']);
        });
        return $rs;
    }

    public function doBreak($where,$amount,$price=0.00,$operator=1,$reason="无"){
        $stock_id = $where['stock_id'];
        $shop_id = $where['shop_id'];
        $amount = intval($amount);
        if($amount<1){
            $this->error = "数量不合法！";
            return false;
        }
        if($price<=0){
            $this->error = "价格不正确！";
            return false;
        }
        if(!empty($stock_id) && $stock_id > 0){
            $dbStock = M("Stock");
            $findRes =  $dbStock->where($where)->find();
            if($findRes){
                $this->startTrans();
                if($amount > $findRes['stock_amount'] || $amount<1){
                    $this->rollback();
                    $this->error = "库存数量不足!";
                    return false;
                }else{
                    $dbStock->where($findRes)->setDec("stock_amount",$amount);
                    $add_data['stock_id'] = $stock_id;
                    $add_data['break_amount'] = $amount;
                    $add_data['allprice'] = $price;
                    $add_data['break_by'] = $operator;
                    $add_data['reason'] = $reason;
                    $add_data['time'] = time();
                    $add_data['shop_id'] = $shop_id;
                    $addRes = $this->add($add_data);
                    if($addRes){
                        $this->commit();
                        return $addRes;
                    }else{
                        $this->rollback();
                        $this->error = "记录失败!";
                        return false;
                    }
                }
            }else{
                $this->error = "药品不在库中!";
                return false;
            }
        }else{
            $this->error = "缺少必要参数或参数不正确!";
            return false;
        }
    }

}