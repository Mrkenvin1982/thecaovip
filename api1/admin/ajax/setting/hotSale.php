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
  $hotSaleObject = $models_prepaid->getObjectByCondition('',array('orders'=>4));
    $hotSaleObject->discount =$hotSale;
    $models_prepaid->setPersistents($hotSaleObject);
    $models_prepaid->edit(array('discount'),1);
        echo json_encode(array('code' => 0, 'msg' => 'Thay đổi chiết khấu khuyến mãi thành công!'));
    exit();
  } catch (Exception $e) {
        echo json_encode(array('code' => 1, 'msg' => 'Đã xảy ra lỗi!'));
    exit();
  }
