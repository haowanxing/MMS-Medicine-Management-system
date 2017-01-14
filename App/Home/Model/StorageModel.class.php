<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 17/1/12
 * Time: 上午10:41
 */

namespace Home\Model;
use Think\Model;

class StorageModel extends Model{

    public function get_list($condition=array(),$size=null,$page=1){
        $this->join("LEFT JOIN __DRUGS__ ON __DRUGS__.drug_id = __STORAGE__.drug_id");
        $this->join("LEFT JOIN __USERS__ ON __USERS__.id = __STORAGE__.in_by");
        if($size) $this->page($page, $size);
        if(!empty($condition)) $this->where($condition);
        $this->order("storage_id desc");
        $rs = $this->select();
        array_walk($rs,function(&$i){
            $i['in_time'] = date("Y/m/d",$i['in_time']);
            $i['producedate'] = date("Y/m/d",$i['producedate']);
            $i['usefuldate'] = date("Y/m/d",$i['usefuldate']);
        });
        return $rs;
    }

    public function storage($pinyin,$data){
        $piYin = $pinyin;
        $dbDrugs = M("Drugs");
        $this->startTrans();
        $drug = $dbDrugs->where(array("pinyinma" => $piYin))->find();
        if ($drug){
            //写入库记录单
            $storage_data = $data;
            $storage_data['drug_id'] = $drug['drug_id'];
            $addResult = $this->add($storage_data);
            if ($addResult){
                //写库存 首先找是否存在已经入库的,否则新增

                $Stock = M("Stock");
                if ($stock = $Stock->where('drug_id='.$drug['drug_id'])->find()){
                    $stockRes = $Stock->where($stock)->setInc('stock_amount', $data['storage_amount']);
                }else{
                    $stock_Data = array('drug_id'      => $drug['drug_id'],
                                        'stock_amount' => $data['storage_amount'],
                                        'sellprice'    => $data['inprice'],
                                        'factory'      => $data['factory'],
                                        'pihao'    => $data['pihao'],
                                        'pizhunwenhao'    => $data['pizhunwenhao'],
                                        'in_time'    => $data['in_time'],
                                        'producedate'    => $data["producedate"],
                                        'usefuldate'    => $data['usefuldate'],
                    );
                    $stockRes = M('Stock')->add($stock_Data);
//                    $stockRes = $Stock->setDrugId($drug['drug_id'])->setFactory($this->factory)->setAmount($this->amount)->setPizhunwenhao($this->pizhunwenhao)->setPihao($this->pihao)->setSellprice($this->inprice)->setInTime($this->in_time)->setProducedate($this->producedate)->setUsefuldate($this->usefuldate)->add();
                }
                if ($stockRes){
                    $this->commit();
                    return $addResult;
                }else{
                    $this->rollback();
                    $this->error = "库存保存失败!";
                    return false;
                }
            }else{
                $this->rollback();
                $this->error = "入库失败,请联系管理员!";
                return false;
            }
        }else{
            $this->rollback();
            $this->error = "药品信息中没有该药品!";
            return false;
        }
    }

}