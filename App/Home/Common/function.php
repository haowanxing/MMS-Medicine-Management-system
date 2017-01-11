<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 17/1/11
 * Time: 下午5:48
 */

/**
 * @param int    $code
 * @param string $msg
 * @param null   $data
 *
 * @return array
 */
function result($code=200,$msg='',$data=null){
    return array('code'=>$code,'msg'=>$msg,'data'=>$data);
}