<?php require_once '../../../config.php';
header("Content-Type: application/json;charset=utf-8");
/*$json=file_get_contents('php://input');
$obj = json_decode($json);*/

foreach($_POST as $key => $value) {
    $$key = trim($value);
}

$models_users = new Models_Users();
$users = $models_users->getObject($id);
if (is_object($users)) {
	if(strlen($new_pass) < 8) {
    echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu mới tối thiểu 8 ký tự!'));
    exit();
}
if($new_pass!=$re_new_pass) {
    echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu không khớp nhau!'));
    exit();
}

if ($type==1) {
   $new_salt = md5(rand(0, 10000000000000));
$new_pass = hash('sha256', $new_pass . $new_salt);
$users->salt = $new_salt;
$users->password = $new_pass;
$change = array('salt', 'password');
}else{
    $users->trans_pass = md5($re_new_pass);
$change=array('trans_pass');
}
$models_users->setPersistents($users);
if($models_users->edit($change, 1)) {
    echo json_encode(array('code' => 0, 'msg' => 'Đổi mật khẩu thành công!'));
    unset($_SESSION['admin_logged']);
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'Thất bại!'));
}

}