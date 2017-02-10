<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 17/2/9
 * Time: 下午6:02
 */

namespace Home\Model;


use Think\Model;

class ShopModel extends Model{

    public function _initialize(){
        $shop_id = session("business.shop_id");
        $this->where(["shop_id"=>$shop_id]);
    }
}