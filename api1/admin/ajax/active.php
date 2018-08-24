<?php
require_once '../../config.php';


foreach($_POST as $key => $value) {
    $$key = trim($value);
if($value!=0){
      if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('error_code' => 1, 'message' => "Anti SQL activated."));
        exit();
    }
}
}

$classname = 'Models_' . $model;
$model = new $classname();
if($model->updateStatus($id, $status, "status")){
  echo json_encode(array('error_code' => 1, 'message' => $model->getSql()));
};

        exit();