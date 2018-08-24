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
if (strlen($usn)<4) {
	  echo json_encode(array('code' => 1, 'msg' => 'Tên tài khoản phải dài hơn 4 ký tự'));
    exit();
}
if (strlen($pwd)<6) {
	  echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu phải dài hơn 6 ký tự'));
    exit();
}
$models_admin = new Models_AdminUsers();
$checkadmin = $models_admin->getObjectByCondition('',array('username'=>$usn));
if (is_object($checkadmin)) {
	  echo json_encode(array('code' => 1, 'msg' => 'Tài khoản này đã tồn tại'));
    exit();
}

$admin = new Persistents_AdminUsers();
$admin->username=$usn;
$admin->password=md5($pwd);
$admin->status=1;
$models_admin->setPersistents($admin);

if ($models_admin->add(1)) {
	  echo json_encode(array('code' => 0, 'msg' => "Thêm admin thành công"));
    exit();
};
	  echo json_encode(array('code' => 0, 'msg' => 'Đã xảy ra lỗi'));
    exit();