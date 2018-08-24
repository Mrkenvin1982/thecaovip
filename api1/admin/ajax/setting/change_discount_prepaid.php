<?php 
include '../../../config.php'; 

foreach($_POST as $key => $value) {
    $$key = intval(trim($value));
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}
if(!is_object($admin_logged)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}
  try {
         $models_prepaid = new Models_PrepaidDiscount();
  $viettel_discount_object = $models_prepaid->getObjectByCondition('',array('orders'=>1));
    $viettel_discount_object->discount =$viettel_discount;
    $models_prepaid->setPersistents($viettel_discount_object);
    $models_prepaid->edit(array('discount'),1);
    $mobi_discount_object = $models_prepaid->getObjectByCondition('',array('orders'=>2));
    $mobi_discount_object->discount =$mobi_discount;
    $models_prepaid->setPersistents($mobi_discount_object);
    $models_prepaid->edit(array('discount'),1);
    $vina_discount_object = $models_prepaid->getObjectByCondition('',array('orders'=>3)); 
    $vina_discount_object->discount =$vina_discount;
    $models_prepaid->setPersistents($vina_discount_object);
    $models_prepaid->edit(array('discount'),1);
        echo json_encode(array('code' => 0, 'msg' => 'Thay đổi chiết khấu thành công!'));
    exit();
  } catch (Exception $e) {
        echo json_encode(array('code' => 1, 'msg' => 'Đã xảy ra lỗi!'));
    exit();
  }
