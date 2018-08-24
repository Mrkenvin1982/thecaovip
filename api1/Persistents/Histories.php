<?php

class Persistents_Histories extends Persistents_Core {
    
    private $id           = 0;
    public $user_id         = 0;
/*    public $user_add          = '';*/
    public $cur_balance         = 0;
    public $money         = 0;

    public $up_balance       = 0;
 /*   public $money          = '';*/
    public $time          = 0;
    public $note          = '';
    public $orders        = 0;
    public $status        = 0; 

    function getId() {
    	return $this->id;
    }

    function getClassName() {
        return __CLASS__;
    }
}