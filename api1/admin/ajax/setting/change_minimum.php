<?php 
include '../../../config.php'; 

foreach($_POST as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
    if ($key!='minimum_gop'&&(!in_array(trim($value), array(10000,50000,100000,500000)))) {
       echo json_encode(array('code' => 1, 'msg' => 'Số tiền không hợp lệ'));
    exit();
    }
}
if(!is_object($admin_logged)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}
 $models_minimum = new Models_MinimumMoney();
if (isset($minimum_gop)) {

    $minimum_gop_object = $models_minimum->getObjectByCondition('',array('orders'=>4)); 
      $minimum_gop_object->price = intval($minimum_gop); 
      $models_minimum->setPersistents($minimum_gop_object);
      if ($models_minimum->edit(array('price'),1)) {
          echo json_encode(array('code' => 0, 'msg' => 'Lưu thành công'));
    exit();
  }
     
}else{
  $success =0;

    $minimum_ts_object = $models_minimum->getObjectByCondition('',array('orders'=>0)); 
      $minimum_ts_object->price = $price2; 
      $models_minimum->setPersistents($minimum_ts_object);
      if ($models_minimum->edit(array('price'),1)) {
    $success++;
  }
      $minimum_tt_object = $models_minimum->getObjectByCondition('',array('orders'=>1)); 
      $minimum_tt_object->price = $price; 
      $models_minimum->setPersistents($minimum_tt_object);
      if ($models_minimum->edit(array('price'),1)) {
    $success++;

      }
      $minimum_ftth_object = $models_minimum->getObjectByCondition('',array('orders'=>2)); 
      $minimum_ftth_object->price = $price3; 
          $models_minimum->setPersistents($minimum_ftth_object);
      if ($models_minimum->edit(array('price'),1)) {
    $success++;
  }
  if ($success==3) {
    echo json_encode(array('code' => 1, 'msg' => 'Thành công'));
    exit();
  }else{
     echo json_encode(array('code' => 1, 'msg' => 'Đã xảy ra lỗi'));
    exit();
  }
}
      
       	echo json_encode(array('code' => 1, 'msg' => 'Lỗi'));
    exit();
