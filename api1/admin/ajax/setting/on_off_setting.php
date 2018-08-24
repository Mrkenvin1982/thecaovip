<?php 
include '../../../config.php'; 

foreach($_POST as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
    if (!in_array($value, array(1,0))) {
   	echo json_encode(array('code' => 1, 'msg' => 'Trạng thái không hợp lệ'));
    exit();
   }
}
if(!is_object($admin_logged)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}
$models_one_mil = new Models_OneMilTransactionAccess();
if (isset($status)) {
	 $one_mil_obj = $models_one_mil->getObjectByCondition('',array('orders'=>1));
	 $stt=$status;
}
elseif (isset($status2)) {
	$one_mil_obj = $models_one_mil->getObjectByCondition('',array('orders'=>2));
	 $stt=$status2;

}
elseif (isset($status3)) {
  $one_mil_obj = $models_one_mil->getObjectByCondition('',array('orders'=>3));
   $stt=$status3;

}
elseif (isset($status4)) {
  $one_mil_obj = $models_one_mil->getObjectByCondition('',array('orders'=>4));
   $stt=$status4;

}elseif(isset($status5)){
	$one_mil_obj = $models_one_mil->getObjectByCondition('',array('orders'=>5));
	 $stt=$status5;

}else{
  $one_mil_obj = $models_one_mil->getObjectByCondition('',array('orders'=>6));
   $stt=$status500k;
}

       	$one_mil_obj->status=$stt;
        $models_one_mil->setPersistents($one_mil_obj);
      if ($models_one_mil->edit(array('status'),1)) {
      	 	echo json_encode(array('code' => 0, 'msg' => 'Lưu thành công'));
    exit();
      	
      }
       	echo json_encode(array('code' => 1, 'msg' => 'Lỗi'));
    exit();