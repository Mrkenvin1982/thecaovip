<?php
include '../config.php';

// get post data
foreach($_POST as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}

if(!is_object($adminuser)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}
  $models_one_mil = new Models_OneMilTransactionAccess();
 $trans_c2_access_obj = $models_one_mil->getObject(2);
 if ($adminuser->group_id==3&&$trans_c2_access_obj->status==0) {
        echo json_encode(array('code' => 1, 'msg' => 'Chức năng này hiện đang tạm khóa cho đại lí cấp 2'));
    exit();
}
// check phone
if(!Library_Validation::isPhoneNumber($userphone)) {
    echo json_encode(array('code' => 1, 'msg' => 'SDT không hợp lệ!'));
    exit();
}

if(intval($money) < 100000 || intval($money) > 100000000) {
    echo json_encode(array('code' => 1, 'msg' => 'Số tiền trong khoản 100,000 -> 100,000,000'));
    exit();
}

$bf_balance =$adminuser->balance;
$af_balance = $bf_balance-intval($money);
if ($adminuser->group_id!=1&&$adminuser->phone==$userphone) {
     echo json_encode(array('code' => 1, 'msg' => 'Bạn không thể chuyển tiền cho chính mình!'));
    exit();
}
if ($adminuser->group_id!=1&&$af_balance<0) {
     echo json_encode(array('code' => 1, 'msg' => 'Tài khoản của bạn không đủ tiền'));
    exit();
}

$models_users = new Models_Users();
$admin = $models_users->getObjectByCondition('',array('id'=>$adminuser->getId(),'trans_pass'=>md5($trans_pass)));
if(!is_object($admin)) {
    echo json_encode(array('code' => 1, 'msg' => 'Mã giao dịch không đúng'));
    exit();
}
$users = $models_users->getObjectByCondition('', array('status' => 1, 'phone' => $userphone));

if(is_object($users)) {
    
    $file_log = "add_money.log";
    Library_Log::writeOpenTable($file_log);
    Library_Log::writeHtml("Admin : " . $adminuser->getId(), $file_log);
    Library_Log::writeHtml("UserAdd : " . $users->getId(), $file_log);
    Library_Log::writeHtml("Request : PhoneAdd : {$userphone}, Money : {$money}", $file_log);
    
    // cong tien 
    $db = Models_Db::getDBO();
    $db->beginTransaction();
    
    $current_balance = $users->balance;

    $update_balance = $current_balance + $money;

    Library_Log::writeHtml("Balance : " . number_format($current_balance), $file_log);
    Library_Log::writeHtml("Update Balance : " . number_format($update_balance), $file_log);

    $users->balance = $update_balance;
    $models_users->setPersistents($users);
    $models_users->edit(array('balance'), 1);
    $db->commit();
    
    // them vao lich su cong tien
    $histories = new Persistents_Histories();
    $histories->user_id =$users->getId();
    $histories->cur_balance = $current_balance;
    $histories->money = $money;
    $histories->up_balance = $update_balance;
    $histories->time = time();
    $histories->note = json_encode(array('uid'=>$adminuser->getId(),'msg'=>'Nhận tiền từ: '.$adminuser->name));
    $histories->orders = 1;
    $histories->status = 1;
    $models_histories = new Models_Histories($histories);
    $models_histories->add();

if ($adminuser->group_id!=1) {
            $adminuser->balance=$af_balance;
    $models_users->setPersistents($adminuser);
    $models_users->edit(array('balance'), 1);
}else{
    $af_balance=$bf_balance;
}

 $histories->user_id =$adminuser->getId();
    $histories->cur_balance = $bf_balance;
    $histories->money = $money*-1;
    $histories->up_balance = $af_balance;
    $histories->time = time();
    $histories->note = json_encode(array('uid'=>$users->getId(),'msg'=>'Chuyển tiền cho: '.$users->name));
    $histories->orders = 0;
    $histories->status = 1;
    $models_histories = new Models_Histories($histories);
    $models_histories->add();

    echo json_encode(array('code' => 0, 'msg' => 'Thành công!'));
    Library_Log::writeCloseTable($file_log);
}
else { 
    echo json_encode(array('code' => 1, 'msg' => 'Tài khoản không tồn tại!'));
}
