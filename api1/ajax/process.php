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
if(!isset($_SESSION['uid_'.$adminuser->getId()])||$_SESSION['uid_'.$adminuser->getId()]!="ok") {
    echo json_encode(array('code' => 1, 'msg' => 'Vui lòng nhập mã giao dịch để tiếp tục!'));
    exit();
}
    unset($_SESSION['uid_'.$adminuser->getId()]);

// check phone
if(!Library_Validation::isPhoneNumber($phone) && in_array($type, array(0, 1))) {
    echo json_encode(array('code' => 1, 'msg' => 'SDT không hợp lệ!'));
    exit();
}

// check ftth
if($type == 2 && stripos($phone, '_ftth_') !== 4) {
    //$msg_err[] = "FTTH {$phone} không hợp lệ!";
    //echo json_encode(array('code' => 1, 'msg' => 'FTTH không hợp lệ!'));
   // exit();
}

$type = intval($type);
$gop = intval($gop);
$canthanhtoan = intval($canthanhtoan);

if(!in_array($type, array(0,1,2,3))) {
    echo json_encode(array('code' => 1, 'msg' => 'Hình thức thanh toán không hợp lệ!'));
    exit();
}

if($gop != 0 && $gop != 1) {
    echo json_encode(array('code' => 1, 'msg' => 'Kiểu gộp không hợp lệ!'));
    exit();
}

if($canthanhtoan < 50000) {
    echo json_encode(array('code' => 1, 'msg' => 'Số tiền cần thanh toán >= 50,000!'));
    exit();
}

// kiem tra so du
if($adminuser->balance < $canthanhtoan) {
    echo json_encode(array('code' => 1, 'msg' => 'Tài khoản bạn không đủ tiền!'));
    exit();
}

$file_log = "add_phone.log";
Library_Log::writeOpenTable($file_log);
Library_Log::writeHtml("Admin : " . $adminuser->getId(), $file_log);
Library_Log::writeHtml("Request : Phone : {$phone}, Thanh toan : {$canthanhtoan}, Type : {$type}, Gop : {$gop}", $file_log);

$phones = new Persistents_Phones();
$phones->phone = $phone;
$phones->loai = $loai;
$phones->type = $type;
$phones->canthanhtoan = $canthanhtoan;
$phones->gop = $gop;
$phones->userid = $adminuser->getId();
$phones->time = time();
$phones->status = 1;

$models_phones = new Models_Phones($phones);
$last_id = $models_phones->add(1);
    
if($last_id) {
    $db = Models_Db::getDBO();
    $db->beginTransaction();
    
    $models_users = new Models_Users();
    $users = $models_users->getObject($adminuser->getId(), 1);

    $current_balance = $users->balance;
    $update_balance = $current_balance - $canthanhtoan;

    Library_Log::writeHtml("Balance : " . number_format($current_balance), $file_log);
    Library_Log::writeHtml("Update Balance : " . number_format($update_balance), $file_log);

    $users->balance = $update_balance;
    $models_users->setPersistents($users);
    
    $models_users->edit(array('balance'), 1);
    $db->commit();
    
    $phones = $models_phones->getObject($last_id);
    $phones->last_balance = $update_balance;
    $models_phones->setPersistents($phones);
    $models_phones->edit(array('last_balance'), 1);
    
    // them vao lich su cong tien
    $histories = new Persistents_Histories();
    $histories->user_id = $adminuser->getId();
    $histories->cur_balance = $current_balance;
    $histories->money = $canthanhtoan*-1;

    $histories->up_balance = $update_balance;
    $histories->time = time();
    $histories->note = 'Phone id : ' . $last_id;
    $histories->status = 1;
    $models_histories = new Models_Histories($histories);
    $models_histories->add();
    
    echo json_encode(array('code' => 0, 'msg' => 'thanh cong'));
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'SĐT đã tồn tại trong hệ thống!'));
}