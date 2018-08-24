<?php

class Persistents_CardTypesValues extends Persistents_Core {
    
    private $id           = 0;
    public $type_id         = 0;
    public $value_id         = 0;
    public $orders         = 0;
    public $status         = 0; 

    function getId() {
    	return $this->id;
    }

    function getClassName() {
        return __CLASS__;
    }
}