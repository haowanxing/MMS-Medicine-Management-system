<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 17/1/12
 * Time: 下午2:14
 */

namespace Home\Model;


use Think\Model;

class StockModel extends Model{

    public function get_list(array $condition=array(),$size=null,$page=1){
        $this->join("LEFT JOIN __DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id");
        if($size) $this->page($page, $size);
        if(!empty($condition)){
            $where=array();
            array_walk($condition,function($v,$k) use (&$where){$where[$this->getTableName().'.'.$k] = $v;});
            $this->where($where);
        }
        $this->order("stock_id desc");
        $rs = $this->select();
        array_walk($rs,function(&$i){
            $i['in_time'] = date("Y-m-d H:i:s",$i['in_time']);
            $i['producedate'] = date("Y-m-d",$i['producedate']);
            $i['usefuldate'] = date("Y-m-d",$i['usefuldate']);
        });
        return $rs;
    }

    public function changePrice($where=array(),$newPrice,$operator=1){
        $stock_id = $where['stock_id'];
        $shop_id = $where['shop_id'];
        if(!empty($newPrice) && $stock_id > 0){
            $findRes = $this->where($where)->find();
            if($findRes){
                $this->startTrans();
                $newData['sellprice'] = $newPrice;
                $newData = array_merge($newData,$where);
                $saveRes = $this->data($newData)->save();
                if($saveRes){
                    $dbAdjust = M("Adjust");
                    $adjustData['stock_id'] = $stock_id;
                    $adjustData['oldprice'] = $findRes['sellprice'];
                    $adjustData['newprice'] = $newPrice;
                    $adjustData['time'] = time();
                    $adjustData['adjust_by'] = $operator;
                    $adjustData['shop_id'] = $shop_id;
                    $addRes = $dbAdjust->data($adjustData)->add();
                    if($addRes){
                        $this->commit();
                        return $addRes;
                    }else{
                        $this->rollback();
                        $this->error = "调价记录写入失败!";
                        return false;
                    }
                }else{
                    $this->rollback();
                    $this->error = "调价失败!";
                    return false;
                }
            }else{
                $this->error = "库存中没有该药品!";
                return false;
            }
        }else{
            $this->error = "参数错误!";
            return false;
        }
    }

}