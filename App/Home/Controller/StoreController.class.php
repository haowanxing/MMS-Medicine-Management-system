<?php
namespace Home\Controller;

use Think\Controller;

class StoreController extends Controller
{
    private $id;
    private $name;
    private $telephone;
    private $mobile;
    private $address;
    private $db;

    public function _initialize()
    {
        $this->db = M("store");
        $info = $this->db->find();
        if ($info) {
            $this->id = $info['id'];
            $this->name = $info['storename'];
            $this->telephone = $info['telephone'];
            $this->mobile = $info['mobile'];
            $this->address = $info['address'];
        } else {
            return false;
        }
    }

    public function index()
    {
        $this->show("<h2>店铺类</h2>");
    }

    public function getInfo()
    {
        return array("name" => $this->name, "telephone" => $this->telephone, "mobile" => $this->mobile, "address" => $this->address);
    }

    public function saveInfo()
    {
        $data['id'] = $this->id;
        $data['storename'] = $this->name;
        $data['telephone'] = $this->telephone;
        $data['mobile'] = $this->mobile;
        $data['address'] = $this->address;
        return $this->db->save($data);
    }

    /**
     * @param mixed $address
     * @return StoreController
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param mixed $mobile
     * @return StoreController
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @param mixed $name
     * @return StoreController
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $telephone
     * @return StoreController
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

}