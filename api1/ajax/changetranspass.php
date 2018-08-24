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

if($user->trans_pass != md5($old_password)) {
    echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu cũ không chính xác!'));
    exit();
}

if(strlen($new_password) < 8) {
    echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu mới tối thiểu 8 ký tự!'));
    exit();
}

// change pass
$user->trans_pass = md5($new_password);
$user->change_pass = 1;
$models_users->setPersistents($user);
if($models_users->edit(array('trans_pass', 'change_pass'), 1)) {
    echo json_encode(array('code' => 0, 'msg' => 'Thành công!'));
$_SESSION['admin_logged'] = $user;

}
else {
    echo json_encode(array('code' => 1, 'msg' => 'That bai!'));
}