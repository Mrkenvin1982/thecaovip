<?php

class Persistents_Users extends Persistents_Core {
    
    private $id           = 0;
    public $refer         = 0;
    public $name          = '';
    public $phone         = '';
    public $balance       = 0;
    public $salt          = '';
    public $password      = '';
    public $trans_pass      = '';

    public $change_pass    = 0;
    public $scret_key    = '';
    public $group_id    = '';


    public $time          = 0;
    public $orders        = 0;
    public $status        = 0; 

    function getId() {
    	return $this->id;
    }
    public function setId($id){
            $this->id = $id;  
    }
    public function getRefer(){
            return $this->refer;   
    }
    public function setRefer($refer){
            $this->refer = $refer;  
    }
    public function getName(){
            return $this->name;   
    }
    public function setName($name){
            $this->name = $name;  
    }
    public function getPhone(){
            return $this->phone;   
    }
    public function setPhone($phone){
            $this->phone = $phone;  
    }
    public function getSalt(){
            return $this->salt;   
    }
    public function setSalt($salt){
            $this->salt = $salt;  
    }
    public function getPassword(){
            return $this->password;   
    }
    public function setPassword($password){
            $this->password = $password;  
    }
    public function getTrans(){
            return $this->trans_pass;   
    }
    public function setTrans($trans_pass){
            $this->trans_pass = $trans_pass;  
    }
    public function getChange(){
            return $this->change_pass;   
    }
    public function setChange($change_pass){
            $this->change_pass = $change_pass;  
    }
    public function getScret(){
            return $this->scret_key;   
    }
    public function setScret($scret_key){
            $this->scret_key = $scret_key;  
    }
    public function getTime(){
            return $this->time;   
    }
    public function setTime($time){
            $this->time = $time;  
    }
    public function getBalance(){
            return $this->balance;   
    }
    public function setBalance($balance){
            $this->balance = $balance;  
    }
    public function getGroupId(){
            return $this->status;   
    }
    public function setGroupId($group_id){
            $this->group_id = $group_id;    
    }
    public function getOrders(){
            return $this->orders;   
    }
    public function setOrders($orders){
            $this->orders = $orders;    
    }
    public function getStatus(){
            return $this->status;   
    }
    public function setStatus($status){
            $this->status = $status;    
    }
    function getClassName() {
        return __CLASS__;
    }
}