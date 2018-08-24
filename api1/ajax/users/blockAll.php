<?php 
include '../../config.php'; 
foreach($_POST as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}
if(!is_object($adminuser)||$adminuser->group_id!=1) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}
$models_user = new Models_Users();
$listUser = $models_user->customFilter('',array('refer'=>$uid));
$listsuccess=0;
foreach ($listUser as $user) {
	 $user->status =2;
	 $models_user->setPersistents($user);
	 if ($models_user->edit(array('status'),1)) {
	 	$listsuccess++;
	 }
}
if (count($listUser)==$listsuccess) {
	 echo json_encode(array('code' => 0, 'msg' => 'Khóa thành công!'));
    exit();
}
 echo json_encode(array('code' => 1, 'msg' => 'Xảy ra 1 ít lỗi!'));
    exit();