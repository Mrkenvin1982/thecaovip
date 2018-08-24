<?php

class Persistents_Phones extends Persistents_Core {
    
    private $id            = 0;
    public $phone          = '';
    public $loai           = 0;
    public $type           = 0;
    public $userid         = 0;
    public $canthanhtoan   = 0;
    public $dathanhtoan    = 0;
    public $gop            = 1;
    public $last_balance   = 0;
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