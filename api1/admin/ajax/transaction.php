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

   $model_trans = new Models_UserBankTransaction();
if($model_trans->updateStatus($id, $status, 'status')){
  switch ($status) {
    case 0:
                            $stt= 'Thất bại';
                                break;
                            
                            case 1:
                               $stt= 'Thành công';
                                break;
                            case 2:
                                $stt= 'Chưa xử lí';
                                break;
                            default:
                            $stt= 'Không xác định';
                            break;
  }
  echo json_encode(array('code' => 0, 'msg' => "Thay đổi trạng thái đổi thành : $stt"));
  exit;
}else{
	 echo json_encode(array('code' => 1, 'msg' => "Lỗi hệ thống!"));
  exit;
};