<?php require_once '../../config.php';
header("Content-Type: application/json;charset=utf-8");
/*$json=file_get_contents('php://input');
$obj = json_decode($json);*/

foreach($_POST as $key => $value) {
    $$key = trim(Library_Validation::antiSql($value));
}
$account=$_POST["account"];
$password=$_POST["password"];
$models_admin = new Models_AdminUsers();
$admin = $models_admin->getObjectByCondition('', array('username' => $account,'password'=>md5($password)));

if (is_object($admin)) {

		$_SESSION['admin']=$admin;
	echo json_encode(array('code'=>0,'link'=>$base_url.'/admin/'));
	exit;
}
echo json_encode(array('code'=>1,'msg'=>'Tài khoản hoặc mật khẩu không đúng!'));
		exit;
/*$models_user = new Models_Users();
$user = $models_user->getObjectByCondition('', array('email' => $account));
// thu tiep sdt
if(!is_object($user)) {
    $user = $models_user->getObjectByCondition('', array('phone' => $account));
}
$valid=0;
if (is_object($user)) {
	$salt = $user->getSalt();
	if (hash('sha256', $password . $salt)==$user->getPassword()) {
		$valid=1;
	}
}
if ($valid==1) {

		$_SESSION['users']=$user;
	echo json_encode(array('code'=>0,'msg'=>'Đăng nhập thành công!','link'=>'https://cardpay.vn/doi-tac/'));
	exit;
}else{
		echo json_encode(array('code'=>1,'msg'=>'Tài khoản hoặc mật khẩu không đúng!'));
		exit;
}*/