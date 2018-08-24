<?php

class Persistents_DiscountPercentage extends Persistents_Core {
    
    private $id           = 0;
    public $user_id            = 0;
    public $viettel_percent            = 0;
    public $mobi_percent            = 0;
    public $vina_percent            = 0;
    public $ftth_percent            = 0;
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