<?php

class Persistents_PrepaidDiscount extends Persistents_Core {
    
    private $id           = 0;
    public $discount         = 0;
    public $time         = 0;
    public $orders         = 0;
    public $status         = 0; 

    function getId() {
    	return $this->id;
    }

    function getClassName() {
        return __CLASS__;
    }
}