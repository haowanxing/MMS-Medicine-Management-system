<?php
namespace Home\Controller;

use Think\Controller;

class StockController extends Controller
{
    public function index()
    {

        $this->show("<h2>库存类</h2>");
    }
}