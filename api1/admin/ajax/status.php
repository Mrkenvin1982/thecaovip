<?php require_once '../../config.php';
header("Content-Type: application/json;charset=utf-8");
/*$json=file_get_contents('php://input');
$obj = json_decode($json);*/

foreach($_POST as $key => $value) {
    $$key = trim(Library_Validation::antiSql($value));
}
   if (!isset($admin_logged)) {
    echo json_encode(array('code' => 1, 'msg' => 'Vui lòng đăng nhập lại'));
    exit();
   }
   $model_status = new $model();
if($model_status->updateStatus($oid, $stt, $field)){
  echo json_encode(array('code' => 0, 'msg' => $model_status->getSql()));
  exit;
}else{
	 echo json_encode(array('code' => 1, 'msg' => "Lỗi hệ thống"));
  exit;
};