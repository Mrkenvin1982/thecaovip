<?php
include '../config.php';

// get post data
foreach($_POST as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}

if(!is_object($adminuser)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}

$models_users = new Models_Users();
$user = $models_users->getObjectByCondition('',array('phone'=>$username));
if (is_object($user)) {
	  echo json_encode(array('code' => 0, 'msg' => $user->name));
    exit();
}
