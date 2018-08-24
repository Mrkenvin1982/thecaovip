<?php

class Controllers_Phones extends Controllers_Core {
    
    function __construct($entity) {
        $this->entity = $entity;
        parent::__construct();
    }
    function save() {
        $keyz =array();
        foreach($_POST as $key => $value) {
            if ($key!= 'ok'&&$key!='controller'&&$key!='id'&&$key!= 'action') {
            $keyz[]=$key;
            }
            $$key = $value;
        }
        //  set object
        $persistents = $this->getPersistents();
        $persistents->setId($id);
        $persistents->phone = $phone;
        $persistents->type = $type;
        $persistents->canthanhtoan = $canthanhtoan;
        $persistents->dathanhtoan = $dathanhtoan;
        $persistents->gop = $gop;
        $persistents->status = $status;
                
        if($persistents->isValidate()) {
            // models wrapper
            $models = $this->getModels();
            $models->setPersistents($persistents);
            $affected_rows = 0;
            if($id) {

                $affected_rows = $models->edit($keyz, 1);
       
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
                $this->setRedirect($this->add_file.'?id='.$id);
            }
            else {
                $this->setRedirect($this->add_file);
            }
        }
    }
    
    
    
}