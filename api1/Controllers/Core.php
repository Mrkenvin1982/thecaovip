<?php

class Controllers_Core {
    
    private   $url;
    protected $list_file;
    protected $add_file;
    protected $info_url;
    
    protected $detail_file;
    protected $translate_file;
    protected $image_path;
    private   $models;
    protected $entity;
    protected $persistents;
    
    function __construct() {
        global $admin_url;
        global $base_url;
        $this->list_file = $base_url."/admin/modules/".$this->entity."/danhsach.php";
        $this->list_file2 = $admin_url."/modules/".$this->entity."/sua.php";
        $this->list_file3 = $base_url."/admin/modules/Auto/sua.php";
        $this->add_file = $admin_url."/modules/".$this->entity."/sua.php";
        $this->info_url = $base_url."/thong-tin-ca-nhan.php";
        $this->detail_file = $admin_url . "/index.php?module=" . $this->entity . "&file=detail";
        $this->translate_file = $admin_url . "/index.php?module=" . $this->entity . "&file=translate";
        if(isset($_SESSION['object_wrapper'])) {
            unset($_SESSION['object_wrapper']);
        }
    }
    
    function execute($action) {
        $this->$action();
    }
    
    function setRedirect($url) {
        $this->url = $url;
    }
    
    function getModels() {
        $class_name = "Models_" . ucfirst($this->entity);
        return new $class_name();
    }
    
    function getPersistents() {
        $class_name = "Persistents_" . ucfirst($this->entity);
        return new $class_name();
    }
    
    function setPersistents($persistents) {
        $this->persistents = $persistents;
    }
    
    function redirect() {
        header("location:" . $this->url);
    }
    
    function view() {
        $this->list_file = $admin_url . "/admin/modules/" . $_REQUEST['controller']."/danhsach.php?";
        $req = '';
        foreach($_REQUEST as $key => $value) {
            $req .= "&" . $key . "=" . $value;
        }
        //echo $req;exit;
        $this->setRedirect($this->list_file . $req);
    }
    
    function add() {
        $this->setRedirect($this->add_file);
    }
    
    function edit() {
        $cid = $_REQUEST['cid'];
        $id = $cid[0];
        $this->setRedirect($this->add_file . '&id=' . $id);
    }
    
    function detail() {
        $cid = $_REQUEST['cid'];
        $id = $cid[0];
        $this->setRedirect($this->detail_file . '&id=' . $id);
    }
    
    function delete() {
        $cid = $_REQUEST['cid'];
        $models = $this->getModels();
        //$affected_rows = 0;
        foreach($cid as $id) {
            if(!$models->delete($id)) {
                break;
            }
            //$affected_rows++;
        }
        $models->delete($cid);
        $this->setRedirect($this->list_file); 
    }
    
    function delete2() {
        $cid = $_REQUEST['cid'];
        
        $models = $this->getModels();
        //$affected_rows = 0;
        //foreach($cid as $id) {
        //    if(!$models->delete($id)) {
          //      break;
         //   }
         //   $affected_rows++;
        //}
        $models->delete($cid);
        $this->setRedirect($this->list_file); 
    }
}