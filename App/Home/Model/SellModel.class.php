<?php
namespace Home\Model;
use Think\Model;

class SellModel extends Model
{
    public function sell($data = array()){
        $Stock = M('Stock');
        $orderNo = createOrderNo();
        $seller = I("session.userId");
        $totalPrice = 0;
        $this->startTrans();
        foreach($data['stock_id'] as $k => $v){
            if(empty($v))
                continue;
            if($stock = $Stock->where('stock_id='.$v)->find()){
                if($data['sell_amount'][$k] < 1){
                    $this->error = "输入的{$data['name'][$k]}的数量有误";
                    return false;
                }
                if($data['sell_amount'][$k] > $stock['stock_amount']){
                    $this->error = "库存不足,剩余:".$stock['stock_amount'];
                    return false;
                }else{
                    $stockRes = $Stock->where($stock)->setDec('stock_amount',$data['sell_amount'][$k]);
                    if($stockRes){
                        $sData = array(
                                'stock_id'=>$data['stock_id'][$k],
                                'price'=>$data['price'][$k],
                                'sell_amount'=>$data['sell_amount'][$k],
                                'subtotal'=>$stock['sellprice']*$data['sell_amount'][$k],
                                'orderno'=>$orderNo,
                                );
                        if($addRes = $this->data($sData)->add()){ //销售记录成功
                            $totalPrice += $sData['subtotal'];
                        }else{
                            $this->rollback();
                            $this->error = "记录失败!";
                            return false;
                        }
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
        $oData = array(
                'orderno'=>$orderNo,
                'buyyer'=>$data['buyyer'],
                'time'=>time(),
                'total'=>$totalPrice,
                'sell_by'=>$seller,
                );
        if(M('Order')->data($oData)->add()){
            $this->commit();
            return true;
        }else{
            $this->rollback();
            $this->error = '记录订单失败,但是已经销售成功';
            return false;
        }
    }
}