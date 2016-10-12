<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 16/10/12
 * Time: 下午2:39
 */
function createOrderNo()
{
    return date('Ymdhis', time()).substr(floor(microtime()*1000),0,1).rand(0,9);
}