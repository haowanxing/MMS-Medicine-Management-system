<?php
namespace Home\Model;
use Think\Model;

class SellModel extends Model
{
    public function get_list($condition=array(),$size=null,$page=1){
        $this->join("LEFT JOIN __ORDER__ ON __ORDER__.orderno = __SELL__.orderno");
        $this->join("LEFT JOIN __STOCK__ ON __STOCK__.stock_id = __SELL__.stock_id");
        $this->join("LEFT JOIN __DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id");
        $this->join("LEFT JOIN __BUSINESS__ ON __BUSINESS__.id = __ORDER__.sell_by");
        if($size) $this->page($page, $size);
        if(!empty($condition)){
            $where=array();
            array_walk($condition,function($v,$k) use (&$where){$where[$this->getTableName().'.'.$k] = $v;});
            $this->where($where);
        }
        $this->order("sell_id DESC");
        $rs = $this->select();
        array_walk($rs,function(&$i){
            $i['time'] = date("Y-m-d H:i",$i['time']);
//            $i['sell_status'] = $i['sell_status']?"退货":"正常";
        });
        return $rs;
    }
    public function getById($sell_id){
        $data['sell_id'] = $sell_id;
        $this->join("LEFT JOIN __ORDER__ ON __ORDER__.orderno = __SELL__.orderno");
        $this->join("__STOCK__ ON __STOCK__.stock_id = __SELL__.stock_id");
        $this->join("__DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id");
        $this->join("__BUSINESS__ ON __BUSINESS__.id = __ORDER__.sell_by");
        $this->where($data);
        $rs = $this->find();
        return $rs;
    }
    public function getInfo($condition=array()){
        $this->join("LEFT JOIN __ORDER__ ON __ORDER__.orderno = __SELL__.orderno");
        $this->join("LEFT JOIN __STOCK__ ON __STOCK__.stock_id = __SELL__.stock_id");
        $this->join("LEFT JOIN __DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id");
        $this->join("LEFT JOIN __BUSINESS__ ON __BUSINESS__.id = __ORDER__.sell_by");
        if(!empty($condition)){
            $where=array();
            array_walk($condition,function($v,$k) use (&$where){$where[$this->getTableName().'.'.$k] = $v;});
            $this->where($where);
        }
        $rs = $this->find();
        return $rs;
    }

    public function sell($data = array()){
        $shop_id = $data['shop_id'];
        $Stock = M('Stock');
        $orderNo = createOrderNo();
        $seller = session('business.bid');
        $totalPrice = 0;
        $data_all = array();
        $this->startTrans();
        foreach($data['stock_id'] as $k => $v){
            if(empty($v))
                continue;
            if($stock = $Stock->where(array('stock_id'=>$v,'shop_id'=>$data['shop_id']))->find()){
                if($data['sell_amount'][$k] < 1){
                    $this->error = "输入的'{$data['name'][$k]}'的数量有误";
                    return false;
                }
                if($data['sell_amount'][$k] > $stock['stock_amount']){
                    $this->error = "'{$data['name'][$k]}'库存不足,剩余:".$stock['stock_amount'];
                    return false;
                }else{
                    $stockRes = $Stock->where($stock)->setDec('stock_amount',intval($data['sell_amount'][$k]));
                    if($stockRes){
                        $sData = array(
                                'stock_id'=>$data['stock_id'][$k],
                                'price'=>$data['price'][$k],
                                'sell_amount'=>$data['sell_amount'][$k],
                                'subtotal'=>$stock['sellprice']*intval($data['sell_amount'][$k]),
                                'orderno'=>$orderNo,
                                'shop_id'=>$shop_id,
                                );
                        array_push($data_all,$sData);
                        $totalPrice += $sData['subtotal'];
                    }else{
                        $this->rollback();
                        $this->error = "扣除库存的时候发生错误!";
                        return false;
                    }
                }
            }else{
                $this->error = "库存中没有找到".$data['name'][$k];
                return false;
            }
        }
        if(empty($data_all)){ $this->error = "销售数据为空";$this->rollback();return false;}
        if($addRes = $this->addAll($data_all)){ //销售记录成功
            $oData = array(
                'orderno'=>$orderNo,
                'buyyer'=>$data['buyyer'],
                'time'=>time(),
                'total'=>$totalPrice,
                'sell_by'=>$seller,
                'shop_id'=>$shop_id,
            );
            if(M('Order')->data($oData)->add()){
                $this->commit();
                return $orderNo;
            }else{
                $this->rollback();
                $this->error = '记录订单失败,但是已经销售成功';
                return false;
            }
        }else{
            $this->rollback();
            $this->error = "记录失败!";
            return false;
        }
    }
}