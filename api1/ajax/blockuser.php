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

$userId = intval($userId);

$users = $models_users->getObjectByCondition('',array('id'=>$userId,'status'=>array(0,'!=')));
if(is_object($users)) {
    if ($users->status==1) {
            $users->status = 2;
    }else{
         $users->status = 1;
    }
if($adminuser->group_id>=$users->group_id) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đủ quyền!'));
    exit();
}
    $models_users->setPersistents($users);
    $models_users->edit(array('status'), 1);
    echo json_encode(array('code' => $users->status, 'msg' => 'Thành công!'));
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'Tài khoản không tồn tại!'));
}
