<?php

class Persistents_LogCards extends Persistents_Core {
    
    private $id            = 0;
    public $pin            = '';
    public $seri           = '';
    public $price          = 0;
    public $time           = 0;
    public $msg            = 0;

    function getId() {
    	return $this->id;
    }

    function getClassName() {
        return __CLASS__;
    }
}