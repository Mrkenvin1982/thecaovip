<?php

class Models_Users extends Models_Core {
    
    function __construct($persitents = null) {
        $arr = explode("_", __CLASS__);
        $this->table = $arr[1];
        $this->persistents = $arr[1];
        $class_name = 'Persistents_' . $this->persistents; 
        if($persitents != null) {
            $this->persistents = $persitents;
        }
        else {
            $this->persistents = new $class_name();
        }
    }
}