<?php

class Persistents_Notification extends Persistents_Core {
    
    private $id           = 0;
    public $content       ='';
    public $orders        = 0;
    public $status        = 0; 

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