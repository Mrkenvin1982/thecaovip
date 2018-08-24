<?php 
include '../../config.php'; 
require_once '../pagination/paging_ajax.php'; 

foreach($_POST as $key => $value) {
if (!is_array($value)) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }	
}else {
    	 $$key = $value;
    }
}
if(!is_object($adminuser)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}
if($adminuser->group_id!=1) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn không đủ quyền!'));
    exit();
} 
if(!Library_Validation::isPhoneNumber($userphone)) {
    echo json_encode(array('code' => 1, 'msg' => 'SDT không hợp lệ!'));
    exit();
}
$users = $models_users->getObjectByCondition('', array('status' => 1, 'phone' => $userphone));
if (!is_object($users)) {
	echo json_encode(array('code' => 1, 'msg' => 'Người dùng không tồn tại!'));
    exit();
}
/*is_log
is_trans*/
$is_log =$field['is_log'];
$is_trans =$field['is_trans'];
$list_change=array();
if ($is_log['stt']!=0) {
	$pass =$is_log['val'];
	if (strlen($pass)<8) {
	echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu đăng nhập không được ít hơn 8 ký tự'));
    exit();
}
 $new_salt = md5(rand(0, 10000000000000));
$new_pass = hash('sha256', $pass . $new_salt);
/*   echo json_encode(array('code' => 1, 'msg' => $new_pass));
    exit();*/
$users->salt = $new_salt;
$users->password = $new_pass;
array_push($list_change, 'salt', 'password');

}
if ($is_trans['stt']!=0) {
	$passtrans =$is_trans['val'];
	if (strlen($passtrans)<8) {
	echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu giao dịch không được ít hơn 8 ký tự'));
    exit();
}
 $new_passtrans = md5($passtrans);
$users->trans_pass = $new_passtrans;
array_push($list_change, 'trans_pass');
}
if (count($list_change)>0) {
$models_users->setPersistents($users);

if ($models_users->edit($list_change,1)) {
    echo json_encode(array('code' => 0, 'msg' => "Lưu thành công"));
    exit();
}
}
    echo json_encode(array('code' => 1, 'msg' => 'Vui lòng chọn 1 loại tài khoản cần đổi!'));
    exit();

