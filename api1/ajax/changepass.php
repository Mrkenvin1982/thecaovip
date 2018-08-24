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

$salt = $adminuser->salt;
if($adminuser->password !== hash('sha256', $old_password . $salt)) {
    echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu cũ không chính xác!'));
    exit();
}

if(strlen($new_password) < 8) {
    echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu mới tối thiểu 8 ký tự!'));
    exit();
}

// change pass
$new_salt = md5(rand(0, 10000000000000));
$new_password = hash('sha256', $new_password . $new_salt);
$stt=1;
if ($adminuser->change_pass==0) {
    $stt=2;
}
$adminuser->salt = $new_salt;
$adminuser->password = $new_password;
$adminuser->change_pass = $stt;
$models_users->setPersistents($adminuser);

if($models_users->edit(array('salt', 'password', 'change_pass'), 1)) {
    echo json_encode(array('code' => 0, 'msg' => 'Thanh cong!'));
    unset($_SESSION['admin_logged']);
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'That bai!'));
}