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
$user = $models_users->getObject($adminuser->getId());

if($user->trans_pass != md5($pass)) {
    echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu không chính xác!'));
    exit();
}
$_SESSION['uid_'.$adminuser->getId()]='ok';
   echo json_encode(array('code' => 0, 'msg' => 'ok'));
    exit();
    exit();