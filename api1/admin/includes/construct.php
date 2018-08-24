<?php 
    if(isset($_GET['id'])){
       $dan = intval($_GET['id']);
       $models_menus = new $model_class_name();
       $obj = $models_menus->getObject($dan); 
    }
?>