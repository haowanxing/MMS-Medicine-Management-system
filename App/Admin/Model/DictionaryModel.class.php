<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 17/1/12
 * Time: 下午4:15
 */

namespace Admin\Model;


use Think\Model;

class DictionaryModel extends Model{
    protected $tableName = 'drugs_dic';

    public function get_list(array $condition=array(),$size=null,$page=1){
        if($size) $this->page($page, $size);
        if(!empty($condition)) $this->where($condition);
        $this->order("id DESC");
        $rs = $this->select();
        return $rs;
    }

    public function edit($drug_id,$data){
        if (!empty($drug_id) && $drug_id > 0) {
            $findRes = $this->where(array('id'=>$drug_id))->find();
            if ($findRes) {
                $data['id'] = $drug_id;
                $data['input_code'] = strtoupper($data['input_code']);
                $saveRes = $this->data($data)->save();
                if ($saveRes) {
                    return $saveRes;
                } else {
                    $this->error = "没有任何变化!".$this->getError();
                    return false;
                }
            } else {
                $this->error = "记录不存在!";
                return false;
            }
        } else {
            $this->error = "缺少必要参数或不正确!";
            return false;
        }
    }

    public function doAdd($data){
        if($data['input_code'] == "" || $data['drug_name'] == "" || $data['drug_spec'] =="" || $data['drug_unit'] =="" || $data['dose_per_unit'] =="" || $data['dose_units'] ==""){
            $this->error = "表单必须填写完整,不得出现空项!";
            return false;
        }
        $info['input_code'] = strtoupper($data['input_code']);
        $info['drug_name'] = $data['drug_name'];
        $info['drug_spec'] = $data['drug_spec'];
        $info['drug_unit'] = $data['drug_unit'];
        $info['dose_per_unit'] = $data['dose_per_unit'];
        $info['dose_units'] = $data['dose_units'];
        foreach ($info as $k => $v) {
            if (empty($v) && $v < 0) {
                $this->error($k . "为必填项");
            }
        }
        if ($this->where(array("input_code" => $info['input_code']))->find()) {
            $this->error = "拼音码重复!";
            return false;
        }
        $res = $this->data($info)->add();
        if ($res) {
            return $res;
        } else {
            $this->error = "写入出错!".$this->getError();
            return false;
        }
    }

    public function del($drug_id){
        $info['id'] = $drug_id;
        $drug = $this->where($info)->find();
        if ($drug) {
            $res = $this->where($drug)->delete();
            return $res;
        } else {
            $this->error = "记录不存在!";
            return false;
        }
    }
}