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
$models_user = new Models_Users();

$condi = "'%{$search}%'";

$users = $models_user->customQuery("Select * from Users where name like $condi");
$res =array();
foreach ($users as $user) {
	$res[] = array("name"=>$user->name,"label"=>$user->name."(".$user->phone.")","phone"=>$user->phone);
}
echo json_encode($res);
?>
