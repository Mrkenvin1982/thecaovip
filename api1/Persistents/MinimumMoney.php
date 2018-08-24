<?php

class Persistents_MinimumMoney extends Persistents_Core {
    
    private $id           = 0;
    public $price          = 0;
    public $orders         = 0;
    public $status         = 0; 

    function getId() {
    	return $this->id;
    }

    function getClassName() {
        return __CLASS__;
    }
}