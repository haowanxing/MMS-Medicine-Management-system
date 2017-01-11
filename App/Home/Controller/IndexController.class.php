<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $User = A('User');
        if(!$User->checkLogin()){
            $this->redirect("User/login");
        }else{
            $this->redirect("Store/index");
        }
    }
    public function getDrugList(){
        $Drug = A("Drug");
        $this->ajaxReturn($Drug->getDrugList(),'json');
    }
    public function getStorageList(){
        $Storage = A("Storage");
        $this->ajaxReturn($Storage->getStorageRecord(),'json');
    }
    public function getSellList(){
        isset($_POST['page']) ? $page = I("post.page") : $page = 1;
        isset($_POST['size']) ? $size = I("post.size") : $size = 10;
        $rs = D("Sell")->get_list(array(),$size,$page);
        $this->ajaxReturn($rs,'json');
    }
    public function getStockList(){
        $Stock = A("Stock");
        $this->ajaxReturn($Stock->getStockList(),'json');
    }
    public function getAdjustList(){
        $Adjust = A("Adjust");
        $this->ajaxReturn($Adjust->getAdjustList(),'json');
    }
    public function getRetList(){
        $this->ajaxReturn(A("Return")->getRetList(),'json');
    }
    public function getBreakageList(){
        $this->ajaxReturn(A("Return")->getBreakageList(),'json');
    }
    public function getUserList(){
        $this->ajaxReturn(A("User")->getUserList(),'json');
    }
}