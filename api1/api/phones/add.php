<?php    
header("Content-Type: application/json;charset=utf-8");

 require_once("../../config.php");
// thuc hien nap lai
$listk = array();
if (count($_POST)!=0) {
foreach ($_POST as $kreq =>  $req) {
		$$kreq = $req;
	array_push($listk, $kreq);
}
}
if (count($_GET)!=0) {
	foreach ($_GET as $kreq =>  $req) {
		$$kreq = $req;
	array_push($listk, $kreq);

}
}
/*echo $public_key = hash('sha256', $uid . $phone_num . $card_type . $trans_type.$is_sum.$money.'61cc1210ac99c2dbdc521f281f38ed09');
exit;*/
$listk = array_unique($listk);
$required =array('uid','phone_num','card_type','trans_type','is_sum','money','key');


if (count(array_diff($listk, $required))>0) {
	echo json_encode(array('status'=>1,'msg'=>'Dữ liệu không hợp lệ!'));

	exit;
}
$trans_type= intval($trans_type);
if ($trans_type!=0&&$trans_type!=1) {
	echo json_encode(array('status'=>1,'msg'=>'Kiểu giao dịch không hợp lệ!'));
	exit;
}
$is_sum= intval($is_sum);
if ($is_sum!=0&&$is_sum!=1) {
	echo json_encode(array('status'=>1,'msg'=>'Kiểu thanh toán không hợp lệ!'));
	exit;
}
$card_type = intval($card_type);
$arr_card = array(1,2,3);
if (!in_array($card_type, $arr_card)) {
	echo json_encode(array('status'=>1,'msg'=>'Kiểu thẻ không hợp lệ!'));
	exit;
}
/*http://14.232.84.85/api/phones/add.php?uid=6&phone_num=0967581301&card_type=1&trans_type=1&money=100000&*/
$models_user = new Models_Users();
$users = $models_user->getObject($uid);
if (!is_object($users)) {
	echo json_encode(array('status'=>1,'msg'=>'Người dùng không tồn tại!'));
	exit;
}
$scret_key = $users->scret_key;
$public_key = hash('sha256', $uid . $phone_num . $card_type . $trans_type.$is_sum.$money.$scret_key);

$money =intval($money);
$current_balance = $users->balance;
    $update_balance = $current_balance - $money;
if ($key!=$public_key) {
		echo json_encode(array('status'=>1,'msg'=>'Dữ liệu nhập vào không đúng!'));

	exit;
}
if ($update_balance<0) {
		echo json_encode(array('status'=>1,'msg'=>'Số dư không đủ!'));
	exit;
}
$phones = new Persistents_Phones();
$phones->phone = $phone_num;
$phones->loai = $card_type;
$phones->type = $trans_type;
$phones->canthanhtoan = $money;
$phones->gop = $is_sum;
$phones->userid = $users->getId();
$phones->time = time();
$phones->status = 1;

$models_phones = new Models_Phones($phones);
$last_id = $models_phones->add(1);
if($last_id){
    Library_Log::writeHtml("Balance : " . number_format($current_balance), $file_log);
    Library_Log::writeHtml("Update Balance : " . number_format($update_balance), $file_log);

    $users->balance = $update_balance;
    $models_users->setPersistents($users);
    
    $models_users->edit(array('balance'), 1);
    
    $phones = $models_phones->getObject($last_id);
    $phones->last_balance = $update_balance;
    $models_phones->setPersistents($phones);
    $models_phones->edit(array('last_balance'), 1);
    
    // them vao lich su cong tien
    $histories = new Persistents_Histories();
    $histories->admin_id = $users->getId();
    $histories->user_add = $users->getId();
    $histories->cur_balance = $current_balance;
    $histories->up_balance = $update_balance;
    $histories->money = $money*-1;
    $histories->time = time();
    $histories->note = 'Phone id : ' . $last_id;
    $histories->status = 1;
    $models_histories = new Models_Histories($histories);
    $models_histories->add();
    
    echo json_encode(array('code' => 0, 'msg' => 'Thêm số điện thoại thành công'));
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'SĐT đã tồn tại trong hệ thống!'));
}
 ?>