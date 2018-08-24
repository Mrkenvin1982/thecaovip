<?php

class Persistents_DiscountHistories extends Persistents_Core {
    
    private $id           = 0;
    public $user_id            = 0;
    public $phone_id            = 0;
    public $money          = 0;
    public $unpaid_amount          = 0;
    public $real_discount          = 0;
    public $discount_percent            = 0;
    public $discount_money            = 0;
    public $real_money            = 0;
    public $trans_type           = 0;
    public $orders         = 0;
    public $status         = 0; 

    function getId() {
    	return $this->id;
    }
    function setId($id) {
        $this->id = $id;
    }
    
    function getClassName() {
        return __CLASS__;
    }
}