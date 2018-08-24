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
if (strlen($password)<6) {
	  echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu phải dài hơn 6 ký tự'));
    exit();
}
$models_admin = new Models_AdminUsers();
$admin = $models_admin->getObject($id);
$admin->password=md5($password);


$models_admin->setPersistents($admin);
if ($models_admin->edit(array('password'),1)) {
	  echo json_encode(array('code' => 0, 'msg' => 'Đổi mật khẩu cho tài khoản '.$admin->username." thành công!"));
    exit();
};
	  echo json_encode(array('code' => 0, 'msg' => 'Đã xảy ra lỗi'));
    exit();