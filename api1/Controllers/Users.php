<?php

class Controllers_Users extends Controllers_Core {
    
    function __construct($entity) {
        $this->entity = $entity;
        parent::__construct();
    }
    function save() {
        foreach($_POST as $key => $value) {
            $$key = $value;
        }
    
        //  set object
        $persistents = $this->getPersistents();
        $persistents->setId($id);
        $persistents->setBalance($balance);
        $persistents->setGroupId($group_id);
        $persistents->setStatus($status);
        
                
        if($persistents->isValidate()) {
            // models wrapper
            $models = $this->getModels();
            $models->setPersistents($persistents);
            $affected_rows = 0;
            if($id) {
                $affected_rows = $models->edit();
            }
            else {
                $affected_rows = $models->add();
            }
            
            
            // unwrapper object
            unset($_SESSION['object_wrapper']);
            $this->setRedirect($this->list_file);   
        }
        else {
            
            if($id) {
                $this->setRedirect($this->add_file);
            }
            else {
                $this->setRedirect($this->add_file);
            }
        }
    }
    function updateuser() {
             foreach($_POST as $key => $value) {
            $$key = $value;
        }
     $models_users = new Models_Users();
     $user = $models_users->getObject($id);
     $refer =$user->getRefer();
     $name=$user->getName();
     $phone =$user->getPhone();
     $salt=$user->getSalt();
     $password=$user->getPassword();
     $trans=$user->getTrans();
     $change =$user->getChange();
     $scret=$user->getScret();
     $time=$user->getTime();
     $orders=$user->getOrders();

                //  set object
        
        $persistents = $this->getPersistents();
        $persistents->setId($id);
        $persistents->setRefer($refer);
        $persistents->setName($name);
        $persistents->setPhone($phone);
        $persistents->setSalt($salt);
        $persistents->setPassword($password);
        $persistents->setTrans($trans);
        $persistents->setBalance($balance);
        $persistents->setGroupId($group_id);
        $persistents->setChange($change);
        $persistents->setScret($scret);
        $persistents->setTime($time);
        $persistents->setOrders($orders);
        $persistents->setStatus($status);
        
        
        
        if($persistents->isValidate()) {
            // models wrapper
            $models = $this->getModels();
            $models->setPersistents($persistents);
            $affected_rows = 0;
            if($id) {
                $affected_rows = $models->edit();
            }
            else {
                $affected_rows = $models->add();
            }
            
            
            // unwrapper object
            unset($_SESSION['object_wrapper']);
            $this->setRedirect($this->list_file);   
        }
        else {
            
            if($id) {
                $this->setRedirect($this->add_file);
            }
            else {
                $this->setRedirect($this->add_file);
            }
        }
    }
    
    
}