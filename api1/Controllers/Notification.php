<?php

class Controllers_Notification extends Controllers_Core {
    
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
        $persistents->content=$content;
        $persistents->orders=$id;
        $persistents->status =$status;
        
                
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
            $this->setRedirect($this->add_file.'?id='.$id);   
        }
        else {
            
            if($id) {
                $this->setRedirect($this->add_file.'?id='.$id);
            }
            else {
                $this->setRedirect($this->add_file);
            }
        }
    }
    
    
    
}