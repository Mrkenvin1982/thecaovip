<?php 
include '../../../config.php'; 

foreach($_POST as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}
if(!is_object($admin_logged)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}
if (!in_array($status, array(1,0))) {
   	echo json_encode(array('code' => 1, 'msg' => 'Trạng thái không hợp lệ'));
    exit();
   }
        $models_one_mil = new Models_OneMilTransactionAccess();
       $one_mil_obj = $models_one_mil->getObject(1);
       	$one_mil_obj->status=$status;
        $models_one_mil->setPersistents($one_mil_obj);
      if ($models_one_mil->edit(array('status'),1)) {
      	 	echo json_encode(array('code' => 0, 'msg' => 'Lưu thành công'));
    exit();
      	
      }
       	echo json_encode(array('code' => 1, 'msg' => 'Lỗi'));
    exit();