<?php

class Persistents_CommissionHistories extends Persistents_Core {
    
    private $id           = 0;
    public $user_id            = 0;
    public $discount            = 0;
    public $money          = 0;
    public $discount_histories_id           = 0;
    public $time           = 0;

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