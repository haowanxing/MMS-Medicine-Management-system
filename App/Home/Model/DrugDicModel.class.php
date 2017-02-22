<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 17/1/12
 * Time: 下午3:24
 */

namespace Home\Model;


use Think\Model;

class DrugDicModel extends Model{
    protected $tableName = 'drugs_dic';

    public function get_list(array $condition=array(),$size=null,$page=1){
        if($size) $this->page($page, $size);
        if(!empty($condition)) $this->where($condition);
        $rs = $this->select();
        return $rs;
    }

    public function getByPinYin($pinYin){
        $data['input_code'] = $pinYin;
        $rs = $this->where($data)->find();
        return $rs;
    }

    public function edit($drug_id,$data){
        $data['input_code'] = strtoupper($data['input_code']);
        if(!empty($drug_id) && $drug_id != 0){
            $saveRes = $this->where(['id'=>$drug_id])->data($data)->save();
            if($saveRes){
                return $saveRes;
            }else{
                $this->error = "没有任何改变发生!";
                return false;
            }
        }else{
            $this->error = "药品编号必须为非零数字!";
            return false;
        }
    }

    public function doAdd($data){
        $data['input_code'] = strtoupper($data['input_code']);
        $findRes = $this->where(array("input_code"=>$data['input_code']))->find();
        if($findRes){
            $this->error = "拼音码重复!";
            return false;
        }
        $res = $this->data($data)->add();
        if($res){
            return $res;
        }else{
            $this->error = "添加失败:".$this->getError();
            return false;
        }
    }

    public function doDel($drug_id){
        $info['id'] = $drug_id;
        $findRes = $this->where($info)->find();
        if ($findRes) {
            $res = $this->where($findRes)->delete();
            return $res;
        } else {
            $this->error = "没有改药品!";
            return false;
        }
    }
}