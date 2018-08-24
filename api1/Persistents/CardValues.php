<?php

class Persistents_CardValues extends Persistents_Core {
    
    private $id           = 0;
    public $value         = 0;

    public $orders         = 0;
    public $status         = 0; 

    function getId() {
    	return $this->id;
    }

    function getClassName() {
        return __CLASS__;
    }
}