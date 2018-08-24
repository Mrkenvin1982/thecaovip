<?php

class Persistents_CardStores extends Persistents_Core {
    
    private $id           = 0;
    public $pin            = '';
    public $seri            = '';
    public $expire_date          = '';
    public $price           = 0;
    public $card_type           = 0;
    public $time_in           = 0;
    public $time_out          = 0;
    public $result          = 0;
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