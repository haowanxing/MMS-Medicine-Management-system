<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 17/1/12
 * Time: 下午1:15
 */

namespace Home\Model;
use Think\Model;

class AdjustModel extends Model{

    public function get_list($condition=array(),$size=null,$page=1){
        $this->join("LEFT JOIN __STOCK__ ON __STOCK__.stock_id = __ADJUST__.stock_id");
        $this->join("LEFT JOIN __DRUGS__ ON __DRUGS__.drug_id = __STOCK__.drug_id");
        $this->join("LEFT JOIN __BUSINESS__ ON __BUSINESS__.id = __ADJUST__.adjust_by");
        if($size) $this->page($page, $size);
        if(!empty($condition)){
            $where=array();
            array_walk($condition,function($v,$k) use (&$where){$where[$this->getTableName().'.'.$k] = $v;});
            $this->where($where);
        }
        $this->order("adjust_id desc");
        $rs = $this->select();
        array_walk($rs,function(&$i){
            $i['time'] = date("Y-m-d H:i:s",$i['time']);
        });
        return $rs;
    }
}