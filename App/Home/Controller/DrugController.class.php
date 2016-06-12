<?php
namespace Home\Controller;

use Think\Controller;

class DrugController extends Controller
{
    public function index()
    {

        $this->show("<h2>药品类</h2>");
    }
}